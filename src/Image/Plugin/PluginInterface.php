<?php

namespace Image\Plugin;

interface PluginInterface
{
    public function attachToOwner($owner);

    public function generate();
}
