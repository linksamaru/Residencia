<?php

namespace Resi\GestionResiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Resi\GestionResiBundle\Entity\Habitacion;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Regex;
class GestionAdminController extends Controller{
    public function gestionarHabitacionesAction(Request $req){
        $usu=$req->getSession()->get('usu');
        $habUsu = $this->getDoctrine()
                    ->getManager()
                    ->getRepository("GestionResiBundle:Habitacion")
                    ->findAllEInquilinoSinId();
        $habitaciones = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('GestionResiBundle:Habitacion')
                        ->findAllSinInquilinoYSinId();
        
        return $this->render("GestionResiBundle:GestionAdmin:gestionarHabitaciones.html.twig",
                array('HabUsu'=>$habUsu, 'Habitaciones' => $habitaciones));
                    
    }
    
    
    public function nuevaHabitacionAction(Request $req){
        $usu=$req->getSession()->get('usu');
        $res = new Collection(array('Descripcion' => new NotNull(),
                                    'Descripcion' => new Regex(array('pattern'=>"/^[a-zA-Z ]+$/", 
                                                                     'message'=>"Debe introducir una descripcion válida",
                                                                     'match'=> true)),
                                    'codhab' => new NotNull(),
                                    'Tipo' => new NotNull(),
                                    /*
                                    'Tipo' => new Regex(array('pattern'=>"/^[1-3]$/", 
                                                              'message'=>"Habitación de 1, 2 ó 3 personas",
                                                              'match'=> true)),
                                     * 
                                     */
                                    'Tarifa' =>new NotNull(),
                                    'Tarifa' => new Regex(array('pattern'=>"/^[0-9]+(\.[0-9]+|)$/", 
                                                                'message'=>"Debe introducir una tarifa de habitacion válida",
                                                                'match'=> true)),
                                    'path' => new NotNull()));
        $default=null;    
        $form = $this->createFormBuilder($default, array('constraints'=>$res))
                         ->add('Descripcion', 'textarea', array('label'=>'Descripcion de la habitacion: ','attr'=>array('cols'=>'10','rows'=>'4','max_length'=>'40')))
                         ->add('codhab', 'text', array('label'=>'Numero de la Habitacion: '))
                         ->add('Tipo','choice', array('choices'=>array('1'=>'individual', '2'=>'doble', '3'=>'triple')
                                                    , 'expanded'=>false,
                                                      'multiple'=>false ,
                                                      'label'=>'Tipo de habitación: '))
                         ->add('Tarifa','text', array('label'=>'Precio al mes del aquiler de la Habitacion: '))
                         ->add('path', 'file',array('label'=>'Añada el path de la imagen a mostrar: '))
                         ->getForm();
            $habitacion= new Habitacion();
            if($req->getMethod()=='POST'){
                $form->bind($req);
                if($form->isValid()&&$form->isSubmitted()&&!$this->getDoctrine()->getManager()
                                                                 ->getRepository("GestionResiBundle:Habitacion")
                                                                 ->findHabitacionCod($form->get('codhab')
                                                                                          ->getData())){
                    $habitacion->setCodhabitacion($form->get('codhab')->getData());
                    $habitacion->setDescripcion($form->get('Descripcion')->getData());
                    $habitacion->setTipohabitacion($form->get('Tipo')->getData());
                    //var_dump($form->get('Tipo')->getData());
                    $habitacion->setTarifames($form->get('Tarifa')->getData());
                    $habitacion->setNumDisponibles($form->get('Tipo')->getData());
                    
                    // $file stores the uploaded PDF file
                    /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                    $file = $form->get('path')->getData();
                    
                    
                    $directory = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/resi/imagesHabitaciones';
                    $extension = $file->guessExtension();
                    
                    $fileName = md5(uniqid()).'.'.$extension;
                    
                    $file->move($directory, $fileName);
                    
                    $habitacion->setPath('bundles/resi/imagesHabitaciones/'.$fileName);
                    
                    $em=$this->getDoctrine()->getManager();
                    $em->getRepository('GestionResiBundle:Habitacion');
                    $em->persist($habitacion);
                    $em->flush();
                    
                    //return $this->redirect($this->gestionarHabitacionesAction($req));
                    
                    /*
                    return $this->render("GestionResiBundle:GestionAdmin:gestionarHabitaciones.html.twig",
                      array('habitaciones'=>$this->getDoctrine()
                                                 ->getManager()
                                                 ->getRepository("GestionResiBundle:Habitacion")
                                                 ->findAllSinId()));
                     * 
                     */
                    return ($this->gestionarHabitacionesAction($req));
                }
            }else{
                return $this->render("GestionResiBundle:GestionAdmin:nuevaHabitacion.html.twig",array('form'=>$form->createView()));
            }
            
        return $this->render("GestionResiBundle:GestionAdmin:nuevaHabitacion.html.twig",array('form'=>$form->createView()));
    }

