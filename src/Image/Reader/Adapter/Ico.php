<?php

/**
 * image-reader-adapter-ico
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

/**
 * Open ICO files and extract any size/depth to PNG format
 */
class Ico extends ReaderAbstract {

    /**
     * Background color on icon extraction
     *
     * @type array(R, G, B) = array(255, 255, 255)
     * @var  public
     */
    private $__bgcolor = array(255, 255, 255);

    /**
     * Is background color transparent?
     *
     * @type boolean = false
     * @var  public
     */
    private $__bgcolor_transparent = true;

    /**
     * Load an ICO data. If you prefer to open the file
     * and return the binary data you can use this function
     * directly. Otherwise use LoadFile() instead.
     *
     * @param   string   $data   Binary data of ICO file
     * @return  boolean          Success
     */
    private function __loadData($data) {
        $this->formats = array();

        /**
         * ICO header
         * */
        $icodata = unpack("SReserved/SType/SCount", $data);
        $this->ico = $icodata;
        $data = substr($data, 6);

        /**
         * Extract each icon header
         */
        for ($i = 0; $i < $this->ico['Count']; $i++) {
            $icodata = unpack("CWidth/CHeight/CColorCount/CReserved/SPlanes/SBitCount/LSizeInBytes/LFileOffset", $data);
            if (0 == $icodata['Width']) 
                $icodata['Width'] = 256;
            if (0 == $icodata['Height']) 
                $icodata['Height'] = 256;
            $icodata['FileOffset'] -= ( $this->ico['Count'] * 16) + 6;
            if ($icodata['ColorCount'] == 0)
                $icodata['ColorCount'] = 256;
            $this->formats[] = $icodata;

            $data = substr($data, 16);
        }

        /**
         * Extract aditional headers for each extracted icon header
         */
        for ($i = 0; $i < count($this->formats); $i++) {
            $icodata = unpack("LSize/LWidth/LHeight/SPlanes/SBitCount/LCompression/LImageSize/LXpixelsPerM/LYpixelsPerM/LColorsUsed/LColorsImportant", substr($data, $this->formats[$i]['FileOffset']));

            $this->formats[$i]['header'] = $icodata;
            $this->formats[$i]['colors'] = array();

            $this->formats[$i]['BitCount'] = $this->formats[$i]['header']['BitCount'];

            switch ($this->formats[$i]['BitCount']) {
                case 32:
                case 24:
                    $length = $this->formats[$i]['header']['Width'] * $this->formats[$i]['header']['Height'] * ($this->formats[$i]['BitCount'] / 8);
                    $this->formats[$i]['data'] = substr($data, $this->formats[$i]['FileOffset'] + $this->formats[$i]['header']['Size'], $length);
                    break;
                case 8:
                case 4:
                    $icodata = substr($data, $this->formats[$i]['FileOffset'] + $icodata['Size'], $this->formats[$i]['ColorCount'] * 4);
                    $offset = 0;
                    for ($j = 0; $j < $this->formats[$i]['ColorCount']; $j++) {
                        $this->formats[$i]['colors'][] = array(
                            'red' => ord($icodata[$offset]),
                            'green' => ord($icodata[$offset + 1]),
                            'blue' => ord($icodata[$offset + 2]),
                            'reserved' => ord($icodata[$offset + 3])
                        );
                        $offset += 4;
                    }
                    $length = $this->formats[$i]['header']['Width'] * $this->formats[$i]['header']['Height'] * (1 + $this->formats[$i]['BitCount']) / $this->formats[$i]['BitCount'];
                    $this->formats[$i]['data'] = substr($data, $this->formats[$i]['FileOffset'] + ($this->formats[$i]['ColorCount'] * 4) + $this->formats[$i]['header']['Size'], $length);
                    break;
                case 1:
                    $icodata = substr($data, $this->formats[$i]['FileOffset'] + $icodata['Size'], $this->formats[$i]['ColorCount'] * 4);

                    $this->formats[$i]['colors'][] = array(
                        'blue' => ord($icodata[0]),
                        'green' => ord($icodata[1]),
                        'red' => ord($icodata[2]),
                        'reserved' => ord($icodata[3])
                    );
                    $this->formats[$i]['colors'][] = array(
                        'blue' => ord($icodata[4]),
                        'green' => ord($icodata[5]),
                        'red' => ord($icodata[6]),
                        'reserved' => ord($icodata[7])
                    );

                    $length = $this->formats[$i]['header']['Width'] * $this->formats[$i]['header']['Height'] / 8;
                    $this->formats[$i]['data'] = substr($data, $this->formats[$i]['FileOffset'] + $this->formats[$i]['header']['Size'] + 8, $length);
                    break;
            }
            $this->formats[$i]['data_length'] = strlen($this->formats[$i]['data']);
        }

        return true;
    }

