<?php

namespace Resi\GestionResiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrato
 */
class Contrato
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $CodContrato;

    /**
     * @var string
     */
    private $DNIResidente;

    /**
     * @var integer
     */
    private $CodHabitacion;

    /**
     * @var \DateTime
     */
    private $FechaContrato;

    
    /**
     * @var \DateTime
     */
    private $fechaExpiracion;

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
     * Set CodContrato
     *
     * @param integer $CodContrato
     * @return Contrato
     */
    public function setCodContrato($CodContrato)
    {
        $this->CodContrato = $CodContrato;
    
        return $this;
    }

    /**
     * Get CodContrato
     *
     * @return integer 
     */
    public function getCodContrato()
    {
        return $this->CodContrato;
    }

    /**
     * Set dNIResidente
     *
     * @param string $dNIResidente
     * @return Contrato
     */
    public function setDNIResidente($dNIResidente)
    {
        $this->DNIResidente = $dNIResidente;
    
        return $this;
    }

    /**
     * Get dNIResidente
     *
     * @return string 
     */
    public function getDNIResidente()
    {
        return $this->DNIResidente;
    }

    /**
     * Set codHabitacion
     *
     * @param integer $codHabitacion
     * @return Contrato
     */
    public function setCodHabitacion($codHabitacion)
    {
        $this->CodHabitacion = $codHabitacion;
    
        return $this;
    }

    /**
     * Get codHabitacion
     *
     * @return integer 
     */
    public function getCodHabitacion()
    {
        return $this->CodHabitacion;
    }

    /**
     * Set fechaContrato
     *
     * @param \DateTime $fechaContrato
     * @return Contrato
     */
    public function setFechaContrato($fechaContrato)
    {
        $this->FechaContrato = $fechaContrato;
    
        return $this;
    }

    /**
     * Get fechaContrato
     *
     * @return \DateTime 
     */
    public function getFechaContrato()
    {
        return $this->FechaContrato;
    }
    
    /**
     * Set fechaExpiracion
     *
     * @param \DateTime $fechaExpiracion
     * @return Contrato
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;
    
        return $this;
    }

    /**
     * Get fechaExpiracion
     *
     * @return \DateTime 
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }
}
