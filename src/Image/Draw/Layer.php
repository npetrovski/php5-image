<?php

namespace Image\Draw;

use Image\Canvas;
use Image\Plugin\PluginInterface;

class Layer extends DrawBase implements PluginInterface
{
    private $_preserveLayerSize = true;

    private $_position_x = 0;

    private $_position_y = 0;

    private $_img_layer;

    public function __construct(Canvas $img = null, $x = 0, $y = 0, $preserveLayerSize = true)
    {
        $this->setLayer($img);
        $this->setPosition($x, $y, $preserveLayerSize);
    }

    public function setLayer(Canvas $img = null)
    {
        $this->_img_layer = $img;

        return $this;
    }

    public function setPosition($x = 0, $y = 0, $preserveLayerSize = true)
    {
        $this->_position_x = $x;
        $this->_position_y = $y;

        $this->_preserveLayerSize = $preserveLayerSize;

        return $this;
    }

    public function generate()
    {
        $this->_img_layer->apply();

        //getting the width and height of the body part image, (should be the same size as the canvas)
        $layer_w = $this->_img_layer->getImageWidth();
        $layer_h = $this->_img_layer->getImageHeight();

        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        //making sure that alpha blending is enabled
        imagealphablending($this->_owner->image, true);

        //making sure to preserve the alpha info
        imagesavealpha($this->_owner->image, true);

        //finally, putting that image on top of our canvas
        if ($this->_preserveLayerSize) {
            imagecopyresampled($this->_owner->image, $this->_img_layer->image, $this->_position_x, $this->_position_y, 0, 0, $layer_w, $layer_h, $layer_w, $layer_h);
        } else {
            imagecopyresampled($this->_owner->image, $this->_img_layer->image, $this->_position_x, $this->_position_y, 0, 0, $width, $height, $layer_w, $layer_h);
        }

        //$this->_owner->image
        return true;
    }
}
