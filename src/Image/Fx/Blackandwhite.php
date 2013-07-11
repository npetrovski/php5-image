<?php

/**
 * image-fx-blackandwhite
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
require_once 'Image/Plugin/Base.php';

require_once 'Image/Plugin/Interface.php';

class Image_Fx_Blackandwhite extends Image_Fx_Abstract implements Image_Plugin_Interface {

    public function __construct($algorithm = "YIQ") {
        $this->algorithm = $algorithm;
    }

    public function setAlgorithm($algorithm = "YIQ") {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function generate() {
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        for ($x = 0; $x < 256; $x++) {
            $palette[$x] = imagecolorallocate($this->_owner->image, $x, $x, $x);
        }
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = $this->_owner->imageColorAt($x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                switch ($this->algorithm) {
                    case "red":
                        $val = $r;
                        break;
                    case "green":
                        $val = $g;
                        break;
                    case "blue":
                        $val = $b;
                        break;
                    case "max":
                        $val = max($r, $g, $b);
                        break;
                    case "avg":
                        $val = ($r +
                                $g +
                                $b) /
                                3;
                        break;
                    default:
                        $val = (($r *
                                0.299) +
                                ($g *
                                0.587) +
                                ($b *
                                0.114));
                        break;
                }
                imagesetpixel($this->_owner->image, $x, $y, $palette[$val]);
            }
        }
        return true;
    }

}
