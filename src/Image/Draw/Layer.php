<?php

/**
 * image-draw-watermark
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

use Image\Base;
use Image\Draw\DrawBase;
use Image\Plugin\PluginInterface;


class Layer extends DrawBase implements PluginInterface {

    protected $position = "br";
    protected $preserveLayerSize = true;
    
    public function __construct(Base $img = null, $preserveLayerSize = true, $position = "br") {
        $this->img = $img;
        $this->preserveLayerSize = $preserveLayerSize;
        $this->position = $position;
    }
    
    public function setPosition() {
        $args = func_get_args();
        switch (count($args)) {
            case 1:
                $this->position = $args[0];
                break;

            case 2:
                $this->position = "user";
                $this->position_x = $args[0];
                $this->position_y = $args[1];
                break;
        }
    }
    
    public function generate() {
        
        //getting the width and height of the body part image, (should be the same size as the canvas)
        $layer_w = $this->img->imagesx();
        $layer_h = $this->img->imagesy();

        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();

        //making sure that alpha blending is enabled
        imagealphablending($this->_owner->image, true);

        //making sure to preserve the alpha info
        imagesavealpha($this->_owner->image, true);

        switch ($this->position) {
            case "tl":
                $x = 0;
                $y = 0;
                break;
            case "tm":
                $x = ($width - $layer_w) / 2;
                $y = 0;
                break;
            case "tr":
                $x = $width - $layer_w;
                $y = 0;
                break;
            case "ml":
                $x = 0;
                $y = ($height - $layer_h) / 2;
                break;
            case "mm":
                $x = ($width - $layer_w) / 2;
                $y = ($height - $layer_h) / 2;
                break;
            case "mr":
                $x = $width - $layer_w;
                $y = ($height - $layer_h) / 2;
                break;
            case "bl":
                $x = 0;
                $y = $height - $layer_h;
                break;
            case "bm":
                $x = ($width - $layer_w) / 2;
                $y = $height - $layer_h;
                break;
            case "br":
                $x = $width - $layer_w;
                $y = $height - $layer_h;
                break;
            case "user":
                $x = $this->position_x;
                $y = $this->position_y;
                break;
            default:
                $x = 0;
                $y = 0;
                break;
        }
        
        //finally, putting that image on top of our canvas
        if ($this->preserveLayerSize) {
            imagecopyresampled($this->_owner->image, $this->img->image, $x, $y, 0, 0, $layer_w, $layer_h, $layer_w, $layer_h);
        } else {
            imagecopyresampled($this->_owner->image, $this->img->image, $x, $y, 0, 0, $width, $height, $layer_w, $layer_h);
        }

        //$this->_owner->image
        return true;
    }

}