    public function gestionarFacturasAction(Request $req){
        $usu=$req->getSession()->get('usu');
        
        //Obtenemos las facturas por contrato, con los datos del usuario:
        $contratos = array();
        
        $listaContratos = $this->getDoctrine()->getManager()->getRepository('GestionResiBundle:Contrato')->findAllSinId();
        foreach($listaContratos as $c)
        {
            $contratoFacturas = array();
            $contratoFacturas[0] = $c; //Info de un contrato
            $contratoFacturas[1] = $this->getDoctrine()->getRepository('GestionResiBundle:Factura')
                    ->findFacturasByCodContrato($c['CodContrato']); //array de facturas
            
            $contratos[] = $contratoFacturas;
        }
        //var_dump($contratos);
        
        return $this->render("GestionResiBundle:GestionAdmin:gestionarFacturas.html.twig",
                      array('contratos'=>$contratos, 'hoy'=> date("Y-m-d")));
    }
    
    public function borrarFacturaAction($cod){
        $this->getDoctrine()->getManager()
             ->getRepository("GestionResiBundle:usuarioadmin")
             ->borrarFactura($cod);
        return $this->gestionarFacturasAction();
    }

    public function gestionarResidentesAction(){
        $req = $this->getRequest();
        $usu=$req->getSession()->get('usu');
        
        return $this->render('GestionResiBundle:GestionAdmin:gestionarResidentes.html.twig',
                      array('usuarios'=>$this->getDoctrine()
                                                 ->getManager()
                                                 ->getRepository('GestionResiBundle:Usuario')
                                                 ->findAllSinId(),
                            'usu'=>$this->getDoctrine()
                                        ->getRepository('GestionResiBundle:Usuario')
                                        ->findByUserSinId($usu)));
                    
    }
    
    public function darBajaResidenteAction($DNI){
        $req = $this->getRequest();
        
        //Comprobar que el residente no tiene facturas pendientes
        $facturasResidente = $this->getDoctrine()->getManager()->getRepository("GestionResiBundle:Factura")->findFacturassByDNIResidente($DNI);
        if($facturasResidente == null)
        {
            return $this->render('GestionResiBundle:GestionAdmin:confirmarBorrardoUsuario.html.twig', array("dniUsuVerPerfil"=>$DNI));
        }
            $flagTodasPagadas = 1;
            foreach($facturasResidente as $f)
            {
                //var_dump($f["fechaPago"]);
                if($f["fechaPago"] == null)
                {
                    //echo 'Hay una factura no pagada!';
                    $flagTodasPagadas = 0;
                    break;
                }
            }
        
            if($flagTodasPagadas == 1)
            {
                //Liberar la habitación. Todas las habitaciones cuyo contrato aún no haya expirado
                $contratosNoExpirados = array();
                
                $listaContratos = $this->getDoctrine()->getManager()
                        ->getRepository("GestionResiBundle:Contrato")
                        ->findByUsuarioDNI($DNI);
                foreach($listaContratos as $contrato)
                {
                    if(!($this->getDoctrine()->getManager()->getRepository("GestionResiBundle:Contrato")
                            ->checkExpiredDate($contrato["fechaExpiracion"])))
                    {
                         $contratosNoExpirados[] = $contrato;
                    }
                }
                
                
                   if($contratosNoExpirados == null)
                   {
                       //El usuario tiene todas las facturas pagadas y contratos expirados!
                       //SE PUEDE ELIMINAR
                            //echo 'Eliminamos';
                            return $this->render('GestionResiBundle:GestionAdmin:confirmarBorrardoUsuario.html.twig', array("dniUsuVerPerfil"=>$DNI));
                   }
                   else
                   {
                       //echo 'El usuario aún tiene algún contrato';
                       return $this->render('GestionResiBundle:GestionAdmin:errorResiConContratos.html.twig', array("dniUsuVerPerfil"=>$DNI));
                   }
                   /*
                    * 
                        $this->getDoctrine()->getManager()
                         ->getRepository("GestionResiBundle:Habitacion")
                         ->liberarHabitacion_1($contrato["codHabitacion"]);
                   */

            }
            else
            {
                return $this->render('GestionResiBundle:GestionAdmin:errorResiConFacturas.html.twig', array("dniUsuVerPerfil"=>$DNI));
            }
        
    }
    
