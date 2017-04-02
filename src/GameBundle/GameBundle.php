<?php

namespace GameBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GameBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
