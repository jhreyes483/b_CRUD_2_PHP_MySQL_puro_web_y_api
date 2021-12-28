<?php

// use Mpdf\Tag\Section;

class productoController extends Controller{
    private $db;
    private $param;
    //
    public function __construct(){
        $this->model = $this->loadModel('productoModel', 'productoModel');
        parent::__construct();
        if(isset($_POST['a'])){
            switch ($this->getSql('a')) {
     
                case 'edt':
                    $this->edit($this->getSql('id'));
                    exit;
                    break;
                case 'dlt':
                    $this->destroy($this->getSql('id'));
                    break;
            }
            
        }

        $this->_view->setCss(array('font-Montserrat', 'google', 'bootstrap.min', 'jav', 'animate', 'fontawesome-all'));
        $this->_view->setJs(['cUsuariosJquery']);
        $this->param = $this->getParam();
    }

    public function index(){
        $r = $this->model->m_consulta(1,0, [' ORDER BY nom ASC']); // consulta productos
        $this->m_verifica_result($r,'No hay porductos');
        $this->_view->setCss([ 'sign', 'styles' ]);
        $this->_view->setJs(['jquery.min','jquery.easing.min','all','bootstrap.bundle.min','contact_me','jqBootstrapValidation','scripts']);
        $this->_view->renderizar('index',1);
    }

    public function edit($id){
        $cond[0] = ' WHERE P.id_prod = "'.$id.'"';
        $cond[1] = ' LIMIT 1';
        $r = $this->model->m_consulta(1,5,$cond);
        if($r > 0){
            $modelCategoria = $this->loadModel('categoriaModel', 'categoriaModel');
            $this->_view->categorias = $modelCategoria->m_consulta(1,2);
        }
        $this->m_verifica_result($r,'Usuario no encontrado');
        $this->_view->setCss([ 'sign', 'styles' ]);
        $this->_view->setJs(['bootstrap.bundle.min']);
      //  $this->_view->renderizar('edit',1);
      $this->_view->renderizar('edit',1);
      die();
    }

    public function create(){
        $cateModel  = $this->loadModel('categoriaModel', 'categoriaModel');
        $this->_view->categorias = $cateModel->m_consulta(1,2); // select de categorias
        $this->_view->setCss([ 'sign', 'styles' ]);
        $this->_view->setJs(['bootstrap.bundle.min']);
        $this->_view->renderizar('create',1);
    }
    
    public function store(){
        $error = $this->m_validar(
            [ $_POST['nom'], $_POST['stok'], $_POST['val'], $_POST['estado']],
            ['Nombre', 'Stok', 'Valor', 'Estado' ]
        );
         if(!$error){
           $b = $this->model->m_insert(1, [$this->getSql('nom'), $this->getSql('val'), $this->getSql('stok'),  $this->getSql('estado'), $this->getSql('descript'), $this->getSql('img'), $this->getSql('categoria')  ]);
        }else{
            $_SESSION['msg']     =  $error;
            $_SESSION['color']   = 'danger';
            $this->redireccionar('producto');
        }
         if($b && !$error){
            $_SESSION['msg']     =  'Registro producto '.$this->getSql('nom');
            $_SESSION['color']   =  'success';
        }
        $this->redireccionar('producto');
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
            $_SESSION['msg']     =  $error;
            $_SESSION['color']   = 'danger';
        }
        if($b && !$error){
            $_SESSION['msg']     =  'Actualizo producto '.$this->getSql('nom');
            $_SESSION['color']   =  'success';
        }
        $this->redireccionar('producto');
    }

    public function destroy($id){
        $b = $this->model->m_delete($id);
        if($b){
            $_SESSION['msg']     =  'Elimino producto ';
            $_SESSION['color']   =  'danger';
        }else{
            $_SESSION['msg']     =  'Error al eliminar producto';
            $_SESSION['color']   =  'danger';
        }
        $this->redireccionar('producto');
    }





 

   
}