<?php 

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
$rol[1]=  ['Administrador', ''.BASE_URL.'admin/'];
$rol[2]=  ['Cliente',       ''.BASE_URL.'cliente/'];
$rol[3]=  ['Empleado',      ''.BASE_URL.'empleado/'];  
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
   <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="<?= RUTAS_APP['ruta_img'] ?>logo/logo-amoblando.svg" alt="" /></a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
         Menu
         <i class="fas fa-bars ml-1"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
         <ul class="navbar-nav text-uppercase ml-auto">
           
  
         <li class="nav-item"><a class="nav-link js-scroll-trigger" href="<?=BASE_URL.'producto'?>">PRODUCTOS</a></li>

         <!-- 
               <li style="dusplay:none"  class="nav-item"><a class="nav-link js-scroll-trigger" href="<?=BASE_URL.'categoria'?>">CATEGORIAS</a></li>
          -->
      
         <li class="nav-item"><a class="nav-link js-scroll-trigger" href="<?=BASE_URL.'index/close'?>">CERRAR SESIÓN</a></li>


<?php if(isset($_SESSION['venta'])){
  ?> 
<li class="nav-item"><a class="nav-link js-scroll-trigger"  href="<?=BASE_URL.'index/carrito'?>"><i class="fa fa-shopping-cart text-success" title="Ver compras en carrito"></i></a></li>
<?php
}
?>




         </ul>
         
      </div>
   </div>
</nav>