<?php 
    require_once "sesion.php";
    Session::init();    
    Session::destroy(); // Destruimos la sesión existente

    require_once "app/views/header.php";
?>
<main>
  <div class="container">
    <div class="alert alert-info" role="alert" style="margin-top: 100px;">
        <h1>Cerrando sesión...</h1>
    </div>
</main>

<meta http-equiv='refresh' content='2;URL=index.php'>