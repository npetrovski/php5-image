<?php

/**
 * image-helper-adapter-psd
 *
 * Copyright (c) 2009-2011, Nikolay Petrovski <to.petrovski@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   Image
 * @author    Nikolay Petrovski <to.petrovski@gmail.com>
 * @copyright 2009-2011 Nikolay Petrovski <to.petrovski@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.0.0
 */

namespace Image\Reader\Adapter;

use Image\Reader\ReaderAbstract;

class Psd extends ReaderAbstract {

    public $infoArray;
    public $fp;
    public $fileName;
    public $tempFileName;
    public $colorBytesLength;

    private function _init($filename) {

        set_time_limit(0);
        $this->infoArray = array();
        $this->fileName = $filename;
        $this->fp = fopen($this->fileName, 'r');

        if (fread($this->fp, 4) == '8BPS') {
            $this->infoArray['version id'] = $this->_getInteger(2);
            fseek($this->fp, 6, SEEK_CUR); // 6 bytes of 0's
            $this->infoArray['channels'] = $this->_getInteger(2);
            $this->infoArray['rows'] = $this->_getInteger(4);
            $this->infoArray['columns'] = $this->_getInteger(4);
            $this->infoArray['colorDepth'] = $this->_getInteger(2);
            $this->infoArray['colorMode'] = $this->_getInteger(2);


            /* COLOR MODE DATA SECTION */ //4bytes Length The length of the following color data.
            $this->infoArray['colorModeDataSectionLength'] = $this->_getInteger(4);
            fseek($this->fp, $this->infoArray['colorModeDataSectionLength'], SEEK_CUR); // ignore this snizzle

            /*  IMAGE RESOURCES */
            $this->infoArray['imageResourcesSectionLength'] = $this->_getInteger(4);
            fseek($this->fp, $this->infoArray['imageResourcesSectionLength'], SEEK_CUR); // ignore this snizzle

            /*  LAYER AND MASK */
            $this->infoArray['layerMaskDataSectionLength'] = $this->_getInteger(4);
            fseek($this->fp, $this->infoArray['layerMaskDataSectionLength'], SEEK_CUR); // ignore this snizzle


            /*  IMAGE DATA */
            $this->infoArray['compressionType'] = $this->_getInteger(2);
            $this->infoArray['oneColorChannelPixelBytes'] = $this->infoArray['colorDepth'] / 8;
            $this->colorBytesLength = $this->infoArray['rows'] * $this->infoArray['columns'] * $this->infoArray['oneColorChannelPixelBytes'];

            if ($this->infoArray['colorMode'] == 2) {
                $this->infoArray['error'] = 'images with indexed colours are not supported yet';
                return false;
            }
        } else {
            $this->infoArray['error'] = 'invalid or unsupported psd';
            return false;
        }
    }

