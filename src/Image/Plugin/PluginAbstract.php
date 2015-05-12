<?php

namespace Image\Plugin;

abstract class PluginAbstract
{
    protected $_owner;

    public function attachToOwner($owner)
    {
        $this->_owner = $owner;
    }
}
