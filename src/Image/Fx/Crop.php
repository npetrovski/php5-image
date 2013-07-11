<?php

/**
 * image-fx-crop
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

class Image_Fx_Crop extends Image_Fx_Abstract implements Image_Plugin_Interface {

    public function __construct($crop_x = 0, $crop_y = 0) {
        $this->crop_x = $crop_x;
        $this->crop_y = $crop_y;
    }

    public function setCrop($crop_x = 0, $crop_y = 0) {
        $this->crop_x = $crop_x;
        $this->crop_y = $crop_y;
        return $this;
    }

    public function calculate() {

        $old_x = $this->_owner->imagesx();
        $old_y = $this->_owner->imagesy();

        $this->canvas_x = $old_x;
        $this->canvas_y = $old_y;

        //Calculate the cropping area
        if ($this->crop_x > 0) {
            if ($this->canvas_x > $this->crop_x) {
                $this->canvas_x = $this->crop_x;
            }
        }

        if ($this->crop_y > 0) {
            if ($this->canvas_y > $this->crop_y) {
                $this->canvas_y = $this->crop_y;
            }
        }

        return true;
    }

    public function generate() {
        $this->calculate();

        $crop = new Image_Image();
        $crop->createImageTrueColorTransparent($this->canvas_x, $this->canvas_y);

        $src_x = $this->_owner->getHandleX() - floor($this->canvas_x / 2);
        $src_y = $this->_owner->getHandleY() - floor($this->canvas_y / 2);

        imagecopy($crop->image, $this->_owner->image, 0, 0, $src_x, $src_y, $this->canvas_x, $this->canvas_y);

        $this->_owner->image = $crop->image;

        unset($crop);

        return true;
    }

}
