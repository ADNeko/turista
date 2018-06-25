<?php

namespace Fx\SchoolBundle\Form\Model;

class BuscarUsuario
{
    public $apellidoPaterno;
    public $documento;
    public $localPrincipal;
    public $rol;


    public function getFieldAndValue()
    {
//        if ($this->documento !== null) return array(
//            'field' => 'documento',
//            'value' => $this->documento,
//        );

        if ($this->apellidoPaterno !== null) return array(
            'field' => 'apellidoPaterno',
            'value' => $this->apellidoPaterno,
        );

        return null;
    }
}
