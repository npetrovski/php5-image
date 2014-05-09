<?php

/**
 * image-fx-corners
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

use Image\Base;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Corners extends FxBase implements PluginInterface {

    public function __construct($radius_x = 0, $radius_y = 0) {
        $this->radius_x = $radius_x;
        $this->radius_y = $radius_y;
    }

    public function setRadius($x = 10, $y = 10) {
        $this->radius_x = $x;
        $this->radius_y = $y;
        return $this;
    }

    public function generate() {
        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, false);
        $image_x = $this->_owner->imagesx();
        $image_y = $this->_owner->imagesy();
        $gdCorner = imagecreatefromstring(base64_decode($this->_cornerpng()));
        $corner = new Base();
        $corner->createImageTrueColorTransparent($this->radius_x, $this->radius_y);
        imagecopyresampled($corner->image, $gdCorner, 0, 0, 0, 0, $this->radius_x, $this->radius_y, imagesx($gdCorner), imagesy($gdCorner));
        $corner_x = $this->radius_x - 1;
        $corner_y = $this->radius_y - 1;
        for ($y = 0; $y < $corner_y; $y++) {
            for ($x = 0; $x < $corner_x; $x++) {
                for ($c = 0; $c < 4; $c++) {
                    switch ($c) {
                        case 0:
                            $xo = 0;
                            $yo = 0;
                            $cxo = $x;
                            $cyo = $y;
                            break;
                        case 1:
                            $xo = ($image_x - $corner_x);
                            $yo = 0;
                            $cxo = $corner_x - $x;
                            $cyo = $y;
                            break;
                        case 2:
                            $xo = ($image_x - $corner_x);
                            $yo = ($image_y - $corner_y);
                            $cxo = $corner_x - $x;
                            $cyo = $corner_y - $y;
                            break;
                        case 3:
                            $xo = 0;
                            $yo = ($image_y - $corner_y);
                            $cxo = $x;
                            $cyo = $corner_y - $y;
                            break;
                    }
                    $irgb = imagecolorat($this->_owner->image, $xo + $x, $yo + $y);
                    $r = ($irgb >> 16) & 0xFF;
                    $g = ($irgb >> 8) & 0xFF;
                    $b = $irgb & 0xFF;
                    $crgb = imagecolorat($corner->image, $cxo, $cyo);
                    $a = ($crgb >> 24) & 0xFF;
                    $colour = imagecolorallocatealpha($this->_owner->image, $r, $g, $b, $a);
                    switch ($c) {
                        case 0:
                            imagesetpixel($this->_owner->image, $x, $y, $colour);
                            break;
                        case 1:
                            imagesetpixel($this->_owner->image, $xo + $x, $y, $colour);
                            break;
                        case 2:
                            imagesetpixel($this->_owner->image, $xo + $x, $yo + $y, $colour);
                            break;
                        case 3:
                            imagesetpixel($this->_owner->image, $x, $yo +  $y, $colour);
                            break;
                    }
                }
            }
        }
    }

    private function _cornerpng() {
        return <<<CORNERPNG
iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m
dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAH2SURBVHjaYvz//z/DQAFGRkZNIAXCakCsBMSyQCwJ
xCJAzA/EnAABxEJnB8kDKWsgtgBiMyA2AmJWfHoAAoiFDo4ChYQ7ELsBsTMQK5CiHyCAWGjoMBUgFQDE
fkBsS645AAHEQgOHKQKpCCAOA2IDSs0DCCAGUCahBgYCDiBOB+KjQPyfWhgggKjlODsgXkxVh0ExQABR
6jBGIM4B4ms0cRwQAwQQJY4D5cYJNHMYFAMEELmOA5Vhq2nuOCAGCCByHOcCxPvo4jggBgggUh3nAcTH
6eY4IAYIIFJDjr6OA2KAACIlze2ju+OAGCCAiM2tqwfEcUAMEEDElHMTBsxxQAwQQIQcmDOgjgNigAAi
VH1dG2gHAgQQvop/8YA7DogBAgiXA9MHheOAGCCAmHC05+IYBgkACCAmLGKgxqbVYHEgQAAxYWmmhzEM
IgAQQOghGECVZjoVAUAAMaH1vvwYBhkACCDknBs2WHIuMgYIIOQodmMYhAAggJiQevzOg9GBAAEEC0Fr
Unv89AIAAQRzoAXDIAUAAQRzoNlgdSBAADFAh79+DcYcDMIAAcQEdSDrYA09gABigg4eDloAEEBM0JHN
QQsAAogJOuw6aAFAADFBx4QHLQAIICbogPWgBQABxAQdTR+0ACCAQP3eP0DMPFgdCBBgAJ273bQUqcwV
AAAAAElFTkSuQmCC=
CORNERPNG;
    }

}

