<?php

namespace Image\Reader\Adapter;

use Image\Reader\ReaderAbstract;

class Jpeg extends ReaderAbstract
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
        if ($this->_detectGD('JPEG') && function_exists('imagecreatefromjpeg')) {
            return imagecreatefromjpeg($filename);
        }

        return false;
    }
}