    public function borrarHabitacionAction(Request $req){
        $COD = $req->get('COD');
        
        
        $this->getDoctrine()->getManager()
             ->getRepository("GestionResiBundle:usuarioadmin")
             ->borraHabitacion($COD);
        return $this->gestionarHabitacionesAction($req);
    }
    
    public function verPerfilUserAction($dni, Request $req)
    {
       //Enviamos la información completa del usuario al twig: InfoPerfil, InfoContratos, InfoFacturas
       $usuarioMostrar = $this->getDoctrine()->getManager()->getRepository("GestionResiBundle:Usuario")
                ->findUserByDNI($dni);
       
       $contratosUsuario = $this->getDoctrine()->getManager()->getRepository("GestionResiBundle:Contrato")
                ->findByUsuarioDNI($dni);
       
        $listaContratosFacturas = array();
        foreach ($contratosUsuario as $contrato)
        {
            $facturas = $this->getDoctrine()->getManager()->getRepository("GestionResiBundle:Factura")
                ->findFacturasByCodContrato($contrato['CodContrato']);
            $listaContratosFacturas[] = array($contrato, $facturas);
        }
        
        $fechaActual = new \DateTime("now");
        return $this->render('GestionResiBundle:GestionAdmin:mostrarUsuarioAAdmin.html.twig',
                array("datosUser"=>$usuarioMostrar , "fechaActual"=> $fechaActual, "userDNI"=>$dni, "listaContratosFacturas"=>$listaContratosFacturas));
    }
    
    public function borrarUsuarioConfirmedAction($dni)
    {
        $this->getRequest();
        $this->getDoctrine()->getManager()
                         ->getRepository("GestionResiBundle:usuarioadmin")
                         ->borraUser($dni);
                        return $this->gestionarResidentesAction();
    }
    
    public function desahuciarAction($dni)
    {
        //Obtenemos todos los contratos asociados al dni
            $listaContratos = $this->getDoctrine()->getRepository('GestionResiBundle:Contrato')
                ->findByUsuarioDNI($dni);
        //Aquellos con fecha de expiración > today, ponemos fecha de expiración a la de hoy, liberamos la habitacion
            if($listaContratos != null)
            {
                foreach($listaContratos as $contrato)
                {
                    if(!($this->getDoctrine()->getManager()
                             ->getRepository("GestionResiBundle:Contrato")
                             ->checkExpiredDate($contrato['fechaExpiracion'])))
                    {
                        //El contrato no ha expirado
                        $listaCodHabitaciones = $this->getDoctrine()->getManager()
                             ->getRepository("GestionResiBundle:Contrato")
                             ->findHabitacionByCodContrato($contrato['CodContrato']);
                        foreach($listaCodHabitaciones as $codHab)
                        {
                            //echo 'Se libera la habitacion de código: '.$codHab['CodHabitacion'];
                            $this->getDoctrine()->getManager()
                             ->getRepository("GestionResiBundle:Habitacion")
                             ->liberarHabitacion_1($codHab['CodHabitacion']);
                        }
                        
                    }
                }
            }
            else
            {
                //lista contratos es nula
                echo 'No hay contratos para este usuario';
                //return $this->gestionarResidentesAction();
            }
        //borramos usuario
        return $this->borrarUsuarioConfirmedAction($dni);
    }
    
    
    public function modificarHabitacionAction($COD)
    {
        return $this->gestionarHabitacionesAction($this->getRequest());
    }
}
