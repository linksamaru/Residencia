<?php

namespace Resi\GestionResiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * usuarioadmin
 */
class usuarioadmin
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $idAdmin;

    /**
     * @var string
     */
    private $dNIUser;


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
     * Set idAdmin
     *
     * @param integer $idAdmin
     * @return usuarioadmin
     */
    public function setIdAdmin($idAdmin)
    {
        $this->idAdmin = $idAdmin;
    
        return $this;
    }

    /**
     * Get idAdmin
     *
     * @return integer 
     */
    public function getIdAdmin()
    {
        return $this->idAdmin;
    }

    /**
     * Set dNIUser
     *
     * @param string $dNIUser
     * @return usuarioadmin
     */
    public function setDNIUser($dNIUser)
    {
        $this->dNIUser = $dNIUser;
    
        return $this;
    }

    /**
     * Get dNIUser
     *
     * @return string 
     */
    public function getDNIUser()
    {
        return $this->dNIUser;
    }
}
