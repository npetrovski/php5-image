<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Plugin\PluginInterface;
use Image\Helper\Color;

class Gaussian extends FxBase implements PluginInterface
{
    private $_matrix = array(1, 4, 8, 16, 8, 4, 1);

    public function __construct($matrix = array(1, 4, 8, 16, 8, 4, 1))
    {
        $this->setMatrix($matrix);
    }

    public function setMatrix(array $matrix)
    {
        $this->_matrix = $matrix;

        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();
        $matrix = $this->_matrix;
        $matrix_width = count($matrix);

        $matrix_sum = array_sum($matrix);

        $m_offset = floor($matrix_width / 2);

        $p1 = $p2 = array();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = Color::intColorToArrayColor($this->_owner->imageColorAt($x, $y));
                $p1[$x][$y]['r'] = $color['red'];
                $p1[$x][$y]['g'] = $color['green'];
                $p1[$x][$y]['b'] = $color['blue'];
                $p1[$x][$y]['a'] = $color['alpha'];

                $p2[$x][$y]['r'] = 255;
                $p2[$x][$y]['g'] = 255;
                $p2[$x][$y]['b'] = 255;
                $p2[$x][$y]['a'] = 127;
            }
        }

        $temp = new Canvas();
        $temp->createImageTrueColorTransparent($width, $height);

        imagesavealpha($temp->image, true);
        imagealphablending($temp->image, true);

        for ($i = $m_offset; $i < $width - $m_offset; $i++) {
            for ($j = $m_offset; $j < $height - $m_offset; $j++) {
                $sumr = $sumg = $sumb = $suma = 0;

                for ($k = 0; $k < $matrix_width; $k++) {
                    $xx = $i - (($matrix_width) >> 1) + $k;
                    $sumr += $p1[$xx][$j]['r'] * $matrix[$k];
                    $sumg += $p1[$xx][$j]['g'] * $matrix[$k];
                    $sumb += $p1[$xx][$j]['b'] * $matrix[$k];
                    $suma += $p1[$xx][$j]['a'] * $matrix[$k];
                }
                $p2[$i][$j]['r'] = $sumr / $matrix_sum;
                $p2[$i][$j]['g'] = $sumg / $matrix_sum;
                $p2[$i][$j]['b'] = $sumb / $matrix_sum;
                $p2[$i][$j]['a'] = $suma / $matrix_sum;
            }
        }

        for ($i = $m_offset; $i < $width - $m_offset; $i++) {
            for ($j = $m_offset; $j < $height - $m_offset; $j++) {
                $sumr = $sumg = $sumb = $suma = 0;

                for ($k = 0; $k < $matrix_width; $k++) {
                    $xy = $j - (($matrix_width) >> 1) + $k;
                    $sumr += $p2[$i][$xy]['r'] * $matrix[$k];
                    $sumg += $p2[$i][$xy]['g'] * $matrix[$k];
                    $sumb += $p2[$i][$xy]['b'] * $matrix[$k];
                    $suma += $p2[$i][$xy]['a'] * $matrix[$k];
                }
                $col = imagecolorallocatealpha($temp->image,
                        ($sumr / $matrix_sum),
                        ($sumg / $matrix_sum),
                        ($sumb / $matrix_sum),
                        ($suma / $matrix_sum)
                );
                imagesetpixel($temp->image, $i, $j, $col);
            }
        }

        $this->_owner->image = $temp->image;
        unset($temp);

        return true;
    }
}
