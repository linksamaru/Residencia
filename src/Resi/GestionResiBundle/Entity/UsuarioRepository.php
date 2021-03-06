<?php

namespace Resi\GestionResiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends EntityRepository{
    public function usernameExists($username){
        //Manjeador
        $usuario = $this->getEntityManager()
                          ->createQueryBuilder()
                          ->select('u.nick')
                          ->from('GestionResiBundle:Usuario', 'u')
                          ->where('u.nick = :username')
                          ->setParameter('username', $username)
                          ->getQuery()
                          ->getResult();
        if($usuario != null){
            return true;
        }
        return false;
    }
    
    public function checkSimilarDNI($dni)
    {
        $usuario = $this->getEntityManager()
                          ->createQueryBuilder()
                          ->select('u.dNI')
                          ->from('GestionResiBundle:Usuario', 'u')
                          ->where('u.dNI = :dni')
                          ->setParameter('dni', $dni)
                          ->getQuery()
                          ->getResult();
        if($usuario != null)
        {
            return true; //ya existe un usuario registrado con ese dni!
        }
        return false;
    }
    
    public function getNombreUser($usu){
        //Manjeador
        $manejador = $this->getEntityManager();
        $dql =  "SELECT u.nombre "
                . " FROM GestionResiBundle:Usuario u   "
                . " WHERE u.nick = :username";
        
        $query = $manejador->createQuery($dql);
        $query->setParameter('username', $usu);
        
        $nombre = "";
        foreach($query->getResult() as $array){
            foreach($array as $params){
                $nombre = $params;
            }
        }
        return $nombre;
    }
    
    public function getApellidosUser($usu){
        //Manjeador
        $manejador = $this->getEntityManager();
        $dql =  "SELECT u.apellidos "
                . " FROM GestionResiBundle:Usuario u   "
                . " WHERE u.nick = :username";
        
        $query = $manejador->createQuery($dql);
        $query->setParameter('username', $usu);
        
        $apellidos = "";
        foreach($query->getResult() as $array){
            foreach($array as $params){
                $apellidos =  $params;
            }
        }
        return $apellidos;
    }
    
    public function getEmailUser($usu){
        //Manjeador
        $manejador = $this->getEntityManager();
        $dql =  "SELECT u.email "
                . " FROM GestionResiBundle:Usuario u   "
                . " WHERE u.nick = :username";
        
        $query = $manejador->createQuery($dql);
        $query->setParameter('username', $usu);
        
        $email = "";
        foreach($query->getResult() as $array){
            foreach($array as $params){
                $email =  $params;
            }
        }
        return $email;
    }
    
    public function getTelefonoUser($usu){
        //Manjeador
        $manejador = $this->getEntityManager();
        $dql =  "SELECT u.telefono "
                . " FROM GestionResiBundle:Usuario u   "
                . " WHERE u.nick = :username";
        
        $query = $manejador->createQuery($dql);
        $query->setParameter('username', $usu);
        
        $tlf = "";
        foreach($query->getResult() as $array){
            foreach($array as $params){
                $tlf =  $params;
            }
        }
        return $tlf;
    }
    
    public function getDNIUser($usu){
        return $this->getEntityManager()->createQueryBuilder()
                    ->select("u.dNI")
                    ->from("GestionResiBundle:Usuario","u")
                    ->where("u.nick = :n")
                    ->setParameter("n", $usu)
                    ->getQuery()
                    ->getResult();
    }
    
    public function validatePasswordTextErr($formPass){
        //Manjeador
        $usuario = $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('u.nick')
                        ->from('GestionResiBundle:Usuario', 'u')
                        ->where('u.contrasena = :formPass')
                        ->setParameter('formPass', $formPass)
                        ->getQuery()
                        ->getResult();
        if($usuario != null){
            return null;
        }
            return 'Contraseña antigua incorrecta';        
    }
    
    public function validatePassword($formPass){
        //Manjeador
        $usuario = $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('u.nick')
                        ->from('GestionResiBundle:Usuario', 'u')
                        ->where('u.contrasena = :formPass')
                        ->setParameter('formPass', $formPass)
                        ->getQuery()
                        ->getResult();
        if($usuario != null){
            return true;
        }
            return false;        
    }
    
