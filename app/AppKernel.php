<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new cayetanosoriano\HashidsBundle\cayetanosorianoHashidsBundle(),
            new Hackzilla\Bundle\PasswordGeneratorBundle\HackzillaPasswordGeneratorBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle(),
            new Fx\SchoolBundle\FxSchoolBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
