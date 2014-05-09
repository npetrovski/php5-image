<?php

/**
 * image-fx-ripple
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

use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Ripple extends FxBase implements PluginInterface {

    public function __construct($frequency = 3, $amplitude = 4, $wrap_around = true) {
        $this->frequency = $frequency;
        $this->amplitude = $amplitude;
        $this->wrap_around = $wrap_around;
    }

    public function generate() {
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        $map = array();
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $dis_x = $x + (sin(deg2rad(($y / $height) * 360) * $this->frequency) * $this->amplitude);
                $dis_y = $y + (sin(deg2rad(($x / $width) * 360) * $this->frequency) * $this->amplitude);
                if ($this->wrap_around == true) {
                    $dis_x = ($dis_x < 0) ? $dis_x + $width : $dis_x;
                    $dis_x = ($dis_x >= $width) ? $dis_x - $width : $dis_x;
                    $dis_y = ($dis_y < 0) ? $dis_y + $height : $dis_y;
                    $dis_y = ($dis_y >= $height) ? $dis_y - $height : $dis_y;
                } else {
                    $dis_x = ($dis_x < 0) ? 0 : $dis_x;
                    $dis_x = ($dis_x > $width) ? $width : $dis_x;
                    $dis_y = ($dis_y < 0) ? 0 : $dis_y;
                    $dis_y = ($dis_y > $height) ? $height : $dis_y;
                }
                $map['x'][$x][$y] = $dis_x;
                $map['y'][$x][$y] = $dis_y;
            }
        }
        
        $this->_owner->displace($map);
        return true;
    }

}