    public function findByUserSinId($user){
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('u.nombre,u.apellidos,u.dNI,u.nick,u.email,u.telefono,u.fechaRegistro')
                    ->from('GestionResiBundle:Usuario', 'u')
                    ->where('u.nick =:user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getResult();
    }
    
    public function findUserByDNI($dni){
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('u.nombre,u.contrasena,u.apellidos,u.nick,u.email,u.telefono,u.fechaRegistro')
                    ->from('GestionResiBundle:Usuario', 'u')
                    ->where('u.dNI =:lul')
                    ->setParameter('lul', $dni)
                    ->getQuery()
                    ->getResult();
    }
    
    
    public function findAllSinId(){
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('u.nombre,u.apellidos,u.dNI,u.nick,u.email,u.telefono')
                    ->from('GestionResiBundle:Usuario', 'u')
                    ->getQuery()
                    ->getResult();
    }
    
    public function findUsuarioNoArray($username){
        //Manjeador
        $manejador = $this->getEntityManager();
        $dql =  "SELECT u.nombre, u.apellidos, u.dNI, u.nick, u.email, u.telefono "
                . " FROM GestionResiBundle:Usuario u   "
                . " WHERE u.nick = :username";
        
        $query = $manejador->createQuery($dql);
        $query->setParameter('username', $username);
        
        return $query->getResult();

    }
    
    public function findHabitacionesUsuario($user){
        $qb2=$this->getEntityManager()
                  ->createQueryBuilder()->select('u.dNI')
                  ->from('GestionResiBundle:Usuario', 'u')
                  ->where('u.nick = :user')
                  ->setParameter('user',$user)
                  ->getQuery()->getResult();
        //var_dump($qb2);
        $qb1=$this->getEntityManager()
                  ->createQueryBuilder()->select('co.CodHabitacion')
                  ->from('GestionResiBundle:Contrato', 'co')
                  ->where('co.DNIResidente = :d') 
                  ->setParameter('d',$qb2)
                  ->getQuery()->getResult();
        if($qb1 != null)
        {
            $glue=$qb1[0]["CodHabitacion"];
            if(sizeof($qb1)>=1){
                for ($i=1;$i<sizeof($qb1);$i++){
                    $glue=$glue.",".strval($qb1[$i]["CodHabitacion"]);
                }
            }
            if($qb1==null){return null;
            }else{  $qb=$this->getEntityManager()->createQueryBuilder();
                return $qb->select('h.CodHabitacion,h.Descripcion,h.TipoHabitacion,h.TarifaMes,co.fechaExpiracion')
                            ->from('GestionResiBundle:Habitacion','h')
                            ->join('GestionResiBundle:Contrato', 'co', 'WITH', 'co.CodHabitacion=h.CodHabitacion')
                            ->where($qb->expr()->in('h.CodHabitacion',$glue))
                            ->getQuery()->getResult(); 
            }
        
        }
    }
    
    public function findFacturasNoPagadas($user){
        $qb=$this->getEntityManager()->createQueryBuilder()
                 ->select('u.dNI')
                 ->from('GestionResiBundle:Usuario', 'u')
                 ->where('u.nick = :user')
                 ->setParameter('user',$user)
                 ->getQuery()->getResult();
        $qb1=$this->getEntityManager()->createQueryBuilder()
                  ->select('co.CodContrato')
                  ->from('GestionResiBundle:Contrato', 'co')
                  ->where('co.DNIResidente = :d') 
                  ->setParameter('d',$qb)
                  ->getQuery()->getResult();
        if($qb1 != null )
        {
            $glue=$qb1[0]["CodContrato"];
            if(sizeof($qb1)>=1){
                for ($i=1;$i<sizeof($qb1);$i++){
                    $glue=$glue.",".strval($qb1[$i]["CodContrato"]);
                }
            }
            if($qb1==null){return null;
            }else{$qb2=$this->getEntityManager()->createQueryBuilder();
                return $qb2->select('f.codFactura,f.fechaExpedicion,f.importe,con.CodHabitacion,con.fechaExpiracion')
                           ->from('GestionResiBundle:Factura','f')
                           ->join('GestionResiBundle:Contrato', 'con', 'WITH', 'con.CodContrato=f.codContrato')
                           ->where($qb2->expr()->in('f.codContrato',$glue))
                           ->andWhere($qb2->expr()->isNull('f.fechaPago'))
                           ->getQuery()->getResult();
            }
        }
        
    }
}