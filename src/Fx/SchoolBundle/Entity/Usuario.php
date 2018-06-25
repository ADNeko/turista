<?php

namespace Fx\SchoolBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(columns={"rol", "apellido_paterno", "apellido_materno", "nombres"}), @ORM\Index(columns={"apellido_paterno", "apellido_materno", "nombres"})})})
 * @ORM\Entity(repositoryClass="Fx\SchoolBundle\Repository\UsuarioRepository")
 *
 * @ExclusionPolicy("all")
 */
class Usuario extends BaseUser
{
    //region Roles
    const ROL_ADMISION     = 'admision';

    const ROL_ADMINISTRADOR = 'administrador';
    //endregion

    //region Datos miembro
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido_paterno", type="string", length=100)
     *
     * @Expose
     */
    private $apellidoPaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido_materno", type="string", length=100, nullable=true)
     *
     * @Expose
     */
    private $apellidoMaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="nombres", type="string", length=100)
     *
     * @Expose
     */
    private $nombres;


    /**
     * @var string
     *
     * @ORM\Column(name="rol", type="string", length=50, nullable=true)
     *
     * @Expose
     */
    private $rol;

    //endregion


    //endregion

    //region Constructor
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->rol=Usuario::ROL_ADMISION;
    }
    //endregion

    //region Métodos
    /**
     * Get NombreCompleto
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->getNombres() . " " . $this->getApellidoPaterno() . " " . $this->getApellidoMaterno();
    }
    /**
     * Get NombreApellido
     *
     * @return string
     */
    public function getNombreApellido()
    {
        return $this->getNombres() . " " . $this->getApellidoPaterno();
    }
    /**
     * Get NombreCompletoApellidosPrimero
     *
     * @return string
     */
    public function getNombreCompletoApellidosPrimero()
    {
        return $this->getApellidoPaterno() . ' ' . $this->getApellidoMaterno() . ', ' . $this->getNombres();
    }
    //endregion

    //region Getters and setters
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set apellidoPaterno
     *
     * @param string $apellidoPaterno
     * @return Usuario
     */
    public function setApellidoPaterno($apellidoPaterno)
    {
        $this->apellidoPaterno = $apellidoPaterno;

        return $this;
    }


    /**
     * Get apellidoPaterno
     *
     * @return string
     */
    public function getApellidoPaterno()
    {
        return $this->apellidoPaterno;
    }


    /**
     * Set apellidoMaterno
     *
     * @param string $apellidoMaterno
     * @return Usuario
     */
    public function setApellidoMaterno($apellidoMaterno)
    {
        $this->apellidoMaterno = $apellidoMaterno;

        return $this;
    }


    /**
     * Get apellidoMaterno
     *
     * @return string
     */
    public function getApellidoMaterno()
    {
        return $this->apellidoMaterno;
    }


    /**
     * Set nombres
     *
     * @param string $nombres
     * @return Usuario
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }


    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }



    /**
     * Set rol
     *
     * @param string $rol
     * @return Usuario
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }


    /**
     * Get rol
     *
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }


    //endregion
}
