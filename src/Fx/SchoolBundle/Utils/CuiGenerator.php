<?php

namespace Fx\SchoolBundle\Utils;

use Fx\SchoolBundle\Manager\SettingsManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * @Service(id="fx_school.cui_generator")
 */
class CuiGenerator
{
    private $A;
    private $B;


    public function __construct()
    {
        $this->A = 9973;
        $this->B = 213659;
    }


    /**
     * @return string
     */
    public function getCui($key, $prefix = null)
    {
        if ($prefix === null)
            $prefix = date("Y");

        $code = (int)$this->getNextCode($key);

        return $prefix . sprintf('%04d', $code);
    }


    /*
     * http://stackoverflow.com/questions/2245292/how-can-i-create-a-unique-7-digit-code-for-an-entity
     */
    private function getNextCode($key)
    {
        $key = 1 + ($key % ($this->A - 1));

        return ($key * $this->B) % $this->A;
    }
}