    /**
     * Return the total icons extracted at the moment
     *
     * @return  integer   Total icons
     */
    public function TotalIcons() {
        return count($this->formats);
    }

    /**
     * Ico::GetIconInfo()
     * Return the icon header corresponding to that index
     *
     * @param   integer   $index    Icon index
     * @return  resource            Icon header
     * */
    public function GetIconInfo($index) {
        if (isset($this->formats[$index])) {
            return $this->formats[$index];
        }
        return false;
    }

    /**
     * Changes background color of extraction. You can set
     * the 3 color components or set $red = '#xxxxxx' (HTML format)
     * and leave all other blanks.
     *
     * @param   optional   integer   $red     Red component
     * @param   optional   integer   $green   Green component
     * @param   optional   integer   $blue    Blue component
     * @return             self
     */
    public function setBackground($red = 255, $green = 255, $blue = 255) {
        if (is_string($red) && preg_match('/^\#[0-9a-f]{6}$/', $red)) {
            $green = hexdec($red[3] . $red[4]);
            $blue = hexdec($red[5] . $red[6]);
            $red = hexdec($red[1] . $red[2]);
        }

        $this->__bgcolor = array($red, $green, $blue);
        return $this;
    }

    /**
     * Set background color to be saved as transparent
     *
     * @param   optional   boolean   $is_transparent   Is Transparent or not
     * @return             boolean                     Is Transparent or not
     */
    public function setBackgroundTransparent($is_transparent = true) {
        return ($this->__bgcolor_transparent = $is_transparent);
    }

