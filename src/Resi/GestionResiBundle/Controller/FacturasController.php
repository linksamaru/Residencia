<?php

namespace Resi\GestionResiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Resi\GestionResiBundle\Entity\Factura;

class FacturasController extends Controller{
    public function verFacturaAction(Request $req, $codFact){
        $usu=$req->getSession()->get('usu');
        return $this->render('GestionResiBundle:Factura:verFactura.html.twig',
                      array('factura'=>$this->getDoctrine()
                                            ->getManager()
                                            ->getRepository('GestionResiBundle:Factura')
                                            ->findByCodFactura($codFact),
                            'usu'=>$this->getDoctrine()
                                        ->getManager()
                                        ->getRepository('GestionResiBundle:Usuario')
                                        ->findByUserSinId($usu)));
    }

    public function pagarFacturaAction(Request $req, $CodFactura){
        $usu=$req->getSession()->get('usu');
        $fac=$this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Factura')
                                               ->findAndUpdateByCodFactura($CodFactura);
        $hab=$this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Habitacion')
                                               ->findHabitacionByCodFact($CodFactura);
        $this->enviarNotificacionPagoAction($req, $fac, $hab);
        return $this->render('GestionResiBundle:Perfil:pagarFactura.html.twig',
                      array('factura'=>$fac,
                            'usu'=>$this->getDoctrine()
                                        ->getManager()
                                        ->getRepository('GestionResiBundle:Usuario')
                                        ->findByUserSinId($usu),
                            'hab'=>$hab));
    }

    public function generarFacturaAction(Request $req, $CodHabitacion){
        $usu=$req->getSession()->get('usu');
        
        //Alteramos en 1 el número de plazas disponibles para la habitación
        $this->getDoctrine()
                    ->getManager()
                    ->getRepository("GestionResiBundle:Habitacion")
                    ->updateNumDisponibles_Alquiler($CodHabitacion);
        //parte del contrato
        $dni=strval($this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Usuario')
                             ->findByUserSinId($usu)[0]["dNI"]);
        $contrato=$this->getDoctrine()->getRepository('GestionResiBundle:Contrato')
                       ->insertContrato($dni,$CodHabitacion);
        //parte de la factura
        $hab=$this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Habitacion')
                                         ->findHabitacionCod($CodHabitacion);
        $factura =new Factura();
        $factura->setCodContrato(strval($contrato[sizeof($contrato)-1]["CodContrato"]));
        $factura->setImporte(strval($this->getRequest()->getSession()->get('totalPrice')));
        $factura->setFechaExpedicion(new \DateTime('now'));
        $em1=$this->getDoctrine()->getManager();
        $em1->getRepository('GestionResiBundle:Factura');
        $em1->persist($factura);
        $em1->flush();
        $fac=$this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Factura')->findAllSinId();
        $fact=$fac[sizeof($fac)-1];
        $this->enviarMensajeFacturaAction($req, $fac, $contrato, $hab);
        return $this->render('GestionResiBundle:Facturas:verFactura.html.twig',
                      array('factura'=>$fact,
                            'user'=>$this->getDoctrine()
                                         ->getManager()
                                         ->getRepository('GestionResiBundle:Usuario')
                                         ->findByUserSinId($usu),
                            'habitacion'=>$hab));
    }

    public function enviarNotificacionPagoAction(Request $req,$fac,$hab){
        $usu=$req->getSession()->get('usu');
        $user=$this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Usuario')->findByUserSinId($usu);
        $titulo="Pago de Factura ".strval($fac[0]['codFactura'])." de la habitacion ".strval($hab[0]['CodHabitacion']);
        $mensaje="\tNumero: ".strval($fac[0]['codFactura'])."\n
            \tFecha de Expedicion: ".strval($fac[0]['fechaExpedicion']->format('d M Y'))."\n
            \tFecha de Pago: ".strval($fac[0]['fechaPago']->format('d M Y'))."\n
            \tImporte: ".strval($fac[0]['importe'])."\n
            \nPAGADA\n";
        $a=$a=strval($user[0]["email"]);        
        
        //return mail($a,$titulo,$mensaje,"de: Residencia Universitaria.");
    }

    public function enviarMensajeFacturaAction(Request $req,$fac,$con,$hab){
        $usu=$req->getSession()->get('usu');
        $user=$this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Usuario')->findByUserSinId($usu);
        $a=strval($user[0]["email"]);
        
        //Para mostrar fechas sin que se queje:
        //echo $fac[0]['fechaExpedicion']->format('d M Y');
        
        $titulo="Factura: ".strval($fac[0]["codFactura"])." por habitacion: ".strval($con[0]["CodHabitacion"]);
        $mensaje="\tNumero de Habitacion: ".strval($con[0]['CodHabitacion'])."\n
            \tNumero maximo de inquilinos en la habitacion: ".strval($hab[0]['TipoHabitacion'])." \n
            \tDescripcion de la habitacion: ".strval($hab[0]['Descripcion'])."\n
            \tNumero de Factura: ".strval($fac[0]['codFactura'])."\n
            \tFecha de Expedicion: ".strval($fac[0]['fechaExpedicion']->format('d M Y'))."\n
            \tImporte: ".strval($fac[0]['importe'])."\n";
        
        $headers =  'MIME-Version: 1.0' . "\r\n"; 
        $headers .= 'From: Residencia Universitaria' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        
        //return mail($a,$titulo,$mensaje,$headers);
        
        //return $this->redirect($this->generateUrl('ver_perfil'));
    }

}
