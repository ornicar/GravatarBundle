<?php

namespace Ornicar\GravatarBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class OrnicarGravatarBundle extends BaseBundle
{
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return __DIR__;
    }
}
