<?php

namespace Resi\GestionResiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class PerfilController extends Controller{
    public function actualizarPerfilAction(Request $req){
        $sesion=$req->getSession();
        $usu=$sesion->get('usu');
        $restricciones = new Collection 
                             (array(
                                    'nombre' => new NotNull(),
                                    'nombre' => new Regex(array(    'pattern'=>"/^[a-zA-Z ]{3,40}$/", 
                                                                    'message'=>"Debe introducir un nombre válido, de 3 a 40 caracteres",
                                                                    'match'=> true)),
                                    'apellidos' => new NotNull(),
                                    'apellidos' => new Regex(array(     'pattern'=>"/^[a-zA-Z ]{3,40}$/", 
                                                                        'message'=>"Debe introducir unos apellidos válidos, de 3 a 40 caracteres",
                                                                        'match'=> true)),
                                    'telefono' => new NotNull(),
                                    'telefono' => new Regex(array(      'pattern'=>"/^[0-9]{9}$/", 
                                                                        'message'=>"Debe introducir un número de teléfono válido",
                                                                        'match'=> true)),
                                    'email' => new NotNull(),
                                    'email' => new Regex(array(         'pattern'=>"/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", 
                                                                        'message'=>"Debe introducir un email válido",
                                                                        'match'=> true))
                                   )
                             );
        $defaultData = array(
            'nombre' => $this->getDoctrine()
                ->getManager()
                ->getRepository('GestionResiBundle:Usuario')
                ->getNombreUser($usu), 
            'apellidos' => $this->getDoctrine()
                ->getManager()
                ->getRepository('GestionResiBundle:Usuario')
                ->getApellidosUser($usu),
            'telefono' => $this->getDoctrine()
                ->getManager()
                ->getRepository('GestionResiBundle:Usuario')
                ->getTelefonoUser($usu), 
            'email' => $this->getDoctrine()
                ->getManager()
                ->getRepository('GestionResiBundle:Usuario')
                ->getEmailUser($usu),
            'dni' => $this->getDoctrine()->getManager()
                ->getRepository('GestionResiBundle:Usuario')
                ->getDNIUser($usu)[0]['dNI'],
            'nick' => $usu);
        $form=$this->createFormBuilder($defaultData, array('constraints'=>$restricciones))
                   ->add('nombre','text', array('label' => 'Nombre: '))
                   ->add('apellidos','text', array('label' => 'Apellidos: '))
                   ->add('telefono','text', array('label' => 'Teléfono: '))
                   ->add('email','email', array('label' => 'Email: '))
                   ->add('dni','text',array('label' => 'DNI: ', 'read_only' => true))
                   ->add('nick','text',array('label' => 'Nickname: ','read_only' => true))
                   ->getForm();
        $form->handleRequest($req);
        if($form->isValid()&&$form->isSubmitted()){
            $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
            $q = $qb->update('GestionResiBundle:Usuario', 'u')
                    ->set('u.nombre', '?1')
                    ->set('u.apellidos', '?2')
                    ->set('u.email', '?3')
                    ->set('u.telefono', '?4')
                    ->where('u.nick = ?5')
                        ->setParameter(1, $form->get('nombre')->getData())
                        ->setParameter(2, $form->get('apellidos')->getData())
                        ->setParameter(3, $form->get('email')->getData())
                        ->setParameter(4, $form->get('telefono')->getData())
                        ->setParameter(5, $usu)
                    ->getQuery();
            $q->execute(); 
            $this->getDoctrine()->getManager()->flush();
            //return $this->redirectToRoute('ver_perfil');//para version 3.2
            if($req->getSession()->get('admin')){
                    return $this->redirect($this->generateUrl('_ver_panel_control'));
                }else{
                    return $this->redirect($this->generateUrl('ver_perfil'));
                }
        }
        return $this->render('GestionResiBundle:Perfil:formUpdate.html.twig',array('form' => $form->createView(),
                                                                                   'admin' => $req->getSession()->get('admin')));
    }

