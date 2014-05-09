<?php

/**
 * image-draw-captcha
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
namespace Image\Draw;

use Image\Draw\DrawBase;
use Image\Plugin\PluginInterface;

class Captcha extends DrawBase implements PluginInterface {

    private $arr_ttf_font = array();
    
    private $text_color = "000000";
    
    private $text = "";
    
    private $text_size = 15;
    
    private $text_size_random = 0;
    
    private $text_angle_random = 0;
    
    private $text_spacing = 5;

    public function __construct($text = "", 
                                $text_size = 15, 
                                $text_size_random = 0, 
                                $text_angle_random = 0, 
                                $text_spacing = 5) {
        $this->text = $text;
        $this->text_size = $text_size;
        $this->text_size_random = $text_size_random;
        $this->text_angle_random = $text_angle_random;
        $this->text_spacing = $text_spacing;
    }

    public function addTTFFont($font = "") {
        if (file_exists($font)) {
            $this->arr_ttf_font[] = $font;
            return true;
        } else {
            return false;
        }
    }

    public function setTextSize($size) {
        $this->text_size = $size;
        return $this;
    }

    public function setTextSpacing($spacing) {
        $this->text_spacing = $spacing;
        return $this;
    }

    public function setSizeRandom($size_random) {
        $this->text_size_random = $size_random;
        return $this;
    }

    public function setAngleRandom($angle_random) {
        $this->text_angle_random = $angle_random;
        return $this;
    }
    
    public function setTextColor($color = "000000") {
        $this->text_color = $color;
        return $this;
    }

    public function generate() {
        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, true);
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        
        $white = $this->_owner->imagecolorallocate($this->text_color);
        //$white = imagecolorallocate($this->_owner->image, 0, 0, 0);
        $l = array();
        $total_width = 0;
        for ($x = 0; $x < strlen($this->text); $x++) {
            $l[$x]['text'] = $this->text[$x];
            $l[$x]['font'] = $this->arr_ttf_font[rand(0, count($this->arr_ttf_font) - 1)];
            $l[$x]['size'] = rand($this->text_size, $this->text_size + $this->text_size_random);
            $l[$x]['angle'] = ($this->text_angle_random / 2) - rand(0, $this->text_angle_random);
            
            $captcha_dimensions = imagettfbbox($l[$x]['size'], $l[$x]['angle'], $l[$x]['font'], $l[$x]['text']);
            $l[$x]['width'] = abs($captcha_dimensions[2]) + $this->text_spacing;
            $l[$x]['height'] = abs($captcha_dimensions[5]);
            $total_width += $l[$x]['width'];
        }
        $x_offset = ($width - $total_width) / 2;
        $x_pos = 0;
        $y_pos = 0;
        foreach ($l as $p => $ld) {
            $y_pos = ($height + $ld['height']) / 2;
            imagettftext($this->_owner->image, $ld['size'], $ld['angle'], $x_offset +
                    $x_pos, $y_pos, $white, $ld['font'], $ld['text']);
            $x_pos += $ld['width'];
        }
    }

}
