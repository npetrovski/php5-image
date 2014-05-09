<?php

/**
 * image-fx-sobel
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
namespace Image\Fx;

use Image\Helper\Color;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Sobel extends FxBase implements PluginInterface {

    public function generate() {
        
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        $mx = array(
            array(
                - 0,   0,   0
            ), array(
                - 1,   0,   1
            ), array(
                - 0,   0,   0
            )
        );
        $my = array(
            array(
                  0,   1,   0
            ), array(
                  0,   0,   0
            ), array(
                - 0, - 1, - 0
            )
        );
        $m_elements = count($mx);
        $m_offset = floor($m_elements / 2);
        $p = array();
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = $this->_owner->imagecolorat($x, $y);
                $arrRgb = Color::intColorToArrayColor($rgb);
                $p[$x][$y]['r'] = $arrRgb['red'];
                $p[$x][$y]['g'] = $arrRgb['green'];
                $p[$x][$y]['b'] = $arrRgb['blue'];
            }
        }
        for ($y = 1; $y < $height - 1; $y++) {
            for ($x = 1; $x < $width - 1; $x++) {
                $sumXr = $sumXg = $sumXb = $sumYr = $sumYg = $sumYb = 0;
                for ($i = - 1; $i <= 1; $i++) {
                    for ($j = - 1; $j <= 1; $j++) {
                        $sumXr = $sumXr + (($p[$x + $i][$y + $j]['r']) * $mx[$i + 1][$j + 1]);
                        $sumXg = $sumXg + (($p[$x + $i][$y + $j]['g']) * $mx[$i + 1][$j + 1]);
                        $sumXb = $sumXb + (($p[$x + $i][$y + $j]['b']) * $mx[$i + 1][$j + 1]);
                        $sumYr = $sumYr + (($p[$x + $i][$y + $j]['r']) * $my[$i + 1][$j + 1]);
                        $sumYg = $sumYg + (($p[$x + $i][$y + $j]['g']) * $my[$i + 1][$j + 1]);
                        $sumYb = $sumYb + (($p[$x + $i][$y + $j]['b']) * $my[$i + 1][$j + 1]);
                    }
                }
                $sumr = abs($sumXr) + abs($sumYr);
                $sumg = abs($sumXg) + abs($sumYg);
                $sumb = abs($sumXb) + abs($sumYb);
                if ($sumr > 255)
                    $sumr = 255;
                if ($sumg > 255)
                    $sumg = 255;
                if ($sumb > 255)
                    $sumb = 255;
                if ($sumr < 0)
                    $sumr = 0;
                if ($sumg < 0)
                    $sumg = 0;
                if ($sumb < 0)
                    $sumb = 0;
                
                $color = imagecolorallocate($this->_owner->image, 255 - $sumr, 255 - $sumg, 255 - $sumb);
                imagesetpixel($this->_owner->image, $x, $y, $color);
            }
        }
        return true;
    }

}
