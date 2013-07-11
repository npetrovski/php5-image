<?php

/**
 * image-fx-flip
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

class Image_Fx_Flip extends Image_Fx_Abstract implements Image_Plugin_Interface {

    public function __construct($flip_x = true, $flip_y = false) {
        $this->flip_x = $flip_x;
        $this->flip_y = $flip_y;
    }

    public function setFlip($flip_x = true, $flip_y = false) {
        $this->flip_x = $flip_x;
        $this->flip_y = $flip_y;
        return $this;
    }

    public function generate() {
        $src_x = $this->_owner->imagesx();
        $src_y = $this->_owner->imagesy();
        $flip_x = $this->flip_x;
        $flip_y = $this->flip_y;
        $flip = new Image_Image($src_x, $src_y);
        if ($flip_x == true) {
            imagecopy($flip->image, $this->_owner->image, 0, 0, 0, 0, $src_x, $src_y);
            for ($x = 0; $x < $src_x; $x++) {
                imagecopy($this->_owner->image, $flip->image, $src_x - $x - 1, 0, $x, 0, 1, $src_y);
            }
        }
        
        if ($flip_y == true) {
            imagecopy($flip->image, $this->_owner->image, 0, 0, 0, 0, $src_x, $src_y);
            for ($y = 0; $y < $src_y; $y++) {
                imagecopy($this->_owner->image, $flip->image, 0, $src_y - $y - 1, 0, $y, $src_x, 1);
            }
        }
        
        $this->_owner->image = $flip->image;
        unset($flip);
        return true;
    }

}
