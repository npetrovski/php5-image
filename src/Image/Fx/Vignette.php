<?php

namespace Image\Fx;

use Image\Canvas as Canvas;
use Image\Plugin\PluginInterface;

class Vignette extends FxBase implements PluginInterface {

    protected $_vignette;

    public function __construct(Canvas $vignette = null) {
        $this->setVignette($vignette);
    }

    public function setVignette($vignette) {
        $this->_vignette = $vignette;
        
        return $this;
    }

    public function generate() {

        if ($this->_vignette instanceof Canvas) {

            imagesavealpha($this->_owner->image, true);
            imagealphablending($this->_owner->image, false);

            $vignette_x = $this->_vignette->getImageWidth();
            $vignette_y = $this->_vignette->getImageHeight();
            for ($y = 0; $y < $vignette_y; $y++) {
                for ($x = 0; $x < $vignette_x; $x++) {
                    $irgb = imagecolorat($this->_owner->image, $x, $y);
                    $r = ($irgb >> 16) & 0xFF;
                    $g = ($irgb >> 8) & 0xFF;
                    $b = $irgb & 0xFF;
                    $vrgb = imagecolorat($this->_vignette->image, $x, $y);
                    $a = ($vrgb >> 24) & 0xFF;

                    $colour = imagecolorallocatealpha($this->_owner->image, $r, $g, $b, $a);
                    imagesetpixel($this->_owner->image, $x, $y, $colour);
                }
            }
        }

        return true;
    }

}
