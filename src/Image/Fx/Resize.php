<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Plugin\PluginInterface;

class Resize extends FxBase implements PluginInterface
{
    private $_resize_x;
    private $_resize_y;

    public function __construct($resize_x = 0, $resize_y = 0)
    {
        $this->setResize($resize_x, $resize_y);
    }

    public function setResize($resize_x = 0, $resize_y = 0)
    {
        $this->_resize_x = $resize_x;
        $this->_resize_y = $resize_y;

        return $this;
    }

    public function generate()
    {
        $src_x = $this->_owner->getImageWidth();
        $src_y = $this->_owner->getImageHeight();

        $old_x = $this->_owner->getImageWidth();
        $old_y = $this->_owner->getImageHeight();
        //Resize the image to be a set size proportionate to the aspect ratio
        //Default to the old size
        $canvas_x = $old_x;
        $canvas_y = $old_y;

        if ($this->_resize_x > 0 and $this->_resize_y > 0) {
            $canvas_x = $this->_resize_x;
            $canvas_y = $this->_resize_y;
        } elseif ($this->_resize_x > 0) {
            if ($this->_resize_x < $old_x) {
                $canvas_x = $this->_resize_x;
                $canvas_y = floor(($this->_resize_x / $old_x) * $old_y);
            }
        } elseif ($this->_resize_y > 0) {
            if ($this->_resize_y < $old_y) {
                $canvas_x = floor(($this->_resize_y / $old_y) * $old_x);
                $canvas_y = $this->_resize_y;
            }
        }

        $dst_x = $canvas_x;
        $dst_y = $canvas_y;
        $dst = new Canvas();
        $dst->createImageTrueColorTransparent($dst_x, $dst_y);

        imagecopyresampled($dst->image, $this->_owner->image, 0, 0, 0, 0, $dst_x, $dst_y, $src_x, $src_y);
        $this->_owner->image = $dst->image;
        unset($dst);

        return true;
    }
}
