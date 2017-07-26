<?php

namespace Resi\GestionResiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Habitacion
 */
class Habitacion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $CodHabitacion;

    /**
     * @var integer
     */
    private $numDisponibles;
    
    /**
     * @var string
     */
    private $Descripcion;

    /**
     * @var integer
     */
    private $TipoHabitacion;

    /**
     * @var float
     */
    private $TarifaMes;


    /**
     * @var string
     */
    private $path;
    
    /**
     * Set path
     *
     * @param string $path
     * @return Habitacion
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
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
     * Set CodHabitacion
     *
     * @param integer $codhabitacion
     * @return Habitacion
     */
    public function setCodhabitacion($codhabitacion)
    {
        $this->CodHabitacion = $codhabitacion;
    
        return $this;
    }

    /**
     * Get CodHabitacion
     *
     * @return integer 
     */
    public function getCodhabitacion()
    {
        return $this->CodHabitacion;
    }
    
    
    /**
     * Set numDisponibles
     *
     * @param integer $numDisponibles
     * @return Habitacion
     */
    public function setNumDisponibles($numDisponibles)
    {
        $this->numDisponibles = $numDisponibles;
    
        return $this;
    }

    /**
     * Get numDisponibles
     *
     * @return integer 
     */
    public function getNumDisponibles()
    {
        return $this->numDisponibles;
    }

    /**
     * Set Descripcion
     *
     * @param string $descripcion
     * @return Habitacion
     */
    public function setDescripcion($descripcion)
    {
        $this->Descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    /**
     * Set tipohabitacion
     *
     * @param integer $tipohabitacion
     * @return Habitacion
     */
    public function setTipohabitacion($tipohabitacion)
    {
        $this->TipoHabitacion = $tipohabitacion;
    
        return $this;
    }

    /**
     * Get tipohabitacion
     *
     * @return integer 
     */
    public function getTipohabitacion()
    {
        return $this->TipoHabitacion;
    }

    /**
     * Set tarifames
     *
     * @param float $tarifames
     * @return Habitacion
     */
    public function setTarifames($tarifames)
    {
        $this->TarifaMes = $tarifames;
    
        return $this;
    }

    /**
     * Get tarifames
     *
     * @return float 
     */
    public function getTarifames()
    {
        return $this->TarifaMes;
    }
}
