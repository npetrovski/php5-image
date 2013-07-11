<?php

/**
 * image-draw-primitive
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

class Image_Draw_Primitive extends Image_Draw_Abstract implements Image_Plugin_Interface {

    const LINE = 'LINE';
    
    const RECTANGLE = 'RECTANGLE';
    
    const FILLED_RECTANGLE = 'FILLED_RECTANGLE';
    
    const ELLIPSE = 'ELLIPSE';
    
    const FILLED_ELLIPSE = 'FILLED_ELLIPSE';
    
    const SPIRAL = 'SPIRAL';
    
    private $__shapes = array();
    
    private $__base_color = "000000";
    
    private $__base_alpha = 0;
    
    public function __construct($base_color = "000000", $base_alpha = 0) {
        $this->__base_color = $base_color;
        $this->__base_alpha = $base_alpha;
    }

    public function addLine($x1, $y1, $x2, $y2, $color = null) {
        $this->__shapes[] = array(self::LINE, $color, $x1, $y1, $x2, $y2);
    }

    public function addRectangle($x1, $y1, $x2, $y2, $color = null, $filled = false) {
        if (!$filled) {
            $this->__shapes[] = array(self::RECTANGLE, $color, $x1, $y1, $x2, $y2);
        } else {
            $this->__shapes[] = array(self::FILLED_RECTANGLE, $color, $x1, $y1, $x2, $y2);
        }
    }

    public function addFilledRectangle($x1, $y1, $x2, $y2, $color = null) {
        $this->__shapes[] = array(self::FILLED_RECTANGLE, $color, $x1, $y1, $x2, $y2);
    }

    public function addEllipse($x1, $y1, $x2, $y2, $color = null, $filled = false) {
        $w = $x2 - $x1;
        $h = $y2 - $y1;
        if (!$filled) {
            $this->__shapes[] = array(self::ELLIPSE, $color, $x1, $y1, $w, $h);
        } else {
            $this->__shapes[] = array(self::FILLED_ELLIPSE, $color, $x1, $y1, $w, $h);
        }
    }

    public function addFilledEllipse($x1, $y1, $x2, $y2, $color = null) {
        $this->__shapes[] = array(self::FILLED_ELLIPSE, $color, $x1, $y1, $x2 - $x1, $y2 - $y1);
    }

    public function addCircle($x, $y, $radius, $color = null) {
        $this->__shapes[] = array(self::ELLIPSE, $color, $x, $y, $radius, $radius);
    }

    /**
     * Draw a spiral
     * 
     * @param int $x
     * @param int $y
     * @param int $radius
     * @param int $angle
     * @param type $color
     */
    public function addSpiral($x, $y, $radius, $angle, $color = null) {
        $this->__shapes[] = array(self::SPIRAL, $color, $x, $y, $radius, $angle); 
    }
    
    public function generate() {
        
        foreach ($this->__shapes as $shape) {
            $type = array_shift($shape);
            $color = array_shift($shape);
            
            $color = $this->_owner->imagecolorallocate((!isset($color) || is_null($color)) ? 
                        $this->__base_color : 
                        $color, $this->__base_alpha);
            
            switch ($type) {
                case self::LINE:
                    imageline($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::RECTANGLE:
                    imagerectangle($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::FILLED_RECTANGLE:
                    imagefilledrectangle($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::ELLIPSE:
                    imageellipse($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::FILLED_ELLIPSE:
                    imagefilledellipse($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::SPIRAL:
                    $angle = $r = 0;
                    while($r <= $shape[2] ) {
                        imagearc($this->_owner->image, $shape[0], $shape[1], $r, $r, $angle - $shape[3], $angle, $color);
                        $angle += $shape[3];
                        $r++;
                    }
                    break;
            }
        }
    }

}
