<?php

namespace Resi\GestionResiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class HabitacionesController extends Controller{
    public function verHabitacionesAction(Request $request){
        //En caso de que el usuario ya esté en alguna habitación
        $usu=$request->getSession()->get('usu');
            $restriccionesFecha=new Collection(array(
                    'fechaIni' => new NotNull(),
                    'fechaFin' => new NotNull()
                    )
                );
            
            $minDate = date('Y');
            $maxDate = date('Y', strtotime('+5 years'));
            
            //echo $maxDate;
            
            $a = array();
            $a[] = strtotime($maxDate);
            
            $formFechas=$this->createFormBuilder(array('constraints' => $restriccionesFecha))
                             ->add('fechaIni','date',array('label'=>'Fecha de Entrada: ', 'years' => range($minDate, $maxDate)))
                             ->add('fechaFin','date',array('label'=>'Fecha de Salida: ', 'years' => range($minDate, $maxDate)))
                             ->getForm();
            if($request->getMethod()=='POST'){
                $formFechas->bind($request);
                if($formFechas->isValid()){
                    if($formFechas->get('fechaIni')->getData()<$formFechas->get('fechaFin')->getData() ){
                        //La fecha introducida es menor que la de hoy
                        if($formFechas->get('fechaIni')->getData() < new \DateTime('now')){
                            return $this->render("GestionResiBundle:Habitaciones:verHabitaciones.html.twig", 
                              array('formFechas' => $formFechas->createView(),
                                    'usu' => $usu,
                                    'fechaFin' => null,
                                    'fechaIni' => null,
                                    'errorFecha' => 2));
                        }
                    $request->getSession()->set('fechaIni', $formFechas->get('fechaIni')->getData());
                    $request->getSession()->set('fechaFin', $formFechas->get('fechaFin')->getData());
                    
                    //var_dump($habitaciones);
                    return $this->render('GestionResiBundle:Habitaciones:verHabitaciones.html.twig',array(
                                         'fechaIni' => $formFechas->get('fechaIni')->getData(),
                                         'fechaFin' => $formFechas->get('fechaFin')->getData(),
                                         'habitaciones'=>$this->getDoctrine()
                                                              ->getManager()
                                                              ->getRepository("GestionResiBundle:Habitacion")
                                                              ->findAllSinIdConFechas($formFechas->get('fechaIni')
                                                                                                 ->getData()),
                                         'usu' => $usu));
                    }else{
                    return $this->render("GestionResiBundle:Habitaciones:verHabitaciones.html.twig", 
                              array('formFechas' => $formFechas->createView(),
                                    'usu' => $usu,
                                    'fechaFin' => null,
                                    'fechaIni' => null,
                                    'errorFecha' => 1));
                    }
                } 
            }else {
                return $this->render("GestionResiBundle:Habitaciones:verHabitaciones.html.twig", 
                              array('formFechas' => $formFechas->createView(),
                                    'usu' => $usu,
                                    'fechaFin' => null,
                                    'fechaIni' => null,
                                    'errorFecha' => null));
        }
        
        return $this->render("GestionResiBundle:Habitaciones:verHabitaciones.html.twig", 
                              array('formFechas' => $formFechas->createView(),
                                    'usu' => $usu,
                                    'fechaFin' => null,
                                    'fechaIni' => null,
                                    'errorFecha' => null));
    }

    public function alquilarHabitacionAction($CodHabitacion, Request  $request){
        $usu=$request->getSession()->get('usu');
        
        if($usu==null){
            return $this->render('GestionResiBundle:Default:index.html.twig', 
                           array('sesionIniciado'=>'No ha iniciado sesion.','userNotFound'=>''));
        }else{
            //Calculamos estancia y precio:
                $fechaIni=$request->getSession()->get('fechaIni');
                $fechaFin=$request->getSession()->get('fechaFin');
                //$numMeses = 0;
                //$totalPrice = 0;
                
                if(($fechaIni == null)||($fechaFin == null)||($fechaIni>$fechaFin))
                {
                    echo 'Error de fechas';
                }
                //echo $fechaIni->format('Y-m-d H:i:s');
                $d1 = new DateTime($fechaIni->format('Y-m-d H:i:s'));
                $d2 = new DateTime($fechaFin->format('Y-m-d H:i:s'));

                //var_dump($d1->diff($d2)->m); // int(4)
                $numMeses = ($d1->diff($d2)->m + ($d1->diff($d2)->y*12));
                if($numMeses==0){
                    $numMeses=1;
                }
                //var_dump($numMeses);
                
                $habitacion = $this->getDoctrine()
                                         ->getManager()
                                         ->getRepository("GestionResiBundle:Habitacion")
                                         ->findHabitacionCod($CodHabitacion);
                
                foreach($habitacion as $hab)
                {
                    //echo $hab['TarifaMes'];
                    $price = $hab['TarifaMes'];
                }
                $totalPrice = (int)$numMeses * (int)$price;
                $request->getSession()->set('totalPrice', $totalPrice);
            
            //Enviamos a confirmación para el alquiler
            return $this->render("GestionResiBundle:Habitaciones:alquilarHabitacion.html.twig",
                array('habitacion'=>$this->getDoctrine()
                                         ->getManager()
                                         ->getRepository("GestionResiBundle:Habitacion")
                                         ->findHabitacionCod($CodHabitacion),
                       'usuario'=>$this->getDoctrine()
                                       ->getManager()
                                       ->getRepository("GestionResiBundle:Usuario")
                                       ->findByUserSinId($usu),
                    'estanciaIni'=>$fechaIni,
                    'estanciaFin'=>$fechaFin,
                    'numMeses'=>$numMeses,
                    'totalPrice'=>$totalPrice));
        }
    }

}
