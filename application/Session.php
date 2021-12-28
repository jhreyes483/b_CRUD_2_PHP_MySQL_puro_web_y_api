<?php
/*
 * 	Clase sesion para autenticar, adminsitrar seguridad, y niveles de acceso
 * 	
 *	
 */

class Session{

   public static function init(){
	//  @session_set_cookie_params(SESSION_TIME);
		if (!session_status() == PHP_SESSION_ACTIVE) {
			@session_start();
    	}
	}
      

   
  


	protected function redireccionar($ruta = false)
	{
	  if ($ruta) {
		header('location:' . BASE_URL . $ruta);
		exit;
	  } else {
		header('location:' . BASE_URL);
		exit;
	  }
	}

	// jav
	public function desencriptaSesion(){

	 }


	 public function verificarAcceso(){
			  die('perfiles no requeridos para prueba laika verificarAcceso '.__LINE__);
	 }
  

  public static function destroy($clave = false, $tipo = 0 ){
	switch ($tipo) {
		case 0:
			if($clave){
				if(is_array($clave)){
				   for($i=0; $i < count($clave); $i++){
					  if(isset($_SESSION[$clave[$i]])){
						 unset($_SESSION[$clave[$i]]);
					 }
				  }
				  }else{
					 if(isset($_SESSION[$clave])){
					  unset($_SESSION[$clave]);
					 }
				 }
			   }else{
				session_destroy();
			}
		break;
	}

	}

  public static function set($clave, $valor){
  	if(!empty ($clave))
    	$_SESSION[$clave] = $valor;
	}

  public static function get($clave){
   	if(isset($_SESSION[$clave]))
    	return $_SESSION[$clave];
  }

  public static function acceso($level){
  	if(!Session::get('autenticado')){
    	header('location:'.BASE_URL . 'error/access/5050');
      exit;
  	}
    Session::tiempo();
    if(!Session::getLevel($level) > Session::getLevel(Session::get('level'))){
    	header('location:'.BASE_URL . 'error/access/5050');
     	exit;
  	}
  }

  public static function accesoView($level){
  	if(!Session::get('autenticado')){
    	return false;
    }
    if(Session::getLevel($level) > Session::getLevel(Session::get('level'))){
    	return false;
    }
    return true;
  }

  public static function accesoEstricto(array $level, $noAdmin = false){
 		if(!Session::get('autenticado')){
    	header('location:'.BASE_URL . 'error/access/5050');
      exit;
    }
    if($noAdmin == false){
    	if(Session::get('level') == 'administrador'){
      	return;
  		}
    }
    if(count($level)){
  		if(in_array(Session::get($level), $level )){
      	return;
      }
    }
    header('location:'.BASE_URL . 'error/access/5050');
	}

	public static function accesoViewEstricto($level, $noAdmin = false){
  	if(!Session::get('autenticado')){
    	return false;
    }
    Session::tiempo();
    if($noAdmin == false){
    	if(Session::get('level') == 'administrador'){
      	return true;
    	}
		}
    if(count($level)){
    	if(in_array(Session::get($level), $level )){
      	return true;
      }
   	}
    return false;
 	}


}
