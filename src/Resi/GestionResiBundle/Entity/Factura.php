<?php

namespace Resi\GestionResiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Factura
 */
class Factura
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $codFactura;

    /**
     * @var \DateTime
     */
    private $fechaExpedicion;

    /**
     * @var \DateTime
     */
    private $fechaPago;

    /**
     * @var float
     */
    private $importe;

    /**
     * @var integer
     */
    private $codContrato;


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
     * Set codFactura
     *
     * @param integer $codFactura
     * @return Factura
     */
    public function setCodFactura($codFactura)
    {
        $this->codFactura = $codFactura;
    
        return $this;
    }

    /**
     * Get codFactura
     *
     * @return integer 
     */
    public function getCodFactura()
    {
        return $this->codFactura;
    }

    /**
     * Set fechaExpedicion
     *
     * @param \DateTime $fechaExpedicion
     * @return Factura
     */
    public function setFechaExpedicion($fechaExpedicion)
    {
        $this->fechaExpedicion = $fechaExpedicion;
    
        return $this;
    }

    /**
     * Get fechaExpedicion
     *
     * @return \DateTime 
     */
    public function getFechaExpedicion()
    {
        return $this->fechaExpedicion;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     * @return Factura
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;
    
        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime 
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set importe
     *
     * @param float $importe
     * @return Factura
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;
    
        return $this;
    }

    /**
     * Get importe
     *
     * @return float 
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set codContrato
     *
     * @param integer $codContrato
     * @return Factura
     */
    public function setCodContrato($codContrato)
    {
        $this->codContrato = $codContrato;
    
        return $this;
    }

    /**
     * Get codContrato
     *
     * @return integer 
     */
    public function getCodContrato()
    {
        return $this->codContrato;
    }
}
