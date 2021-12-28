<?php
include_once 'mysql.php';

class productoModel extends c_SQL{
   public $db;
   public function __construct() {
      $this->db = new c_SQL;
   } 

   public function m_consulta($tipo, $obj = 0, $param=[]){ 
      switch ($tipo){
         case 1: // productos
            $conds = implode(' ',$param); 
            $sql   =
            'SELECT P.nom,  P.val, P.stok, P.estado, P.descript, P.img, P.created_at, P.updated_at, 
            C.nom_categoria, P.id_prod, P.estado, C.id_categoria
            FROM productos        P
            INNER JOIN categorias C USING(id_categoria) '.$conds;
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
            case 6:
               return $this->db->m_trae_array($sql,1)->row;
               break;
      }

   }


   public function m_insert($tipo, $param){
      switch ($tipo){
         case 1:
            $sql = 'INSERT INTO  productos (id_prod, nom, val, stok, estado , descript, img, updated_at ,created_at, id_categoria) 
            VALUES(NULL, "'.$param[0].'", "'.$param[1].'","'.$param[2].'","'.$param[3].'","'.$param[4].'","'.$param[5].'","'.date('Y-m-d H:i:s').'","'.date('Y-m-d H:i:s').'","'.$param[6].'")';
            break;
      }
      return $this->db->m_ejecuta($sql);
   }

   public function m_update($tipo, $param){
      switch ($tipo) {
         case 1:
            $sql = 
            'UPDATE productos 
            SET nom =  "'.$param[0].'", val = "'.$param[1].'", stok = "'.$param[2].'", estado = "'.$param[3].'", descript = "'.$param[4].'", img = "'.$param[5].'", id_categoria = "'.$param[6].'", updated_at = "'.date('Y-m-d H:i:s').'"
            WHERE id_prod = '.$param[7].'';
            break;
      }

      return $this->db->m_ejecuta($sql);
   }

   public function m_delete($id){
      $sql = 'DELETE FROM productos WHERE id_prod = '.$id;
      return $this->db->m_ejecuta($sql);
   }







}
?>