<?php

namespace Resi\GestionResiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;
use Resi\GestionResiBundle\Entity\Usuario;

class AccesoController extends Controller{
    public function AccederAction(Request $peticion){
        $sesion=$peticion->getSession();
        if($sesion->isStarted()){
            return $this->render('GestionResiBundle:Default:index.html.twig', 
                           array('sesionIniciado'=>'Ya ha iniciado sesion.'));
        }else{
        //Log-in
            $restricciones = new Collection(array(  'nombre_usuario' => new NotNull(),
                                                    'nombre_usuario' => new Regex(array(    'pattern'=>"/^[a-zA-Z ]{3,20}$/", 
                                                                                            'message'=>"Debe introducir un nombre válido, de 3 a 20 caracteres",
                                                                                            'match'=> true)),
                                                    'pass' => new NotNull()));
        //Creamos el formulario sin objeto Type
            $formLogIn = $this->createFormBuilder(array('constraints'=>$restricciones))
                              ->add('nombre_usuario', 'text',array('label'=>'Nombre de usuario'))
                              ->add('pass', 'password',      array('label'=>'Contraseña del usuario'))
                              ->getForm();
            if($peticion->getMethod() == 'POST'){
                $formLogIn->bind($peticion);
                if($formLogIn->isValid()){
                    //Consulta de si existe el usuario introducido como log-in
                    $manejador = $this->getDoctrine()->getEntityManager();
                    if($manejador->getRepository("GestionResiBundle:Usuario")
                                 ->usernameExists($formLogIn->get("nombre_usuario")->getData())){
                        
                        //Si el que logea es el administrador
                        if($manejador->getRepository("GestionResiBundle:usuarioadmin")
                                     ->findAdmin($manejador->getRepository("GestionResiBundle:Usuario")
                                                                           ->getDNIUser($formLogIn->get("nombre_usuario")
                                                                                                      ->getData())))
                        {
                            //la contraseña introducida es correcta del administrador
                            if($manejador->getRepository("GestionResiBundle:Usuario")
                                         ->validatePassword($formLogIn->get("pass")->getData()))
                            {
                                $peticion->getSession()->start();
                                //$peticion->getSession()->setName($$formLogIn->get('nombre_usuario')->getData());
                                $peticion->getSession()->set('usu', $formLogIn->get('nombre_usuario')->getData());
                                $peticion->getSession()->set('admin', 1);
                                
                                return $this->redirect($this->generateUrl("_ver_panel_control"));
                            }
                            else
                            {         
                                return $this->render('GestionResiBundle:Acceso:Acceder.html.twig', array('formLogIn' => $formLogIn->createView(), 'userNotFound' => 'Nombre de usuario o contraseña incorrectos'));
                            }
                            
                            
                        }else{
                            if($manejador->getRepository("GestionResiBundle:Usuario")
                                         ->validatePassword($formLogIn->get("pass")->getData())){
                                //El usuario existe, establecemos como variable de sesión
                               //$this->get('session')->set('loginUserId', $formLogIn->get("nombre_usuario")->getData());
                            $peticion->getSession()->start();
                            //$peticion->getSession()->setName($$formLogIn->get('nombre_usuario')->getData());
                            $peticion->getSession()->set('usu', $formLogIn->get('nombre_usuario')->getData());
                                return $this->redirect($this->generateUrl("ver_perfil"));
                            }
                        }
                            return $this->render('GestionResiBundle:Acceso:Acceder.html.twig', array('formLogIn' => $formLogIn->createView(), 'userNotFound' => 'Nombre de usuario o contraseña incorrectos'));
                    }else{
                            return $this->render('GestionResiBundle:Acceso:Acceder.html.twig', array('formLogIn' => $formLogIn->createView(), 'userNotFound' => 'No existe un usuario con ese nombre'));    
                    }
                }
            }
        }
            return $this->render('GestionResiBundle:Acceso:Acceder.html.twig', array('formLogIn' => $formLogIn->createView(), 'userNotFound' => null));
    }

