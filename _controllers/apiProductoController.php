<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; carset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class apiProductoController extends Controller{
    
    private $db;
    private $param;
    //
    public function __construct(){
        $this->model = $this->loadModel('productoModel', 'productoModel');
        require_once APP_CLASS.'logC/c_log.php';
        $log         = new Log( APP_LOGS.'api.log');

        if( isset($gC) &&  is_object(json_decode($gC))  ){
            $_DATA = get_object_vars(json_decode($gC));
            $log->m_salto_linea();
            $log->m_escribeLineaParams("P. Object", $_DATA);
            extract($_DATA);
         }
         
         if( isset($_POST) && count($_POST) > 0  ){
            $log->m_salto_linea();
            $log->m_escribeLineaParams("P. POST", $_POST);
            extract($_POST);
         }
   
         if(isset($_GET) && count($_GET) > 1 ){
            $log->m_salto_linea();
            $log->m_escribeLineaParams("P. GET", $_GET);
            extract($_GET);
         }



        if(isset($_POST['method'])){
            switch ($_POST['method']) {
                case 'index':
                    $this->index();
                    break;
                case 'edit':
                    $this->edit($this->getSql('id'));
                    break;
                case 'create':
                    $this->create();
                    break;
                case 'store':
                    $this->store();
                    break;
                case 'show':
                    $this->show($this->getSql('id'));
                    break;

                case 'destroy':
                    $this->destroy($this->getSql('id'));
                    break;

                case 'update':
                    $this->update();
                    break;
                
                default:
                    header('HTTP/1.0 404 Not Found');
                    die();
                    break;
            }
        }else{
            header('HTTP/1.0 404 Not Found');
            die();
        }


    }

    public function index(){
        $r = $this->model->m_consulta(1,4); // consulta productos
        $this->m_response_array($r,'No hay productos','lista de productos');
    }

    public function edit($id){
        $cond[0] = ' WHERE P.id_prod = "'.$id.'"';
        $cond[1] = ' LIMIT 1';
        $r = $this->model->m_consulta(1,6,$cond);
        $this->m_response_array($r,'Usuario no encontrado','Usario a editar');
    }

    public function create(){
        $cateModel  = $this->loadModel('categoriaModel', 'categoriaModel');
        $r          = $cateModel->m_consulta(1,2); // select de categorias
        $this->m_response_array($r,'No hay categorias','Lista de categorias');
    }

    public function store(){
        $error = $this->m_validar(
            [ $_POST['nom'], $_POST['stok'], $_POST['val'], $_POST['estado']], // required
            ['Producto', 'Stok', 'Valor', 'Estado' ], // msgs
           // [2,2,100,2] // valor maximo por cada caracter "opcional
        );

        if(!$error){
           $b = $this->model->m_insert(1, [$this->getSql('nom'), $this->getSql('val'), $this->getSql('stok'),  $this->getSql('estado'), $this->getSql('descript'), $this->getSql('img'), $this->getSql('categoria')  ]);
        }else{
            $this->m_response_error($error);
        }
         if($b && !$error){
            $this->m_response_true('Registro producto');
        }
    }

    public function update(){
        $error = $this->m_validar(
            [ $_POST['nom'], $_POST['stok'], $_POST['val'], $_POST['estado']],
            ['Nombre', 'Stok', 'Valor', 'Estado' ]
        );

        if(!$error){
            $b = $this->model->m_update(1, [
                $this->getSql('nom'), 
                $this->getSql('val'), 
                $this->getSql('stok'),  
                $this->getSql('estado'), 
                $this->getSql('descript'), 
                $this->getSql('img'), 
                $this->getSql('categoria'), 
                $this->getSql('id_prod')  
            ]);
        }else{
            $this->m_response_error($error);
        }
        if($b && !$error){
            $this->m_response_true('Actualizo producto');
        }else{
            $this->m_response_error('Error al actualizar');
        }
       // $this->redireccionar('producto');
    }

    public function show($id){
        $r = $this->model->m_consulta(1,6, [' WHERE id_prod ='.$id] ); // consulta productos
        $this->m_response_array($r,'No hay porductos','detalle productos');
    }

    public function destroy($id){
        $b = $this->model->m_delete($id);
        if($b){
            $this->m_response_true('Elimino producto');
        }else{
            $this->m_response_error('Error al eliminar producto');
        }
        $this->redireccionar('producto');
    }

 

   
}

?>