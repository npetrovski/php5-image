<?php

namespace Image\Draw;

use Image\Draw\DrawBase;
use Image\Plugin\PluginInterface;

class Captcha extends DrawBase implements PluginInterface {

    private $_arr_ttf_font = array();
    
    private $_text_color = "000000";
    
    private $_text = "";
    
    private $_text_size = 15;
    
    private $_text_size_random = 0;
    
    private $_text_angle_random = 0;
    
    private $_text_spacing = 5;

    public function __construct($text = "", 
                                $text_size = 15, 
                                $text_size_random = 0, 
                                $text_angle_random = 0, 
                                $text_spacing = 5) {
        $this->_text = $text;
        $this->_text_size = $text_size;
        $this->_text_size_random = $text_size_random;
        $this->_text_angle_random = $text_angle_random;
        $this->_text_spacing = $text_spacing;
    }

    public function addTTFFont($font = "") {
        if (file_exists($font)) {
            $this->_arr_ttf_font[] = $font;
            return true;
        } else {
            return false;
        }
    }

    public function setTextSize($size) {
        $this->_text_size = $size;
        return $this;
    }

    public function setTextSpacing($spacing) {
        $this->_text_spacing = $spacing;
        return $this;
    }

    public function setSizeRandom($size_random) {
        $this->_text_size_random = $size_random;
        return $this;
    }

    public function setAngleRandom($angle_random) {
        $this->_text_angle_random = $angle_random;
        return $this;
    }
    
    public function setTextColor($color = "000000") {
        $this->_text_color = $color;
        return $this;
    }

    public function generate() {
        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, true);
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        
        $white = $this->_owner->imagecolorallocate($this->_text_color);
        //$white = imagecolorallocate($this->_owner->image, 0, 0, 0);
        $l = array();
        $total_width = 0;
        for ($x = 0; $x < strlen($this->_text); $x++) {
            $l[$x]['text'] = $this->_text[$x];
            $l[$x]['font'] = $this->_arr_ttf_font[rand(0, count($this->_arr_ttf_font) - 1)];
            $l[$x]['size'] = rand($this->_text_size, $this->_text_size + $this->_text_size_random);
            $l[$x]['angle'] = ($this->_text_angle_random / 2) - rand(0, $this->_text_angle_random);
            
            $captcha_dimensions = imagettfbbox($l[$x]['size'], $l[$x]['angle'], $l[$x]['font'], $l[$x]['text']);
            $l[$x]['width'] = abs($captcha_dimensions[2]) + $this->_text_spacing;
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
