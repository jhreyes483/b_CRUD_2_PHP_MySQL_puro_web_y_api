<?php
//include_once 'class.conexion.php';
include_once 'mysql.php';

class SQL extends c_SQL{
   public $db;
   public function __construct() {
      $this->db = new c_SQL;
   } 

   public function m_consulta($tipo, $obj = 0, $param=[]){
     // die('   ------>    metodo consulta---->    '.$tipo);
 
      switch ($tipo){
         case 1: // login 
            $conds = implode(' ', $param); 
            $sql ='SELECT id, name, email FROM users '.$conds;
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

         case 4:
            return $this->db->m_trae_array($sql,1)->row;
            break;
      }

      
   }

   public function m_insert($tipo, $param){
      switch ($tipo){
         case 1: // inserta usuario
            $sql = 
            'INSERT INTO users (id, name, email, email_verified_at, password, api_key_laika, created_at, updated_at) VALUES
            (NULL, "'.$param[0].'", "'.$param[1].'", NULL, "'.$param[2].'","", "'.date('Y-m-d H:i:s').'", "'.date('Y-m-d H:i:s').'")';
 ;
            break;
      }

      return $this->db->m_ejecuta($sql);
   }

   public function m_update($tipo, $param){
      switch ($tipo){
         case 1: // inserta usuario
            $sql = 
            'UPDATE users SET api_key_laika = "'.$param[1].'" WHERE id = '.$param[0];
            break;
      }

      return $this->db->m_ejecuta($sql);
   }







}
?>