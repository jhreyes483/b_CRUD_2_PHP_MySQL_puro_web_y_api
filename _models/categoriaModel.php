<?php
//include_once 'class.conexion.php';
include_once 'mysql.php';

class categoriaModel extends c_SQL{
   public $db;
   public function __construct() {
      $this->db = new c_SQL;
   } 

   public function m_consulta($tipo, $obj = 0, $param=[]){ 
      switch ($tipo){
         case 1: // select categorias 
            $sql   =
            'SELECT id_categoria, nom_categoria
            FROM categorias';
            break;

         default:
         die('no selecciono tipo cons');
         break;
      }
      switch ($obj) {
         case 0:
            return $this->db->m_trae_array($sql)->rows;
            break;
         case 1;
            return $this->db->m_trae_array($sql);
            break;
         case 2:
            return $this->db->m_trae_array($sql,2);
            break;
         case 3:
            return $this->db->m_trae_array($sql)->rows;
            break;
         case 4:
            return $this->db->m_trae_array($sql,1)->rows;
            break;
         case 5:
            return $this->db->m_trae_array($sql)->row;
            break;
      }
   }






}
?>