    public function RegistroAction(Request $req){
        //Formulario para dar de alta un usuario
        $restricciones = new Collection (array('dni' => new NotNull,
                                               'dni' => new Regex(array(    'pattern'=>"/^[0-9]{8}[a-zA-Z]{1}$/", 
                                                                            'message'=>"Introduzca un DNI válido",
                                                                            'match'=> true)),
                                                'nick' => new NotNull,
                                                'nick' => new Regex(array(  'pattern'=>"/^[a-zA-Z ]{3,20}$/", 
                                                                            'message'=>"Debe introducir un nombre de usuario válido, de 3 a 20 caracteres",
                                                                            'match'=> true)),
                                                'contrasena' => new NotNull(),
                                                'contrasena' => new Regex(array(    'pattern'=>"/^[a-zA-Z0-9]{4,20}$/", 
                                                                                    'message'=>"Debe introducir una contraseña válida, de 4 a 20 caracteres",
                                                                                    'match'=> true)),
                                                'confirmContrasena' => new NotNull(),
            
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
                                                                                    'match'=> true))));
        
        
        $ususario= new Usuario();
        $default = array('dni'=> '');
        $form=$this->createFormBuilder($default, array('constraints'=>$restricciones))
                   ->add('dni','text',                  array('label'=>'DNI del usuario'))
                   ->add('nick','text',                 array('label'=>'Nombre de usuario'))
                   ->add('contrasena','password',       array('label'=>'Contraseña del usuario'))
                   ->add('confirmContrasena','password',array('label'=>'Confirme contraseña'))
                   ->add('nombre','text',               array('label'=>'Nombre real de usuario'))
                   ->add('apellidos','text',            array('label'=>'Apellidos del usuario'))
                   ->add('telefono','text',             array('label'=>'Telefono de usuario'))
                   ->add('email','email',               array('label'=>'Correo electronico'))
                   ->getForm();
        $form->handleRequest($req);
        if($form->isValid()&&$form->isSubmitted()&&!($this->getDoctrine()
                                                          ->getManager()
                                                          ->getRepository('GestionResiBundle:Usuario')
                                                          ->usernameExists($form->get('nick')->getData())))
        {
            
            //Comprobamos que no haya un NIF ni username repetido
            if($this->getDoctrine()->getRepository("GestionResiBundle:Usuario")->checkSimilarDNI($form->get('dni')->getData()))
            {
                $default = array('dni'=> 'introduce tu dni');
                return $this->render('GestionResiBundle:Acceso:Registro.html.twig',array('formRegistro'=>$form->createView(), 'unconfirmedPass' => 'Este DNI no se encuentra disponible. ¿Ya tiene una cuenta activa?'));
            }
            //Comprobamos que las contraseñas coinciden:
            if($form->get('contrasena')->getData() != $form->get('confirmContrasena')->getData())
            {
                return $this->render('GestionResiBundle:Acceso:Registro.html.twig',array('formRegistro'=>$form->createView(), 'unconfirmedPass' => 'La contraseña de validación no coincide con la contraseña del usuario'));
            }
            
            $ususario->setApellidos($form->get('apellidos')->getData());
            $ususario->setNombre($form->get('nombre')->getData());
            $ususario->setNick($form->get('nick')->getData());
            $ususario->setContrasena($form->get('contrasena')->getData());
            $ususario->setDNI($form->get('dni')->getData());
            $ususario->setTelefono($form->get('telefono')->getData());
            $ususario->setEmail($form->get('email')->getData());
            $ususario->setFechaRegistro(new \DateTime());
            
            $em=$this->getDoctrine()->getManager();
            $em->getRepository('GestionResiBundle:Usuario');
            $em->persist($ususario);
            $em->flush();
            //return $this->redirectToRoute('ver_perfil');//para version 3.2
            
            //Establecemos la sesión
                $this->get('session')->set('usu', $form->get('nick')->getData());
            
            return $this->redirect($this->generateUrl('ver_perfil'));
        }
        return $this->render('GestionResiBundle:Acceso:Registro.html.twig',array('formRegistro'=>$form->createView()));
        
    }
    
    public function DesconexionAction(Request $req){
        $req->getSession()->clear();
        return $this->render('GestionResiBundle:Default:index.html.twig', 
                           array('userNotFound'=>'Sesion cerrada correctamente.','sesionIniciado'=>null));
    }

}
