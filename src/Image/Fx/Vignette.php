<?php

/**
 * image-fx-vignette
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

use Image\Canvas;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Vignette extends FxBase implements PluginInterface {

    public function __construct(Canvas $vignette = NULL) {
        $this->vignette = $vignette;
    }

    public function generate() {

        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, false);

        $vignette_x = $this->vignette->imagesx();
        $vignette_y = $this->vignette->imagesy();
        for ($y = 0; $y < $vignette_y; $y++) {
            for ($x = 0; $x < $vignette_x; $x++) {
                $irgb = imagecolorat($this->_owner->image, $x, $y);
                $r = ($irgb >> 16) & 0xFF;
                $g = ($irgb >> 8) & 0xFF;
                $b = $irgb & 0xFF;
                $vrgb = imagecolorat($this->vignette->image, $x, $y);
                $a = ($vrgb >> 24) & 0xFF;
                $colour = imagecolorallocatealpha($this->_owner->image, $r, $g, $b, $a);
                imagesetpixel($this->_owner->image, $x, $y, $colour);
            }
        }
    }

}