    public function cambiarContrasenaAction(Request $req){
        $sesion=$req->getSession();
        $usu=$sesion->get('usu');
        $restricciones = new Collection (array('newContrasena' => new NotNull(),
                                               'oldContrasena' => new NotNull(),
                                               'confirmContrasena' => new NotNull(),
                                               'newContrasena' => new Regex(array(  'pattern'=>"/^[a-zA-Z0-9]{4,20}$/", 
                                                                                    'message'=>"Debe introducir una contraseña válida, de 4 a 20 caracteres",
                                                                                    'match'=> true))));
        $defaultData = null;
        $form=$this->createFormBuilder($defaultData, array('constraints'=>$restricciones))
                   ->add('oldContrasena','password', array('label' => 'Antigua contraseña: '))
                   ->add('newContrasena','password', array('label' => 'Nueva contraseña: '))
                   ->add('confirmContrasena','password', array('label' => 'Confirmar contraseña: '))
                   ->getForm();
        $form->handleRequest($req);
        $errores = array();
        if($form->isValid()&&$form->isSubmitted()){
            $errores = $this->getDoctrine()->getRepository("GestionResiBundle:Usuario")->validatePasswordTextErr($form->get("oldContrasena")->getData());
            if($form->get("newContrasena")->getData() != $form->get("confirmContrasena")->getData()){
                $errores = 'La nueva contraseña y la contraseña de confirmación no coinciden';
            }
            if($errores == ''){
                $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
                $q = $qb->update('GestionResiBundle:Usuario', 'u')
                    ->set('u.contrasena', '?1')
                    ->where('u.nick = ?2')
                        ->setParameter(1, $form->get('newContrasena')->getData())
                        ->setParameter(2, $usu)
                    ->getQuery();
                $q->execute();
                $this->getDoctrine()->getManager()->flush();
                if($req->getSession()->get('admin')){
                    return $this->redirect($this->generateUrl('_ver_panel_control'));
                }else{
                    return $this->redirect($this->generateUrl('ver_perfil'));
                }
            }  
        }
        return $this->render('GestionResiBundle:Perfil:formUpdatePass.html.twig',array('form'=>$form->createView(), 'errs'=>$errores,'admin'=>$req->getSession()->get('admin')));
    }
    
    public function verPerfilAction(Request $req){
        $sesion=$req->getSession();
        $usu=$sesion->get('usu');
        if(!$sesion->isStarted()){
            return $this->render('GestionResiBundle:Default:index.html.twig', 
                           array('sesionIniciado'=>'No ha iniciado sesion.','userNotFound'=>null));
        }else{
            $usu=$sesion->get('usu');
            
            
            return $this->render('GestionResiBundle:Perfil:verPerfil.html.twig',
                           array('user'=>$this->getDoctrine()
                                              ->getManager()
                                              ->getRepository('GestionResiBundle:Usuario')
                                              ->findByUserSinId($usu)));
        }
    }

    public function verHistorialHabitacionesAction(Request $req){
        $usu=$req->getSession()->get('usu');
        return $this->render('GestionResiBundle:Perfil:verHistorialHabitaciones.html.twig',
                      array('habitaciones'=>$this->getDoctrine()
                                                 ->getManager()
                                                 ->getRepository('GestionResiBundle:Usuario')
                                                 ->findHabitacionesUsuario($usu),
                            'usu'=>$this->getDoctrine()
                                        ->getRepository('GestionResiBundle:Usuario')
                                        ->findByUserSinId($usu),
                            'hoy'=> localtime()));
    }

    public function mostrarPagoPendienteAction(Request $req){
        $usu=$req->getSession()->get('usu');
        return $this->render('GestionResiBundle:Perfil:mostrarPagoPendiente.html.twig',
                      array('pagos'=>$this->getDoctrine()
                                          ->getManager()
                                          ->getRepository('GestionResiBundle:Usuario')
                                          ->findFacturasNoPagadas($usu),
                            'usu'=>$this->getDoctrine()
                                        ->getManager()
                                        ->getRepository('GestionResiBundle:Usuario')
                                        ->findByUserSinId($usu),
                            'hoy'=> localtime()));
    }

    public function verPanelDeControlAction(Request $req){
        $usu=$req->getSession()->get('usu');
        
        if((!$req->getSession()->isStarted()) || ($usu == null)){
            return $this->render('GestionResiBundle:Default:index.html.twig', 
                           array('sesionIniciado'=>'No ha iniciado sesion.','userNotFound'=>null));
        }else{

            return $this->render('GestionResiBundle:GestionAdmin:controlPanel.html.twig',
                           array('user'=>$this->getDoctrine()
                                              ->getManager()
                                              ->getRepository('GestionResiBundle:Usuario')
                                              ->findByUserSinId($usu)));
        }
    }
    
}
