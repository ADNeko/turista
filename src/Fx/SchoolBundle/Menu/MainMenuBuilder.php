<?php
namespace Fx\SchoolBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MainMenuBuilder extends ContainerAware
{
    private $menuItems = array();


    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $this->addInicio($menu);
        $this->addPerfil($menu);
        $this->addConsultar($menu);
        $this->addUsuarios($menu);
        $this->setCurrentRoute($menu);

        $menu->setChildrenAttribute('class', 'nav nav-sidebar');

        return $menu;
    }


    private function addInicio(ItemInterface $menu)
    {
        $this->menuItems['inicio'] = $label = '<i class="fa fa-home"></i> Inicio';

        $menu->addChild($label, array(
            'route' => 'fx_school.default.index',
        ));
    }



    private function addPerfil(ItemInterface $menu)
    {
        $this->menuItems['perfil'] = $label = '<i class="fa fa-user"></i> Mi perfil';

        $menu->addChild($label, array(
            'route' => 'fx_school.my_profile.index',
        ));
    }



    private function addConsultar(ItemInterface $menu)
    {
        $this->menuItems['consultar'] = $label = '<i class="fa fa-user"></i>Consultar';

        $menu->addChild($label, array(
            'route' => 'fx_tourist.foto.subir',
        ));
    }





    private function addUsuarios(ItemInterface $menu)
    {
        if (true === $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {

            $this->menuItems['usuarios'] = $label = '<i class="fa fa-users"></i> Usuarios';

            $menu->addChild($label, array(
                'uri'        => '#',
                'attributes' => $this->getLabelAttributes(),
            ));

            $menu[$label]->setLinkAttributes($this->getLinkAttributes('usuarios'));

            $menu[$label]->setChildrenAttributes(array(
                'id'    => $this->getChildrenId('usuarios'),
                'class' => $this->getChildrenClass(),
            ));

            $menu[$label]->addChild('Registrar', array(
                'route' => 'fx_school.usuario.new',
            ));

            $menu[$label]->addChild('Buscar', array(
                'route' => 'fx_school.usuario.index',
            ));
        }
    }




    private function setCurrentRoute(ItemInterface $menu)
    {
        $request   = $this->container->get('request');
        $routeName = $request->get('_route');

        if ($this->startsWith($routeName, 'fx_school.usuario')) {
            $menu[$this->menuItems['usuarios']]->setCurrent(true);
        } elseif ($this->startsWith($routeName, 'fx_tourist.foto.subir')) {
        $menu[$this->menuItems['consultar']]->setCurrent(true);
    }
    }


    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }


    //region Attributes
    private static $labelAttributes = array(
        'class' => 'submenu-header',
    );

    private static $linkAttributes = array(
        'data-toggle' => 'collapse',
        'data-target' => '#name-submenu',
        'class'       => 'collapsed',
    );

    private static $childrenAttributes = array(
        'id'    => 'name-submenu',
        'class' => 'submenu collapse',
    );


    private function getLabelAttributes()
    {
        return array('class' => 'submenu-header');
    }


    private function getLinkAttributes($name)
    {
        return array(
            'data-toggle' => 'collapse',
            'data-target' => '#' . $name . '-submenu',
            'class'       => 'collapsed',
        );
    }


    private function getChildrenId($name)
    {
        return $name . '-submenu';
    }


    private function getChildrenClass()
    {
        return 'submenu collapse';
    }
    //endregion
}
