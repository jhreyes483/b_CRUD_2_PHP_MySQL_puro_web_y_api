<?php

class indexController extends Controller {
	public function __construct(){
      parent::__construct(1);
      $this->model            = $this->loadModel('userModel', 'SQL');
   }

	public function index(){
      if(isset($_POST['login']) ){
         $this->login();
      }
      $this->_view->setCss([ 'sign', 'styles' ]);
      $this->_view->setJs(['jquery.min','jquery.easing.min','all','bootstrap.bundle.min','contact_me','jqBootstrapValidation','scripts']);
      $this->_view->renderizar('index',1);
   }
   

   public function close(){
      session_destroy();
      $this->model->m_update(1,[ $_SESSION['usuario']['id'], '']);
      $this->redireccionar('index');
   }


   public function login(){
      $error = $this->m_validar(
         [ $_POST['email'], $_POST['password']],
         ['Correo', 'Contraseña' ]
      );
      if(!$error){

         include APP_CLASS.'c_encripta.php';
         $cond[0] = ' WHERE email = "'.$this->getSql('email').'"';
         $cond[1] = ' AND password = "'.c_encripta::hash($this->getSql('password')).'"';
         $USER    = $this->model->m_consulta(1,4, $cond );
   
         if(isset($USER) && !empty( $USER )){ 

            $token  = rand(1000000, 9000000);
            $this->model->m_update(1,[ $USER['id'], c_encripta::hash($token)] );
            $_SESSION['usuario']  = $USER;
            $_SESSION['msg']      = 'Bienvenido '.$_SESSION['usuario']['name'];
            $_SESSION['color']    = 'success';
         }else{
            $_SESSION['msg']     =  'Credenciales incorrectas';
            $_SESSION['color']   = 'danger';
         }
      }else{
         $_SESSION['msg']     =  $error;
         $_SESSION['color']   = 'danger';
      }
      $this->redireccionar('index');
   }

   
   public function registrar(){ 
      $this->_view->setCss([ 'sign', 'styles' ]);
      $this->_view->setJs(['jquery.min','jquery.easing.min','all','bootstrap.bundle.min','contact_me','jqBootstrapValidation','scripts']);
      $this->_view->renderizar('register',1);  
   }


   public function store(){ 
      $error = $this->m_validar(
         [ $_POST['name'], $_POST['email'], $_POST['password']],
         [ 'Nombre', 'Correo', 'Contraseña' ]
      );
      if( !$error ){
         include APP_CLASS.'c_encripta.php';
         $b = $this->model->m_insert(1,[$this->getSql('name'), $this->getSql('email'),c_encripta::hash($this->getSql('password')) ]); // inserta user
         $_SESSION['msg']     = 'registro de manera exitosa';
         $_SESSION['color']   = 'success';
      }else{
         $_SESSION['msg']     =  $error;
         $_SESSION['color']   = 'danger';
      }
      $this->redireccionar('index');
   }



  




}
