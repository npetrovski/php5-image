<?php

/**
 * image-image
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

namespace Image;

use Image\Plugin\PluginInterface;
use Image\Helper\Color;
use Image\Reader\DefaultReader;
use Image\Fx\FxBase;
use Image\Draw\DrawBase;
use Image\Helper\HelperBase;
use Image\Exception as ImageException;

class Canvas {

    /**
     * Image resource
     * 
     * @var resource
     */
    public $image;
    
    /**
     * Set as false to use the top left corner as the handle
     * 
     * @var bool 
     */
    public $mid_handle = true;
    
    /**
     * Image settings
     * 
     * @var array 
     */
    protected $_settings = array();
    
    /**
     * Plugins stack
     * 
     * @var array 
     */
    protected $_plugins = array();

    /**
     * Constructor
     */
    public function __construct() {

        $args = func_get_args();

        switch (count($args)) {
            case 1:
                if (!empty($args[0]) && is_string($args[0]))
                    $this->openImage($args[0]);
                break;
            case 2:
                $this->createImageTrueColor(intval($args[0]), intval($args[1]));
                break;
        }
    }
        
    /**
     * Plugins access
     * 
     */
    public function __call($name, $arguments) {
        foreach ($this->_plugins as $row) {
            if ($row['plugin'] instanceof PluginInterface && method_exists($row['plugin'], $name)) {
                return call_user_func_array(array($row['plugin'], $name), $arguments);
            }
        }
    }

    /**
     * Attach image plugin
     * 
     * @param \Image\Plugin\PluginInterface $plugin
     * @return string
     */
    public function attach(PluginInterface $plugin) {
        
        $id = spl_object_hash($plugin);
        $plugin->attachToOwner($this);
        
        $this->_plugins[$id] = array('plugin' => $plugin, 'applied' => false);

        return $id;
    }
    
    /**
     * Attach image effect
     * 
     * @return \Image\Canvas
     * @throws ImageException
     */
    public function fx() {
        $args = func_get_args();
        $obj = FxBase::factory(array_shift($args), $args);
        if (!($obj instanceof PluginInterface)) {
            throw new ImageException('Invalid fx class');
        }
        $this->attach($obj);
        
        return $this;  
    }
    
    /**
     * Attach image drawing
     * 
     * @return \Image\Canvas
     * @throws ImageException
     */
    public function draw() {
        $args = func_get_args();
        $obj = DrawBase::factory(array_shift($args), $args);
        if (!($obj instanceof PluginInterface)) {
            throw new ImageException('Invalid draw class');
        }
        $this->attach($obj);
        
        return $this;  
    }
    
    /**
     * Attach image helper
     * 
     * @return \Image\Canvas
     * @throws ImageException
     */
    public function helper() {
        $args = func_get_args();
        $obj = HelperBase::factory(array_shift($args), $args);
        if (!($obj instanceof PluginInterface)) {
            throw new ImageException('Invalid helper class');
        }
        $this->attach($obj);
        
        return $this;  
    }

    /**
     * Apply plugin changes
     * 
     * @return \Image\Canvas
     */
    public function apply() {

        foreach ($this->_plugins as $id => $row) {
            if ($row['plugin'] instanceof PluginInterface && false == $row['applied']) {
                $row['plugin']->generate();
                $this->_plugins[$id]['applied'] = true;
            }
        }
        
        return $this;
    }

    /**
     * 
     * Create an empty image
     * 
     * @param int $width
     * @param int $height
     * @param string $color
     * @return \Image\Canvas
     */
    public function createImage($width = 100, $height = 100, $color = "FFFFFF") {
        $this->image = imagecreate($width, $height);
        if (!empty($color)) {
            $this->imagefill(0, 0, $color);
        }
        
        return $this;
    }

    /**
     * Create an empty image (true color)
     * 
     * @param int $width
     * @param int $height
     * @param string $color
     * @return \Image\Canvas
     */
    public function createImageTrueColor($width = 100, $height = 100, $color = "FFFFFF") {
        $this->image = imagecreatetruecolor($width, $height);
        if (!empty($color)) {
            $this->imagefill(0, 0, $color);
        }
        
        return $this;
    }
    
    /**
     * Create an image (color transparent)
     * 
     * @param int $x
     * @param int $y
     * @return \Image\Canvas
     */
    public function createImageTrueColorTransparent($x = 100, $y = 100) {
        $this->image = imagecreatetruecolor($x, $y);
        $blank = imagecreatefromstring(base64_decode($this->_blankpng()));
        imagesavealpha($this->image, true);
        imagealphablending($this->image, false);
        imagecopyresized($this->image, $blank, 0, 0, 0, 0, $x, $y, imagesx($blank), imagesy($blank));
        imagedestroy($blank);
        
        return $this;
    }

    /**
     * Open image from file
     * 
     * @param string $filename
     * @return \Image\Canvas
     * @throws ImageException
     */
    public function openImage($filename = "") {
        if (file_exists($filename)) {

            $reader = new DefaultReader($filename);

            $this->image = $reader->read($filename);

            if ('resource' != gettype($this->image)) {
                unset($this->image);
            }

            $this->_getFileInfo($filename);
        } else {

            throw new ImageException('Image file does not exist');
        }
        
        return $this;
    }

    /**
     * Outputs an image
     * 
     * @param string $type
     * @param string $filename
     * @return binary
     */
    public function printImage($type, $filename = "") {

        $this->apply();

        $gd_function = 'image' . strtolower($type);
        if (function_exists($gd_function)) {
            if (!empty($filename)) {
                return call_user_func($gd_function, $this->image, $filename);
            } else {
                header("Content-type: " . image_type_to_mime_type(constant('IMAGETYPE_' . strtoupper($type))));
                return call_user_func($gd_function, $this->image);
            }
        }
    }

    /**
     * Outputs a PNG image
     * 
     * @param string $filename
     * @return binary
     */
    public function imagePng($filename = "") {
        return $this->printImage('png', $filename);
    }
    
    /**
     * Outputs a JPG image
     * 
     * @param string $filename
     * @return binary
     */
    public function imageJpeg($filename = "") {
        return $this->printImage('jpeg', $filename);
    }

    /**
     * Outputs a WBMP image
     * 
     * @param string $filename
     * @return binary
     */
    public function imageWbmp($filename = "") {
        return $this->printImage('wbmp', $filename);
    }

    /**
     * Outputs a GIF image
     * 
     * @param string $filename
     * @return binary
     */
    public function imageGif($filename = "") {
        return $this->printImage('gif', $filename);
    }

    /**
     * Destroy image
     * 
     * @return \Image\Canvas
     */
    public function destroyImage() {
        imagedestroy($this->image);
        unset($this->image);
        
        return $this;
    }

    /**
     * Get image width
     * 
     * @return int
     */
    public function imagesx() {
        return imagesx($this->image);
    }

    /**
     * Get image height
     * 
     * @return int
     */
    public function imagesy() {
        return imagesy($this->image);
    }

    /**
     * Finds whether an image is a truecolor image
     * 
     * @return bool
     */
    public function imageIsTrueColor() {
        return imageistruecolor($this->image);
    }

    /**
     * Get the index of the color of a pixel
     * 
     * @param int $x
     * @param int $y
     * @return int
     */
    public function imageColorAt($x = 0, $y = 0) {
        $color = imagecolorat($this->image, $x, $y);
        if (!$this->imageIsTrueColor()) {
            $arrColor = imagecolorsforindex($this->image, $color);
            return Color::arrayColorToIntColor($arrColor);
        } else {
            return $color;
        }
    }

    /**
     * Flood fill
     * 
     * @param int $x
     * @param int $y
     * @param string $color
     * @param int $alpha
     * @return \Image\Canvas
     */
    public function imagefill($x = 0, $y = 0, $color = "FFFFFF", $alpha = null) {
        imagefill($this->image, $x, $y, $this->imagecolorallocate($color, $alpha));
        
        return $this;
    }

    /**
     * Allocate a color for an image
     * 
     * @param string $color
     * @param int $alpha
     * @return int
     */
    public function imagecolorallocate($color = "FFFFFF", $alpha = null) {

        if (is_string($color)) {
            $arrColor = Color::hexColorToArrayColor($color);
        } else if (is_int($color)) {
            $arrColor = Color::intColorToArrayColor($color);
            $alpha = $arrColor['alpha'];
        }

        if ($alpha) {
            return imagecolorallocate($this->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
        } else {
            return imagecolorallocatealpha($this->image, $arrColor['red'], $arrColor['green'], $arrColor['blue'], intval($alpha));
        }
    }

    /**
     * Displace image
     * 
     * @param array $map
     * @return \Image\Canvas
     */
    public function displace($map) {
        $width = $this->imagesx();
        $height = $this->imagesy();
        $temp = new self($width, $height);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = $this->imageColorAt($map['x'][$x][$y], $map['y'][$x][$y]);
                imagesetpixel($temp->image, $x, $y, $this->imagecolorallocate($rgb));
            }
        }
        $this->image = $temp->image;
        
        return $this;
    }

    /**
     * Test image type
     * 
     * @return bool
     */
    public function testImageHandle() {
        return (bool) (isset($this->image) && 'gd' == get_resource_type($this->image));
    }

    /**
     * Gets an image width middle position
     * 
     * @return int
     */
    public function getHandleX() {
        return ($this->mid_handle == true) ? floor($this->imagesx() / 2) : 0;
    }
    
    /**
     * Gets an image height middle position
     * 
     * @return int
     */
    public function getHandleY() {
        return ($this->mid_handle == true) ? floor($this->imagesy() / 2) : 0;
    }

    /**
     * Gets image meta data
     * 
     * @param string $key
     * @return mixed
     */
    public function getSettings($key) {
        return isset($this->_settings[$key]) ? $this->_settings[$key] : null;
    }

    /**
     * Gets file information
     * 
     * @param string $filename
     * @param int $round
     */
    private function _getFileInfo($filename, $round = 2) {
        $ext = array('B', 'KB', 'MB', 'GB');

        $this->_settings['filepath'] = $filename;
        $this->_settings['filename'] = basename($filename);
        $this->_settings['filesize_bytes'] = filesize($filename);
        $size = $this->_settings['filesize_bytes'];
        for ($i = 0; $size > 1024 && $i < count($ext) - 1; $i++) {
            $size /= 1024;
        }
        $this->_settings['filesize_formatted'] = round($size, $round) . $ext[$i];
        $this->_settings['original_width'] = $this->imagesx();
        $this->_settings['original_height'] = $this->imagesy();
    }

    /**
     * Blank PNG
     * 
     * @return string
     */
    private function _blankpng() {
        return <<<BLACKPNG
iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m
dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBNCg
dyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAAN
egcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQ
oHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAA
DXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII=
BLACKPNG;
    }

}
