<?php

namespace Image\Draw;

use Image\Plugin\PluginInterface;

class Captcha extends DrawBase implements PluginInterface
{
    private $_arr_ttf_font = array();

    private $_text_color = '000000';

    private $_text = '';

    private $_text_size = 15;

    private $_text_size_random = 0;

    private $_text_angle_random = 0;

    private $_text_spacing = 5;

    public function __construct($text = '',
                                $text_size = 15,
                                $text_size_random = 0,
                                $text_angle_random = 0,
                                $text_spacing = 5)
    {
        $this->_text = $text;
        $this->_text_size = $text_size;
        $this->_text_size_random = $text_size_random;
        $this->_text_angle_random = $text_angle_random;
        $this->_text_spacing = $text_spacing;
    }

    public function addTTFFont($font = '')
    {
        if (file_exists($font)) {
            $this->_arr_ttf_font[] = $font;
        }
        
        return $this;
    }

    public function setTextSize($size)
    {
        $this->_text_size = $size;

        return $this;
    }

    public function setTextSpacing($spacing)
    {
        $this->_text_spacing = $spacing;

        return $this;
    }

    public function setSizeRandom($size_random)
    {
        $this->_text_size_random = $size_random;

        return $this;
    }

    public function setAngleRandom($angle_random)
    {
        $this->_text_angle_random = $angle_random;

        return $this;
    }

    public function setTextColor($color = '000000')
    {
        $this->_text_color = $color;

        return $this;
    }

    public function generate()
    {
        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, true);
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        $white = $this->_owner->imagecolorallocate($this->_text_color);

        $data = array();
        $total_width = 0;
        for ($x = 0; $x < strlen($this->_text); $x++) {
            $data[$x]['text'] = $this->_text[$x];
            $data[$x]['font'] = $this->_arr_ttf_font[rand(0, count($this->_arr_ttf_font) - 1)];
            $data[$x]['size'] = rand($this->_text_size, $this->_text_size + $this->_text_size_random);
            $data[$x]['angle'] = ($this->_text_angle_random / 2) - rand(0, $this->_text_angle_random);

            $captcha_dimensions = imagettfbbox($data[$x]['size'], $data[$x]['angle'], $data[$x]['font'], $data[$x]['text']);
            $data[$x]['width'] = abs($captcha_dimensions[2]) + $this->_text_spacing;
            $data[$x]['height'] = abs($captcha_dimensions[5]);
            $total_width += $data[$x]['width'];
        }
        $x_offset = ($width - $total_width) / 2;
        $x_pos = 0;
        $y_pos = 0;
        foreach ($data as $ld) {
            $y_pos = ($height + $ld['height']) / 2;
            
            imagettftext($this->_owner->image, $ld['size'], $ld['angle'], $x_offset +
                    $x_pos, $y_pos, $white, $ld['font'], $ld['text']);
            
            $x_pos += $ld['width'];
        }
        
        return true;
    }
}
