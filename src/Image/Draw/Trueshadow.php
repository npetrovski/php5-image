<?php

/**
 * image-draw-Trueshadow
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
require_once 'Image/Image.php';

require_once 'Image/Plugin/Base.php';

require_once 'Image/Plugin/Interface.php';

class Image_Draw_Trueshadow extends Image_Draw_Abstract implements Image_Plugin_Interface {

    public function __construct($distance = 10, $color = "888888", $matrix = array(1, 2, 4, 4, 8, 8, 12, 12, 16, 16, 24, 32, 32, 24, 16, 16, 12, 12, 8, 8, 4, 4, 2, 1)) {
        $this->distance = $distance;
        $this->color = $color;
        $this->matrix = $matrix;
    }

    public function setDistance($distance = 10) {
        $this->distance = $distance;
    }

    public function generate() {
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        $distance = $this->distance;
        $matrix = $this->matrix;
        $matrix_width = count($matrix);
        $matrix_sum = array_sum($matrix);
        $c = 0;
        $m_offset = floor($matrix_width / 2);
        $temp = new Image_Image();
        $temp->createImageTrueColorTransparent($width + ($matrix_width * 2), $height + ($matrix_width * 2));
        imagecopy($temp->image, $this->_owner->image, $matrix_width, $matrix_width, 0, 0, $width, $height);
        $w = $temp->imagesx();
        $h = $temp->imagesy();
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $t = $temp->imagecolorat($x, $y);
                $t1 = Image_Image::intColorToArrayColor($t);
                $p[$x][$y]['r'] = $t1['red'];
                $p[$x][$y]['g'] = $t1['green'];
                $p[$x][$y]['b'] = $t1['blue'];
                $p[$x][$y]['a'] = $t1['alpha'];
                $p1[$x][$y]['r'] = 255;
                $p1[$x][$y]['g'] = 255;
                $p1[$x][$y]['b'] = 255;
                $p1[$x][$y]['a'] = 127;
            }
        }
        $w = $this->_owner->imagesx();
        $h = $this->_owner->imagesy();
        $d_offset = $distance - $matrix_width;
        if ($this->color != "image") {
            $arrColor = $this->_owner->hexColorToArrayColor($this->color);
        }
        $temp->createImageTrueColorTransparent($width + $distance + $m_offset, $height + $distance + $m_offset);
        imagesavealpha($temp->image, true);
        imagealphablending($temp->image, true);
        for ($i = $m_offset; $i < $w + $m_offset + $matrix_width; $i++) {
            for ($j = $m_offset; $j < $h + $m_offset + $matrix_width; $j++) {
                $sumr = 0;
                $sumg = 0;
                $sumb = 0;
                $suma = 0;
                for ($k = 0; $k < $matrix_width; $k++) {
                    $xx = $i - (($matrix_width) >> 1) + $k;
                    $sumr += $p[$xx][$j]['r'] * $matrix[$k];
                    $sumg += $p[$xx][$j]['g'] * $matrix[$k];
                    $sumb += $p[$xx][$j]['b'] * $matrix[$k];
                    $suma += $p[$xx][$j]['a'] * $matrix[$k];
                }
                $p1[$i][$j]['r'] = $sumr / $matrix_sum;
                $p1[$i][$j]['g'] = $sumg / $matrix_sum;
                $p1[$i][$j]['b'] = $sumb / $matrix_sum;
                $p1[$i][$j]['a'] = $suma / $matrix_sum;
            }
        }
        for ($i = $m_offset; $i < $w + $m_offset + $matrix_width; $i++) {
            for ($j = $m_offset; $j < $h + $m_offset + $matrix_width; $j++) {
                $sumr = 0;
                $sumg = 0;
                $sumb = 0;
                $suma = 0;
                for ($k = 0; $k < $matrix_width; $k++) {
                    $xy = $j - (($matrix_width) >> 1) + $k;
                    $sumr += $p1[$i][$xy]['r'] * $matrix[$k];
                    $sumg += $p1[$i][$xy]['g'] * $matrix[$k];
                    $sumb += $p1[$i][$xy]['b'] * $matrix[$k];
                    $suma += $p1[$i][$xy]['a'] * $matrix[$k];
                }
                if ($this->color != "image") {
                    $col = imagecolorallocatealpha($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue'], ($suma / $matrix_sum));
                } else {
                    $col = imagecolorallocatealpha($temp->image, ($sumr /
                            $matrix_sum), ($sumg /
                            $matrix_sum), ($sumb /
                            $matrix_sum), ($suma / $matrix_sum));
                }
                imagesetpixel($temp->image, $i + $d_offset, $j + $d_offset, $col);
            }
        }

        imagecopy($temp->image, $this->_owner->image, 0, 0, 0, 0, $width, $height);
        $this->_owner->image = $temp->image;
        unset($temp);
        return true;
    }

}
