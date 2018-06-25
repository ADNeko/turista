<?php

namespace Fx\SchoolBundle\Twig;

use Fx\AccountingBundle\Entity\Caja;
use Fx\AccountingBundle\Entity\ReciboEgreso;
use Fx\AccountingBundle\Entity\ReciboIngreso;

class FxExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('format_sexo', array($this, 'formatSexo')),
            new \Twig_SimpleFilter('format_estado_civil', array($this, 'formatEstadoCivil')),
            new \Twig_SimpleFilter('format_grado', array($this, 'formatGrado')),
            new \Twig_SimpleFilter('format_tipo_documento', array($this, 'formatTipoDocumento')),
            new \Twig_SimpleFilter('format_si_no', array($this, 'formatSiNo')),
            new \Twig_SimpleFilter('formatEstadosCaja', array($this, 'formatEstadosCaja')),
            new \Twig_SimpleFilter('format_estado_recuperacion', array($this, 'formatEstadoRecuperacion')),
            new \Twig_SimpleFilter('format_mayuscula', array($this, 'formatMayuscula')),
            new \Twig_SimpleFilter('format_pension', array($this, 'formatEstadoPension')),
            new \Twig_SimpleFilter('format_total_retiros', array($this, 'formatTotalRetiros')),
        );
    }


    public function formatSexo($char)
    {
        if ($char === 'm' || $char === 'M') return "Masculino";
        else if ($char === 'f' || $char === 'F') return "Femenino";

        return '--';
    }


    public function formatSiNo($char)
    {
        if ($char === 's' || $char === 'S') return "Sí";
        else if ($char === 'n' || $char === 'N') return "No";

        return '--';
    }


    public function formatEstadoCivil($char)
    {
        if ($char === 's' || $char === 'S') return "Soltero";
        else if ($char === 'c' || $char === 'C') return "Casado";
        else if ($char === 'v' || $char === 'V') return "Viudo";
        else if ($char === 'd' || $char === 'D') return "Divorciado";

        return '--';
    }
    public function formatEstadoRecuperacion($char)
    {
        if ($char === 1) return "-";
        if($char===2)  return "X";
        return '--';
    }

    public function formatGrado($char)
    {
        if ($char === 's' || $char === 'S') return "Secundaria";
        else if ($char === 'z' || $char === 'Z') return "Superior";

        return '--';
    }


    public function formatTipoDocumento($string)
    {
        $string = strtolower($string);

        if ($string === 'dni') return "DNI";
        else if ($string === 'pasaporte') return "Pasaporte";

        return $string;
    }
    public function formatEstadoPension($string){
        $string = strtolower($string);

        if($string == 'habilitado') return "HABILITADO";

        return "PENDIENTE";
    }

    public function getName()
    {
        return 'fx_extension';
    }
    public function formatEstadosCaja($string){
        if ($string === 'abierto') return "ABIERTO";
        else if ($string === 'cerrado') return "CERRADO";
        return '--';
    }
    public function formatMayuscula($string){
        return strtoupper($string);
    }
    public function formatTotalRetiros(Caja $caja){
        $count=0;
        /** @var ReciboEgreso $recibo */
        foreach($caja->getRecibosEgreso() as $recibo){
            if(!is_null($recibo->getcajachica()) and $recibo->getEstado()!=ReciboEgreso::ESTADO_ANULADO){
                $count=$count+1;
            }
        }
        return $count;
    }

}
