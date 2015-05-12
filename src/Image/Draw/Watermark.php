<?php

namespace Image\Draw;

use Image\Canvas;
use Image\Plugin\PluginInterface;

class Watermark extends DrawBase implements PluginInterface
{
    private $_position = 'br';
    private $_watermark;
    private $_position_x;
    private $_position_y;

    public function __construct($watermark = null, $position = 'br')
    {
        $this->setWatermark($watermark);
        $this->setPosition($position);
    }

    public function setWatermark($watermark = null)
    {
        if ($watermark instanceof Canvas) {
            $this->_watermark = $watermark;
        } elseif (file_exists($watermark)) {
            $this->_watermark = new Canvas($watermark);
        }

        return $this;
    }

    public function setPosition()
    {
        $args = func_get_args();
        switch (count($args)) {
            case 1:
                $this->_position = $args[0];
                break;

            case 2:
                $this->_position = 'user';
                $this->_position_x = $args[0];
                $this->_position_y = $args[1];
                break;
        }

        return $this;
    }

    public function generate()
    {
        if (is_null($this->_watermark)) {
            return false;
        }

        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, true);
        imagesavealpha($this->_watermark->image, false);
        imagealphablending($this->_watermark->image, false);
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();
        $watermark_width = $this->_watermark->getImageWidth();
        $watermark_height = $this->_watermark->getImageHeight();
        switch ($this->_position) {
            case 'tl':
                $x = 0;
                $y = 0;
                break;
            case 'tm':
                $x = ($width - $watermark_width) / 2;
                $y = 0;
                break;
            case 'tr':
                $x = $width - $watermark_width;
                $y = 0;
                break;
            case 'ml':
                $x = 0;
                $y = ($height - $watermark_height) / 2;
                break;
            case 'mm':
                $x = ($width - $watermark_width) / 2;
                $y = ($height - $watermark_height) / 2;
                break;
            case 'mr':
                $x = $width - $watermark_width;
                $y = ($height - $watermark_height) / 2;
                break;
            case 'bl':
                $x = 0;
                $y = $height - $watermark_height;
                break;
            case 'bm':
                $x = ($width - $watermark_width) / 2;
                $y = $height - $watermark_height;
                break;
            case 'br':
                $x = $width - $watermark_width;
                $y = $height - $watermark_height;
                break;
            case 'user':
                $x = $this->_position_x - ($this->_watermark->getHandleX() / 2);
                $y = $this->_position_y - ($this->_watermark->getHandleY() / 2);
                break;
            default:
                $x = 0;
                $y = 0;
                break;
        }
        if ($this->_position != 'tile') {
            imagecopy($this->_owner->image, $this->_watermark->image, $x, $y, 0, 0, $watermark_width, $watermark_height);
        } else {
            imagesettile($this->_owner->image, $this->_watermark->image);
            imagefilledrectangle($this->_owner->image, 0, 0, $width, $height, IMG_COLOR_TILED);
        }

        return true;
    }
}
