<?php

namespace Image\Reader\Adapter;

use Image\Reader\ReaderAbstract;

class Gif extends ReaderAbstract
{
    /**
     * Return an image resource from file or URL.
     *
     * @param string $filename
     *
     * @return resource an image resource identifier on success, false on errors.
     */
    public function getImage($filename)
    {
        if ($this->_detectGD('GIF') && function_exists('imagecreatefromgif')) {
            return imagecreatefromgif($filename);
        }

        return false;
    }
}
