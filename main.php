<?php
  require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auditorias EMR Nae</title>
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/main.css">

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>
    <?php require 'partials/header.php' ?>
    <div class="menu">
      <div class="linemenu"><label class="lnr lnr-menu"><font>Menú</font></label></div>
      <a href="seguimiento.php"><div class="line"><label class="lnr lnr-list"><font>Seguimiento</font></label></div></a>
      <?php if (($_SESSION['user_rol'])=="Administrador"){ ?>
        <a href="signup.php"><div class="line"><label class="lnr lnr-plus-circle"><font>Crear usuario</font></label></div></a>
      <?php }; ?>
      <?php if (($_SESSION['user_rol'])=="Administrador"){ ?>
      <a href="modify.php"><div class="line"><label class="lnr lnr-pencil"><font>Modificar usuario</font></label></div></a>
      <?php }; ?>
      <?php if (($_SESSION['user_rol'])=="Administrador"){ ?>
      <a href="delete.php"><div class="line"><label class="lnr lnr-cross-circle"><font>Borrar usuario</font></label></div></a>
      <?php }; ?>
      <div class="line"><label class="lnr lnr-download"><font>Descargar reporte</font></label></div>
      <a href="changepassword.php"><div class="line"><label class="lnr lnr-sync"><font>Cambiar contraseña</font></label></div></a>
    </div>

    <main>
        <article>
            <h1>Página principal</h1>
        </article>
    </main>
</body>
</html>
