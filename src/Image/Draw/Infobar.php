<?php

/**
 * image-draw-infobar
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

class Image_Draw_Infobar extends Image_Draw_Abstract implements Image_Plugin_Interface {

    public function __construct($info = "[Filename]", $position = "b", $justify = "c", $barcolor = "000000", $textcolor = "FFFFFF") {
        $this->info = $info;
        $this->position = $position;
        $this->justify = $justify;
        $this->barcolor = $barcolor;
        $this->textcolor = $textcolor;
        $this->font = 2;
    }

    public function generate() {
        $src_x = $this->_owner->imagesx();
        $src_y = $this->_owner->imagesy();
        $temp = new Image_Image();
        $temp->createImageTrueColorTransparent($src_x, $src_y + 20);
        $text = str_replace("[Filename]", $this->_owner->getSettings('filename'), $this->info);
        switch ($this->position) {
            case "t":
                $x = 0;
                $y = 20;
                $bar_y = 0;
                $text_y = 3;
                break;
            case "b":
                $x = 0;
                $y = 0;
                $bar_y = $src_y + 20;
                $text_y = $bar_y - 20 + 3;
                break;
            default:
                return false;
                break;
        }
        switch ($this->justify) {
            case "l":
                $text_x = 3;
                break;
            case "c":
                $text_x = ($src_x - (imagefontwidth($this->font) *
                        strlen($text))) / 2;
                break;
            case "r":
                $text_x = $src_x - 3 - (imagefontwidth($this->font) *
                        strlen($text));
                break;
        }
        //Draw the bar background
        $arrColor = $this->_owner->hexColorToArrayColor($this->barcolor);
        $bar_color = imagecolorallocate($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
        imagefilledrectangle($temp->image, 0, $bar_y, $src_x, 20, $bar_color);
        //Copy the image
        imagecopy($temp->image, $this->_owner->image, $x, $y, 0, 0, $src_x, $src_y);
        //Draw the text (to be replaced with image_draw_text one day
        $arrColor = $this->_owner->hexColorToArrayColor($this->textcolor);
        $text_color = imagecolorallocate($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
        imagestring($temp->image, $this->font, $text_x, $text_y, $text, $text_color);
        $this->_owner->image = $temp->image;
        unset($temp);
        return true;
    }

}
