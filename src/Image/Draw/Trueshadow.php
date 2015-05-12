<?php

namespace Image\Draw;

use Image\Canvas;
use Image\Plugin\PluginInterface;
use Image\Helper\Color;

class Trueshadow extends DrawBase implements PluginInterface
{
    private $_distance;
    private $_color;
    private $_matrix;

    public function __construct($distance = 10, $color = '888888', $matrix = array(1, 2, 4, 4, 8, 8, 12, 12, 16, 16, 24, 32, 32, 24, 16, 16, 12, 12, 8, 8, 4, 4, 2, 1))
    {
        $this->setDistance($distance);
        $this->setColor($color);
        $this->setMatrix($matrix);
    }

    public function setDistance($distance = 10)
    {
        $this->_distance = $distance;

        return $this;
    }

    public function setColor($color = '888888')
    {
        $this->_color = $color;

        return $this;
    }

    public function setMatrix($matrix = array(1, 2, 4, 4, 8, 8, 12, 12, 16, 16, 24, 32, 32, 24, 16, 16, 12, 12, 8, 8, 4, 4, 2, 1))
    {
        $this->_matrix = $matrix;

        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        $distance = $this->_distance;
        $matrix = $this->_matrix;

        $matrix_width = count($matrix);
        $matrix_sum = array_sum($matrix);

        $m_offset = floor($matrix_width / 2);
        $temp = new Canvas();
        $temp->createImageTrueColorTransparent($width + ($matrix_width * 2), $height + ($matrix_width * 2));
        imagecopy($temp->image, $this->_owner->image, $matrix_width, $matrix_width, 0, 0, $width, $height);

        $w = $temp->getImageWidth();
        $h = $temp->getImageHeight();
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $t = $temp->imagecolorat($x, $y);
                $t1 = Color::intColorToArrayColor($t);
                $p[$x][$y]['r'] = $t1['red'];
                $p[$x][$y]['g'] = $t1['green'];
                $p[$x][$y]['b'] = $t1['blue'];
                $p[$x][$y]['a'] = $t1['alpha'];
                $p1[$x][$y]['r'] = 255;
                $p1[$x][$y]['g'] = 255;
                $p1[$x][$y]['b'] = 255;
                $p1[$x][$y]['a'] = 127;
            }
        }
        $w = $this->_owner->getImageWidth();
        $h = $this->_owner->getImageHeight();
        $d_offset = $distance - $matrix_width;
        if ($this->_color != 'image') {
            $arrColor = Color::hexColorToArrayColor($this->_color);
        }

        $temp->createImageTrueColorTransparent($width + $distance + $m_offset, $height + $distance + $m_offset);
        imagesavealpha($temp->image, true);
        imagealphablending($temp->image, true);
        for ($i = $m_offset; $i < $w + $m_offset + $matrix_width; $i++) {
            for ($j = $m_offset; $j < $h + $m_offset + $matrix_width; $j++) {
                $sumr = 0;
                $sumg = 0;
                $sumb = 0;
                $suma = 0;
                for ($k = 0; $k < $matrix_width; $k++) {
                    $xx = $i - (($matrix_width) >> 1) + $k;
                    $sumr += $p[$xx][$j]['r'] * $matrix[$k];
                    $sumg += $p[$xx][$j]['g'] * $matrix[$k];
                    $sumb += $p[$xx][$j]['b'] * $matrix[$k];
                    $suma += $p[$xx][$j]['a'] * $matrix[$k];
                }
                $p1[$i][$j]['r'] = $sumr / $matrix_sum;
                $p1[$i][$j]['g'] = $sumg / $matrix_sum;
                $p1[$i][$j]['b'] = $sumb / $matrix_sum;
                $p1[$i][$j]['a'] = $suma / $matrix_sum;
            }
        }
        for ($i = $m_offset; $i < $w + $m_offset + $matrix_width; $i++) {
            for ($j = $m_offset; $j < $h + $m_offset + $matrix_width; $j++) {
                $sumr = 0;
                $sumg = 0;
                $sumb = 0;
                $suma = 0;
                for ($k = 0; $k < $matrix_width; $k++) {
                    $xy = $j - (($matrix_width) >> 1) + $k;
                    $sumr += $p1[$i][$xy]['r'] * $matrix[$k];
                    $sumg += $p1[$i][$xy]['g'] * $matrix[$k];
                    $sumb += $p1[$i][$xy]['b'] * $matrix[$k];
                    $suma += $p1[$i][$xy]['a'] * $matrix[$k];
                }
                if ($this->_color != 'image') {
                    $col = imagecolorallocatealpha($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue'], ($suma / $matrix_sum));
                } else {
                    $col = imagecolorallocatealpha($temp->image, ($sumr /
                            $matrix_sum), ($sumg /
                            $matrix_sum), ($sumb /
                            $matrix_sum), ($suma / $matrix_sum));
                }
                imagesetpixel($temp->image, $i + $d_offset, $j + $d_offset, $col);
            }
        }

        imagecopy($temp->image, $this->_owner->image, 0, 0, 0, 0, $width, $height);
        $this->_owner->image = $temp->image;
        unset($temp);

        return true;
    }
}