    /**
     * Return an image resource from file or URL
     * 
     * @param string $filename
     * @param integer $index   Position of the icon inside ICO
     * @return resource an image resource identifier on success, false on errors.
     */
    public function getImage($filename, $index = 0) {

        if (($fp = @fopen($filename, 'rb')) !== false) {
            $data = '';
            while (!feof($fp)) {
                $data .= fread($fp, 4096);
            }
            fclose($fp);
        }

        if (!$this->__loadData($data)) {
            return false;
        }

        if (!isset($this->formats[$index])) {
            return false;
        }

        $im = imagecreatetruecolor($this->formats[$index]['Width'], $this->formats[$index]['Height']);
        $bgcolor = $this->__allocateColor($im, $this->__bgcolor[0], $this->__bgcolor[1], $this->__bgcolor[2]);
        imagefilledrectangle($im, 0, 0, $this->formats[$index]['Width'], $this->formats[$index]['Height'], $bgcolor);

        if ($this->__bgcolor_transparent) {
            imagecolortransparent($im, $bgcolor);
        }

        /**
         * allocate pallete and get XOR image
         */
        if (in_array($this->formats[$index]['BitCount'], array(1, 4, 8, 24))) {
            if ($this->formats[$index]['BitCount'] != 24) {
                /**
                 * color pallete
                 */
                $c = array();
                for ($i = 0; $i < $this->formats[$index]['ColorCount']; $i++) {
                    $c[$i] = $this->__allocateColor($im, $this->formats[$index]['colors'][$i]['red'], $this->formats[$index]['colors'][$i]['green'], $this->formats[$index]['colors'][$i]['blue'], round($this->formats[$index]['colors'][$i]['reserved'] / 255 * 127));
                }
            }

            /**
             * XOR image
             */
            $width = $this->formats[$index]['Width'];
            if (($width % 32) > 0) {
                $width += ( 32 - ($this->formats[$index]['Width'] % 32));
            }
            $offset = $this->formats[$index]['Width'] * $this->formats[$index]['Height'] * $this->formats[$index]['BitCount'] / 8;
            $total_bytes = ($width * $this->formats[$index]['Height']) / 8;
            $bits = '';
            $bytes = 0;
            $bytes_per_line = ($this->formats[$index]['Width'] / 8);
            $bytes_to_remove = (($width - $this->formats[$index]['Width']) / 8);
            for ($i = 0; $i < $total_bytes; $i++) {
                $bits .= str_pad(decbin(ord($this->formats[$index]['data'][$offset + $i])), 8, '0', STR_PAD_LEFT);
                $bytes++;
                if ($bytes == $bytes_per_line) {
                    $i += $bytes_to_remove;
                    $bytes = 0;
                }
            }
        }

        /**
         * paint each pixel depending on bit count 
         */
        switch ($this->formats[$index]['BitCount']) {
            case 32:
                /**
                 * 32 bits: 4 bytes per pixel [ B | G | R | ALPHA ]
                 */
                $offset = 0;
                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        $color = substr($this->formats[$index]['data'], $offset, 4);
                        if (ord($color[3]) > 0) {
                            $c = $this->__allocateColor($im, ord($color[2]), ord($color[1]), ord($color[0]), 127 - round(ord($color[3]) / 255 * 127));
                            imagesetpixel($im, $j, $i, $c);
                        }
                        $offset += 4;
                    }
                }
                break;
            case 24:
                /**
                 * 24 bits: 3 bytes per pixel [ B | G | R ]
                 */
                $offset = 0;
                $bitoffset = 0;
                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        if ($bits[$bitoffset] == 0) {
                            $color = substr($this->formats[$index]['data'], $offset, 3);
                            $c = $this->__allocateColor($im, ord($color[2]), ord($color[1]), ord($color[0]));
                            imagesetpixel($im, $j, $i, $c);
                        }
                        $offset += 3;
                        $bitoffset++;
                    }
                }
                break;
            case 8:
                /**
                 * 8 bits: 1 byte per pixel [ COLOR INDEX ]
                 */
                $offset = 0;
                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        if ($bits[$offset] == 0) {
                            $color = ord(substr($this->formats[$index]['data'], $offset, 1));
                            imagesetpixel($im, $j, $i, $c[$color]);
                        }
                        $offset++;
                    }
                }
                break;
            case 4:
                /**
                 * 4 bits: half byte/nibble per pixel [ COLOR INDEX ]
                 */
                $offset = 0;
                $maskoffset = 0;
                $leftbits = true;
                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        if ($leftbits) {
                            $color = substr($this->formats[$index]['data'], $offset, 1);
                            $color = array(
                                'High' => bindec(substr(decbin(ord($color)), 0, 4)),
                                'Low' => bindec(substr(decbin(ord($color)), 4))
                            );
                            if ($bits[$maskoffset++] == 0) {
                                imagesetpixel($im, $j, $i, $c[$color['High']]);
                            }
                            $leftbits = false;
                        } else {
                            if ($bits[$maskoffset++] == 0) {
                                imagesetpixel($im, $j, $i, $c[$color['Low']]);
                            }
                            $offset++;
                            $leftbits = true;
                        }
                    }
                }
                break;
            case 1:
                /**
                 * 1 bit: 1 bit per pixel (2 colors, usually black&white) [ COLOR INDEX ]
                 */
                $colorbits = '';
                $total = strlen($this->formats[$index]['data']);
                for ($i = 0; $i < $total; $i++) {
                    $colorbits .= str_pad(decbin(ord($this->formats[$index]['data'][$i])), 8, '0', STR_PAD_LEFT);
                }

                $total = strlen($colorbits);
                $offset = 0;
                for ($i = $this->formats[$index]['Height'] - 1; $i >= 0; $i--) {
                    for ($j = 0; $j < $this->formats[$index]['Width']; $j++) {
                        if ($bits[$offset] == 0) {
                            imagesetpixel($im, $j, $i, $c[$colorbits[$offset]]);
                        }
                        $offset++;
                    }
                }
                break;
        }

        return $im;
    }

    /**
     * Allocate a color on $im resource. This function prevents
     * from allocating same colors on the same pallete. Instead
     * if it finds that the color is already allocated, it only
     * returns the index to that color.
     * It supports alpha channel.
     *
     * @param               resource    $im       Image resource
     * @param               integer     $red      Red component
     * @param               integer     $green    Green component
     * @param               integer     $blue     Blue component
     * @param   optional    integer     $alphpa   Alpha channel
     * @return              integer               Color index
     */
    private function __allocateColor(&$im, $red, $green, $blue, $alpha = 0) {
        $c = imagecolorexactalpha($im, $red, $green, $blue, $alpha);
        if ($c >= 0) {
            return $c;
        }
        return imagecolorallocatealpha($im, $red, $green, $blue, $alpha);
    }

}

