<?php

namespace Image\Draw;

use Image\Canvas;
use Image\Plugin\PluginInterface;
use Image\Helper\Color;

class Infobar extends DrawBase implements PluginInterface
{
    private $_info;
    private $_position;
    private $_justify;
    private $_barcolor;
    private $_textcolor;
    private $_font = 2;

    public function __construct($info = '[Filename]', $position = 'b', $justify = 'c', $barcolor = '000000', $textcolor = 'FFFFFF')
    {
        $this->setInfo($info);
        $this->setTextcolor($textcolor);
        $this->setBarcolor($barcolor);
        $this->setJustify($justify);
        $this->setPosition($position);
    }

    public function setInfo($info = '[Filename]')
    {
        $this->_info = $info;

        return $this;
    }

    public function setTextcolor($textcolor = 'FFFFFF')
    {
        $this->_textcolor = $textcolor;

        return $this;
    }

    public function setBarcolor($barcolor = '000000')
    {
        $this->_barcolor = $barcolor;

        return $this;
    }

    public function setJustify($justify = 'c')
    {
        $this->_justify = $justify;

        return $this;
    }

    public function setPosition($position = 'b')
    {
        $this->_position = $position;

        return $this;
    }

    public function generate()
    {
        $src_x = $this->_owner->getImageWidth();
        $src_y = $this->_owner->getImageHeight();
        $temp = new Canvas();
        $temp->createImageTrueColorTransparent($src_x, $src_y + 20);
        $text = str_replace('[Filename]', $this->_owner->getProperty('filename'), $this->_info);
        switch ($this->_position) {
            case 't':
                $x = 0;
                $y = 20;
                $bar_y = 0;
                $text_y = 3;
                break;
            case 'b':
                $x = 0;
                $y = 0;
                $bar_y = $src_y + 20;
                $text_y = $bar_y - 20 + 3;
                break;
            default:
                return false;
                break;
        }
        switch ($this->_justify) {
            case 'l':
                $text_x = 3;
                break;
            case 'c':
                $text_x = ($src_x - (imagefontwidth($this->_font) *
                        strlen($text))) / 2;
                break;
            case 'r':
                $text_x = $src_x - 3 - (imagefontwidth($this->_font) *
                        strlen($text));
                break;
        }
        //Draw the bar background
        $color = Color::hexColorToArrayColor($this->_barcolor);
        $bar_color = imagecolorallocate($temp->image, $color['red'], $color['green'], $color['blue']);
        imagefilledrectangle($temp->image, 0, $bar_y, $src_x, 20, $bar_color);
        //Copy the image
        imagecopy($temp->image, $this->_owner->image, $x, $y, 0, 0, $src_x, $src_y);

        //Draw the text (to be replaced with image_draw_text one day
        $color = Color::hexColorToArrayColor($this->_textcolor);
        $text_color = imagecolorallocate($temp->image, $color['red'], $color['green'], $color['blue']);
        imagestring($temp->image, $this->_font, $text_x, $text_y, $text, $text_color);
        $this->_owner->image = $temp->image;
        unset($temp);

        return true;
    }
}
