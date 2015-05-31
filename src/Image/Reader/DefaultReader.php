<?php

namespace Image\Reader;

use Image\Exception as ImageException;

class DefaultReader
{
    public function read($filename)
    {
        $image_data = getimagesize($filename);
        if ($image_data) {
            $class_name = __NAMESPACE__.'\\Adapter\\'.ucfirst(strtolower(image_type_to_extension($image_data[2], false)));

            if (!class_exists($class_name)) {
                throw new ImageException("No reader is defined for this image");
            }
            
            $adapter = new $class_name();

            return $adapter->getImage($filename);

        }
    }
}
