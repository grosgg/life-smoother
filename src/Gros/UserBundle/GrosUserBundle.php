<?php
// src/Gros/UserBundle/GrosUserBundle.php

namespace Gros\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GrosUserBundle extends Bundle
{
    public function getParent()
    {
        return 'SonataUserBundle';
    }
}

