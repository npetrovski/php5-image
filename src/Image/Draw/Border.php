<?php

/**
 * image-draw-border
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

class Image_Draw_Border extends Image_Draw_Abstract implements Image_Plugin_Interface {

    public function __construct($padding = 10, $color = "000000") {
        $this->padding = $padding;
        $this->color = $color;
    }

    public function setBorder($padding = 10, $color = "000000") {
        $this->padding = $padding;
        $this->color = $color;
        return $this;
    }

    public function setPadding($padding = 10) {
        $this->padding = $padding;
        return $this;
    }

    public function setColor($color = "000000") {
        $this->color = $color;
        return $this;
    }

    public function generate() {
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        $padding = $this->padding;
        $arrColor = Image_Image::hexColorToArrayColor($this->color);
        $temp = new Image_Image();
        $temp->createImageTrueColor($width + ($padding * 2), $height + ($padding * 2));
        $tempcolor = imagecolorallocate($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
        imagefill($temp->image, 0, 0, $tempcolor);
        imagecopy($temp->image, $this->_owner->image, $padding, $padding, 0, 0, $width, $height);
        $this->_owner->image = $temp->image;
        unset($temp);
        return true;
    }

}