    /**
     * Return an image resource from file or URL
     * 
     * @param string $filename
     * @return resource an image resource identifier on success, false on errors.
     */
    public function getImage($filename) {

        $this->_init($filename);

        // decompress image data if required
        switch ($this->infoArray['compressionType']) {
            // case 2:, case 3: zip not supported yet..
            case 1:
                // packed bits
                $this->infoArray['scanLinesByteCounts'] = array();
                for ($i = 0; $i < ($this->infoArray['rows'] * $this->infoArray['channels']); $i++)
                    $this->infoArray['scanLinesByteCounts'][] = $this->_getInteger(2);
                $this->tempFileName = tempnam(sys_get_temp_dir(), 'decompressedImageData');
                $tfp = fopen($this->tempFileName, 'wb');
                foreach ($this->infoArray['scanLinesByteCounts'] as $scanLinesByteCount) {
                    fwrite($tfp, $this->_getPackedBitsDecoded(fread($this->fp, $scanLinesByteCount)));
                }
                fclose($tfp);
                fclose($this->fp);
                $this->fp = fopen($this->tempFileName, 'r');
            default:
                // continue with current file handle;
                break;
        }

        // let's write pixel by pixel....
        $image = imagecreatetruecolor($this->infoArray['columns'], $this->infoArray['rows']);

        for ($rowPointer = 0; ($rowPointer < $this->infoArray['rows']); $rowPointer++) {
            for ($columnPointer = 0; ($columnPointer < $this->infoArray['columns']); $columnPointer++) {
                /* 	The color mode of the file. Supported values are: Bitmap=0;
                  Grayscale=1; Indexed=2; RGB=3; CMYK=4; Multichannel=7;
                  Duotone=8; Lab=9.
                 */
                switch ($this->infoArray['colorMode']) {
                    case 2: // indexed... info should be able to extract from color mode data section. not implemented yet, so is grayscale
                        exit;
                        break;
                    case 0:
                        // bit by bit
                        if ($columnPointer == 0)
                            $bitPointer = 0;
                        if ($bitPointer == 0)
                            $currentByteBits = str_pad(base_convert(bin2hex(fread($this->fp, 1)), 16, 2), 8, '0', STR_PAD_LEFT);
                        $r = $g = $b = (($currentByteBits[$bitPointer] == '1') ? 0 : 255);
                        $bitPointer++;
                        if ($bitPointer == 8)
                            $bitPointer = 0;
                        break;

                    case 1:
                    case 8: // 8 is indexed with 1 color..., so grayscale
                        $r = $g = $b = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        break;

                    case 4: // CMYK
                        $c = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        $currentPointerPos = ftell($this->fp);
                        fseek($this->fp, $this->colorBytesLength - 1, SEEK_CUR);
                        $m = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        fseek($this->fp, $this->colorBytesLength - 1, SEEK_CUR);
                        $y = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        fseek($this->fp, $this->colorBytesLength - 1, SEEK_CUR);
                        $k = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        fseek($this->fp, $currentPointerPos);
                        $r = round(($c * $k) / (pow(2, $this->infoArray['colorDepth']) - 1));
                        $g = round(($m * $k) / (pow(2, $this->infoArray['colorDepth']) - 1));
                        $b = round(($y * $k) / (pow(2, $this->infoArray['colorDepth']) - 1));

                        break;

                    case 9: // hunter Lab
                        // i still need an understandable lab2rgb convert algorithm... if you have one, please let me know!
                        $l = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        $currentPointerPos = ftell($this->fp);
                        fseek($this->fp, $this->colorBytesLength - 1, SEEK_CUR);
                        $a = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        fseek($this->fp, $this->colorBytesLength - 1, SEEK_CUR);
                        $b = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        fseek($this->fp, $currentPointerPos);

                        $r = $l;
                        $g = $a;
                        $b = $b;

                        break;
                    default:
                        $r = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        $currentPointerPos = ftell($this->fp);
                        fseek($this->fp, $this->colorBytesLength - 1, SEEK_CUR);
                        $g = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        fseek($this->fp, $this->colorBytesLength - 1, SEEK_CUR);
                        $b = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
                        fseek($this->fp, $currentPointerPos);
                        break;
                }

                if (($this->infoArray['oneColorChannelPixelBytes'] == 2)) {
                    $r = $r >> 8;
                    $g = $g >> 8;
                    $b = $b >> 8;
                } elseif (($this->infoArray['oneColorChannelPixelBytes'] == 4)) {
                    $r = $r >> 24;
                    $g = $g >> 24;
                    $b = $b >> 24;
                }

                $pixelColor = imagecolorallocate($image, $r, $g, $b);
                imagesetpixel($image, $columnPointer, $rowPointer, $pixelColor);
            }
        }
        fclose($this->fp);
        if (isset($this->tempFileName))
            unlink($this->tempFileName);
        return $image;
    }

    /**
     * The PackBits algorithm will precede a block of data with a one byte header n, where n is interpreted as follows:
     * n Meaning
     * 0 to 127 Copy the next n + 1 symbols verbatim
     * -127 to -1 Repeat the next symbol 1 - n times
     * -128 Do nothing
     * 
     * Decoding:
     * Step 1. Read the block header (n).
     * Step 2. If the header is an EOF exit.
     * Step 3. If n is non-negative, copy the next n + 1 symbols to the output stream and go to step 1.
     * Step 4. If n is negative, write 1 - n copies of the next symbol to the output stream and go to step 1.
     * 
     * @param type $string
     * @return string 
     */
    private function _getPackedBitsDecoded($string) {

        $stringPointer = 0;
        $returnString = '';

        while (1) {
            if (isset($string[$stringPointer]))
                $headerByteValue = $this->_unsignedToSigned(hexdec(bin2hex($string[$stringPointer])), 1);
            else
                return $returnString;
            $stringPointer++;

            if ($headerByteValue >= 0) {
                for ($i = 0; $i <= $headerByteValue; $i++) {
                    $returnString .= $string[$stringPointer];
                    $stringPointer++;
                }
            } else {
                if ($headerByteValue != -128) {
                    $copyByte = $string[$stringPointer];
                    $stringPointer++;

                    for ($i = 0; $i < (1 - $headerByteValue); $i++) {
                        $returnString .= $copyByte;
                    }
                }
            }
        }
    }

    private function _unsignedToSigned($int, $byteSize = 1) {
        switch ($byteSize) {
            case 1:
                if ($int < 128)
                    return $int;
                else
                    return -256 + $int;
                break;

            case 2:
                if ($int < 32768)
                    return $int;
                else
                    return -65536 + $int;

            case 4:
                if ($int < 2147483648)
                    return $int;
                else
                    return -4294967296 + $int;

            default:
                return $int;
        }
    }

    private function _hexReverse($hex) {
        $output = '';

        if (strlen($hex) % 2) {
            return false;
        }

        for ($pointer = strlen($hex); $pointer >= 0; $pointer -= 2) {
            $output .= substr($hex, $pointer, 2);
        }

        return $output;
    }

    private function _getInteger($byteCount = 1) {
        switch ($byteCount) {
            case 4:
                // for some strange reason this is still broken...
                return @reset(unpack('N', fread($this->fp, 4)));
                break;

            case 2:
                return @reset(unpack('n', fread($this->fp, 2)));
                break;

            default:
                return hexdec($this->_hexReverse(bin2hex(fread($this->fp, $byteCount))));
        }
    }

}
