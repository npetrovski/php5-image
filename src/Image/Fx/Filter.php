<?php

namespace Image\Fx;

use Image\Plugin\PluginInterface;

class Filter extends FxBase implements PluginInterface
{
    private $_filter_args = array();

    /**
     *  arg0
     *      IMG_FILTER_NEGATE: Reverses all colors of the image.
     *      IMG_FILTER_GRAYSCALE: Converts the image into grayscale.
     *      IMG_FILTER_BRIGHTNESS: Changes the brightness of the image. Use arg1 to set the level of brightness.
     *      IMG_FILTER_CONTRAST: Changes the contrast of the image. Use arg1 to set the level of contrast.
     *      IMG_FILTER_COLORIZE: Like IMG_FILTER_GRAYSCALE, except you can specify the color. Use arg1, arg2 and arg3 in the form of red, green, blue and arg4 for the alpha channel. The range for each color is 0 to 255.
     *      IMG_FILTER_EDGEDETECT: Uses edge detection to highlight the edges in the image.
     *      IMG_FILTER_EMBOSS: Embosses the image.
     *      IMG_FILTER_GAUSSIAN_BLUR: Blurs the image using the Gaussian method.
     *      IMG_FILTER_SELECTIVE_BLUR: Blurs the image.
     *      IMG_FILTER_MEAN_REMOVAL: Uses mean removal to achieve a "sketchy" effect.
     *      IMG_FILTER_SMOOTH: Makes the image smoother. Use arg1 to set the level of smoothness.
     *      IMG_FILTER_PIXELATE: Applies pixelation effect to the image, use arg1 to set the block size and arg2 to set the pixelation effect mode.
     *
     *  arg1
     *      IMG_FILTER_BRIGHTNESS: Brightness level.
     *      IMG_FILTER_CONTRAST: Contrast level.
     *      IMG_FILTER_COLORIZE: Value of red component.
     *      IMG_FILTER_SMOOTH: Smoothness level.
     *      IMG_FILTER_PIXELATE: Block size in pixels.
     *
     *  arg2
     *      IMG_FILTER_COLORIZE: Value of green component.
     *      IMG_FILTER_PIXELATE: Whether to use advanced pixelation effect or not (defaults to FALSE).
     *
     *  arg3
     *      IMG_FILTER_COLORIZE: Value of blue component.
     *
     *  arg4
     *      IMG_FILTER_COLORIZE: Alpha channel, A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent.
     */
    public function __construct()
    {
        $this->_filter_args = func_get_args();
    }

    public function generate()
    {
        if (count($this->_filter_args) > 0) {
            $args = $this->_filter_args;
            array_unshift($args, $this->_owner->image);

            return call_user_func_array('imagefilter', $args);
        }

        return false;
    }
}
