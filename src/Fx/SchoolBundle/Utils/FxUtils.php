<?php

namespace Fx\SchoolBundle\Utils;

use Fx\SchoolBundle\Entity\Clase;
use Fx\SchoolBundle\Manager\SettingsManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;


/**
 * @Service(id="fx_school.fx_utils")
 */
class FxUtils
{
    public function getFirstDayOfThisMonth()
    {
        return \DateTime::createFromFormat('d H:i:s', '01 00:00:00');
    }


    public function getLastDayOfThisMonth()
    {
        return \DateTime::createFromFormat('d H:i:s', '01 00:00:00')
            ->add(\DateInterval::createFromDateString('1 month'))
            ->sub(\DateInterval::createFromDateString('1 day'));
    }
    public function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function last7days(Clase $clase){
        $fecha = $clase->getFechaFin();
        date_add($fecha, date_interval_create_from_date_string('7 days'));
        return $fecha;
    }
    public function fechaEspañol(\DateTime $fecha){
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        return $dias[$fecha->format('w')]." ".$fecha->format('d')." de ".$meses[$fecha->format('n')-1]. " del ".$fecha->format('Y') ;
    }
}