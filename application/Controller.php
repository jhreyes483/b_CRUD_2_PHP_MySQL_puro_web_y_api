<?php
/*  *********************************************************************
*   Desarrollado: Javier Reyes Neira         
*   Descripci�n: Controlador principal de nuestro framework
*   $tipo 0 = controlador que requere validacion de session activa para renderizar 
*   $tipo 1 = controlador de vista publica 
+   **********************************************************************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

abstract class Controller
{
protected $_view;
protected $_request;
protected $_tipo;

  public function __construct($public =null){
      date_default_timezone_set("America/Bogota");
      $this->_view                = new View(new Request);
      $this->_request             = new Request;
      if(!isset($public)){
        $this->issetSession();
      }
      $this->_view->setCss(array('ind'));

      $this->_view->titulo = APP_COMPANY . ':: Sistema inteligente de Gestion Empresarial';
      if (isset($_SESSION['usuario']) && count($_SESSION['usuario'])  != 0) {
      }
  }



  protected function m_validar($a, $ms, $leng = null, $tipoData = null ){
    $error = '';
    foreach($a as $i => $d) {
      if( ($d=='') ){ $error.= ', Favor llenar el campo '.$ms[$i].' ';}else{
        if( $leng    &&  $leng[$i] < strlen($d) ){ $error.=', Excede el maximo de caracteres para '.$ms[$i]; } 
      }
      
    }


    return ($error != ''? $error : false);
  }
  
  protected function m_verifica_result(Array $a,String $msgFalse) {
    if( count($a) != 0 ){
      $this->_view->data = ['status'=>'ok','msg'=>$a, ];
    }else{
      $this->_view->data = ['msg'=>$msgFalse, 'status'=>'error'];
    }
   
  }

  protected function m_response_array(Array $a,String $msgFalse, String $msgTrue) {
    if( count($a) != 0 ){
      $r = ['status'=>'ok','data'=>$a,'msg'=>$msgTrue ];
    }else{
      $r = ['status'=>'error','msg'=>$msgFalse ];
    }
    echo json_encode($r, JSON_UNESCAPED_UNICODE);
    die();
  }

  protected function m_response_true(String $msgTrue) {
    $r = ['status'=>'ok','msg'=>$msgTrue ];
    echo json_encode($r, JSON_UNESCAPED_UNICODE);
    die();
  }

  protected function m_response_error($msgFalse) {
    $r = ['status'=>'error','msg'=>$msgFalse ];
    echo json_encode($r, JSON_UNESCAPED_UNICODE);
    die();
  }


  abstract public function index();

  protected function loadModel($file, $clase = false, $param = ''){
    $clase = $clase !== false ? $clase : $file;
    $rutaModelo = ROOT . '_models/' . $file . '.php';
    //echo 'ruta modelo  -> ' . ROOT;

    if (is_readable($rutaModelo)) {
      //  die('Funcion modelo controller');
      require_once $rutaModelo;
      $modelo = new $clase($param);
      return $modelo;
    } else {
      throw new Exception('Error de modelo');
    }
  }

    
  public static function ver($dato, $sale = 0, $bg = 0, $tit = '', $float = false, $email = ''){
    switch ($bg) {
      case 1:
        $bgColor = 'b0ffff';
        break;
      case 2:
        $bgColor = 'd0ffb9';
        break;
      default:
        $bgColor = 'ffcfcd';
        break;
    }



    echo '<div style="background-color:#' . $bgColor . '; border:1px solid maroon;  margin:auto 5px; text-align:left;' . ($float ? ' float:left;' : '') . ' padding: 0 7px 7px 7px; border-radius:7px; margin-top:10px; ">';
    echo '<h2 style="padding: 5px 5px 5px 10px;	margin: 0 -7px; color: #FFF; background-color: #FF6F00; border-radius: 6px 6px 0 0; display:flex"><img src="/public/layout1/ico/debugging.png">&nbsp;Debugging for:&nbsp;&nbsp;<span style="color:black">' . $tit . '</span></h2>';
    if (is_array($dato) || is_object($dato)) {
      echo '<pre>';
      print_r($dato);
      echo '</pre>';
    } else {
      if (isset($dato)) {
        echo '<b>&raquo;&raquo;&raquo; DEBUG &laquo;&laquo;&laquo;</b><br><br>' . nl2br($dato);
      } else {
        echo 'LA VARIABLE NO EXISTE';
      }
    }
    echo '</div>';
    if ($sale == 1) die();
  }


  
  protected function verficaParametros($params){
    $avaible = true;
    $missingparams = '';
 
    foreach($params as $param){
       if(!isset($_POST[$param]) || strlen($_POST[$param]) <=0 ){
          $avaible = false;
          $missingparams = $missingparams.','.$param;
       }
    }
    // Si faltan parametros
    if(!$avaible){       
       die('Parametros:'.substr($missingparams, 1, strlen($missingparams)).'vacion');
    }
  }

  //limpia los string de codigo sql sanitizar la contrasena por post
  protected function getSqlSinEspacios($clave){
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      $_POST[$clave] = strip_tags($_POST[$clave]);
      return   str_replace(' ', '',  trim($_POST[$clave]));
    }
  }

  protected function redireccionar($ruta = false){
    if ($ruta) {
      header('location:' . BASE_URL . $ruta);
      exit;
    } else {
      header('location:' . BASE_URL);
      exit;
    }
  }

  protected function getLibrary($libreria){
    $rutaLibreria = ROOT . 'libs/' . $libreria . '.php';
    if (is_readable($rutaLibreria)) {
      return  $rutaLibreria;
    } else {
      throw new Exception('Error en la libreria');
    }
  }

  protected function getTexto($clave){
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
      return $_POST[$clave];
    }
    return '';
  }

  protected function getDate($clave){
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      return $_POST[$clave];
    }
    return '';
  }


  protected function getParamApi($param){
    if (isset($param) && !empty($param)) {
      $param = strip_tags($param);
      return trim($param);
    }
  }

  protected function getParam(){
    if(   count($this->_request->_parametros) != 0){
    return  explode(',', $this->_request->_parametros);
    }
  }

  //filtro para enteros enviados por url
  protected function filtrarInt($int){
    $int = (int) $int;
    if (is_int($int)) {
      return $int;
    } else {
      return 0;
    }
  }

  //filtra un entero enviado por post
  protected function getInt($clave){
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
      return $_POST[$clave];
    }
    return 0;
  }

  //limpia los string de codigo sql sanitizar la contrasena por post
  protected function getSql($clave){
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      $_POST[$clave] = strip_tags($_POST[$clave]);
      return trim($_POST[$clave]);
    }
  }

  //Sanitizar el nombre de usuario por el metodo post
  protected function getAlphaNum($clave){
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
      return trim($_POST[$clave]);
    }
  }

  // agrega slashes por si envian comillas dobles
  protected function agregaSlashes($texto){
    return addslashes($texto);
  }  

  protected function getJson($r){
    echo json_encode($r, JSON_UNESCAPED_UNICODE);
  }

  protected function issetSession(){
    if( !isset($_SESSION['usuario']) ){
      $this->redireccionar('error/iniciesesion');
    }
  }

  public function getSeguridad($token){
    if (!in_array($token, $_SESSION['t'])) {
      $this->redireccionar('error/permiso');
    }
  }




}
