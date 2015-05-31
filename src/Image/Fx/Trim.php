<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Helper\Color;
use Image\Plugin\PluginInterface;

class Trim extends FxBase implements PluginInterface
{
    private $_base;

    private $_away = null;

    private $_tolerance;

    private $_feather;

    public function __construct($base = null, $away = null, $tolerance = 0, $feather = 0)
    {
        $this->setTrim($base, $away, $tolerance, $feather);
    }

    public function setTrim($base = null, $away = null, $tolerance = 0, $feather = 0)
    {
        $this->_base = $base;
        $this->_away = $away;
        $this->_tolerance = $tolerance;
        $this->_feather = $feather;
        
        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        // default values
        $checkTransparency = false;

        // define borders to trim away
        if (is_null($this->_away)) {
            $this->_away = array('top', 'right', 'bottom', 'left');
        } elseif (is_string($this->_away)) {
            $this->_away = array($this->_away);
        }

        // lower border names
        foreach ($this->_away as $key => $value) {
            $this->_away[$key] = strtolower($value);
        }

        // define base color position
        switch (strtolower($this->_base)) {
            case 'transparent':
            case 'trans':
                $checkTransparency = true;
                $base_x = 0;
                $base_y = 0;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $base_x = $width - 1;
                $base_y = $height - 1;
                break;

            default:
            case 'top-left':
            case 'left-top':
                $base_x = 0;
                $base_y = 0;
                break;
        }

        // pick base color
        if ($checkTransparency) {
            // color will only be used to compare alpha channel
            $color = array(
                'red' => 255, 
                'green' => 255, 
                'blue' => 255, 
                'alpha' => 0
            );
        } else {
            $color = Color::intColorToArrayColor($this->_owner->imageColorAt($base_x, $base_y));
        }

        $top_x = 0;
        $top_y = 0;
        $bottom_x = $width;
        $bottom_y = $height;

        // search upper part of image for colors to trim away
        if (in_array('top', $this->_away)) {
            for ($y = 0; $y < ceil($height / 2); $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $checkColor = Color::intColorToArrayColor($this->_owner->imageColorAt($x, $y));

                    if ($checkTransparency) {
                        $checkColor['red'] = $color['red'];
                        $checkColor['green'] = $color['green'];
                        $checkColor['blue'] = $color['blue'];
                    }

                    if (Color::differs($color, $checkColor, $this->_tolerance)) {
                        $top_y = max(0, $y - $this->_feather);
                        break 2;
                    }
                }
            }
        }

        // search left part of image for colors to trim away
        if (in_array('left', $this->_away)) {
            for ($x = 0; $x < ceil($width / 2); $x++) {
                for ($y = $top_y; $y < $height; $y++) {
                    $checkColor = Color::intColorToArrayColor($this->_owner->imageColorAt($x, $y));

                    if ($checkTransparency) {
                        $checkColor['red'] = $color['red'];
                        $checkColor['green'] = $color['green'];
                        $checkColor['blue'] = $color['blue'];
                    }

                    if (Color::differs($color, $checkColor, $this->_tolerance)) {
                        $top_x = max(0, $x - $this->_feather);
                        break 2;
                    }
                }
            }
        }

        // search lower part of image for colors to trim away
        if (in_array('bottom', $this->_away)) {
            for ($y = ($height - 1); $y >= floor($height / 2) - 1; $y--) {
                for ($x = $top_x; $x < $width; $x++) {
                    $checkColor = Color::intColorToArrayColor($this->_owner->imageColorAt($x, $y));

                    if ($checkTransparency) {
                        $checkColor['red'] = $color['red'];
                        $checkColor['green'] = $color['green'];
                        $checkColor['blue'] = $color['blue'];
                    }

                    if (Color::differs($color, $checkColor, $this->_tolerance)) {
                        $bottom_y = min($height, $y + 1 + $this->_feather);
                        break 2;
                    }
                }
            }
        }

        // search right part of image for colors to trim away
        if (in_array('right', $this->_away)) {
            for ($x = ($width - 1); $x >= floor($width / 2) - 1; $x--) {
                for ($y = $top_y; $y < $bottom_y; $y++) {
                    $checkColor = Color::intColorToArrayColor($this->_owner->imageColorAt($x, $y));

                    if ($checkTransparency) {
                        $checkColor['red'] = $color['red'];
                        $checkColor['green'] = $color['green'];
                        $checkColor['blue'] = $color['blue'];
                    }

                    if (Color::differs($color, $checkColor, $this->_tolerance)) {
                        $bottom_x = min($width, $x + 1 + $this->_feather);
                        break 2;
                    }
                }
            }
        }

        $trimmed = new Canvas();
        $trimmed->createImageTrueColorTransparent($bottom_x - $top_x, $bottom_y - $top_y);

        // Preserve transparency
        $transIndex = imagecolortransparent($this->_owner->image);
        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($trimmed->image, $transIndex);
            $transColor = imagecolorallocatealpha($trimmed->image, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($trimmed->image, 0, 0, $transColor);
            imagecolortransparent($trimmed->image, $transColor);
        } else {
            imagealphablending($trimmed->image, false);
            imagesavealpha($trimmed->image, true);
        }

        imagecopyresampled($trimmed->image, $this->_owner->image, 0, 0, $top_x, $top_y, ($bottom_x - $top_x), ($bottom_y - $top_y), ($bottom_x - $top_x), ($bottom_y - $top_y));

        $this->_owner->image = $trimmed->image;

        return true;
    }
}
