<?php

namespace Fx\SchoolBundle;

use Fx\SchoolBundle\DependencyInjection\Compiler\ValidatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FxSchoolBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ValidatorPass());
    }
}
