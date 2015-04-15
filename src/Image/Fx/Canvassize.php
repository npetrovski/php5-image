<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;
use Image\Helper\Color;

class Canvassize extends FxBase implements PluginInterface {

    public function __construct($t = 10, $r = 10, $b = 10, $l = 10, $color = "") {
        $this->t = $t;
        $this->r = $r;
        $this->b = $b;
        $this->l = $l;
        $this->color = $color;
    }

    public function generate() {
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();

        $temp = new Canvas();
        if (!empty($this->color)) {
            $temp->createImageTrueColor($width + ($this->r + $this->l), $height +
                    ($this->t + $this->b));
            $arrColor = Color::hexColorToArrayColor($this->color);
            $tempcolor = imagecolorallocate($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
            imagefilledrectangle($temp->image, 0, 0, $temp->imagesx(), $temp->imagesy(), $tempcolor);
        } else {
            $temp->createImageTrueColorTransparent($width + ($this->r + $this->l), $height +
                    ($this->t + $this->b));
        }

        imagecopy($temp->image, $this->_owner->image, $this->l, $this->t, 0, 0, $width, $height);
        $this->_owner->image = $temp->image;
        unset($temp);

        return true;
    }

}
