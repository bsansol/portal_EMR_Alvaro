<?php
  require 'session.php';
  if (($_SESSION['user_rol'])!="Administrador"){
    header('Location: noacceso.php');
  }

  require 'database.php';
  $message = '';
  $encontrado = false;
  if(isset($_POST['submitsearch'])){
    if (!empty($_POST['username'])) {
      $sql = 'SELECT user, login, rol FROM users WHERE login = :login';
      $records = $conn->prepare($sql);
      $records->bindParam(':login', $_POST['username']);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);
      if ($records->rowCount() == 1 ){
          $username = $results['login'];
          $name = $results['user'];
          $rol = $results['rol'];
          $encontrado = true;
          }
      else{
        $message = 'El usuario buscado no existe';
      }
    }
    else{
      $message = 'Por favor, introduce el login del usuario que quieres buscar.';
    }
  }
  if(isset($_POST['submitupdate'])){
    if (!empty($_POST['name']))
    {
     if (!empty($_POST['password'])) {
      $sql = 'UPDATE users SET user= :user, password = :password, rol = :rol WHERE login = :login';
      $records = $conn->prepare($sql);
      $records->bindParam(':user', $_POST['name']);
      $password = $_POST['password'];
      $salt = "m#yvA*Q4";
      $hash = hash('sha512',hash('sha512',hash('sha512',$salt).$password));
      $records->bindParam(':password', $hash);
      $records->bindParam(':rol', $_POST['rol']);
      $records->bindParam(':login', $_POST['username']);
      if ($records->execute()){
        $message = 'Los datos del usuario se han actualizado correctamente. La nueva contraseña es '.$password;
        $username = $_POST['username'];
        $name = $_POST['name'];
        $rol = $_POST['rol'];
        $encontrado = false;
      }
      else{
        $message = 'Ha habido un problema al cambiar los datos del usuario.';
      }
    }
    else {
      $sql = 'UPDATE users SET user= :user, rol = :rol WHERE login = :login';
      $records = $conn->prepare($sql);
      $records->bindParam(':user', $_POST['name']);
      $records->bindParam(':rol', $_POST['rol']);
      $records->bindParam(':login', $_POST['username']);
      if ($records->execute()){
        $message = 'Los datos del usuario se han actualizado correctamente.';
        $username = $_POST['username'];
        $name = $_POST['name'];
        $rol = $_POST['rol'];
        $encontrado = false;
      }
      else{
        $message = 'Ha habido un problema al cambiar los datos del usuario.';
      }
    }
    }
    else{
      $message = 'Por favor, uno de los campos está vacio.';
    }
  }

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
<body  <?php if(!empty($message)): ?>onload="openDialog()"<?php endif; ?>>
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
      <?php if(!$encontrado) { ?>
      <h1>Buscar Usuario</h1>

      <form action="modify.php" method="POST">
        <input name="username" type="text" placeholder="Introduce el usuario que quieres buscar">
        <input type="submit" name="submitsearch" value="Buscar usuario">
      </form>
      <?php }; ?>
      <?php if((isset($_POST['submitsearch']) || isset($_POST['submitupdate'])) && !empty($_POST['username']) && $encontrado) { ?>
      <h1>Usuario</h1>

      <form action="modify.php" method="POST">
        <laber for="username">Usuario: </label><input name="username" type="text" value=<?= $username?> readonly style="background-color:#C7C7C7;">
        <laber for="name">Nombre: </label><input name="name" type="text" value=<?= $name?>>
        <laber for="password">Contraseña: </label><input name="password" type="password" placeholder="Dejar vacio si no se quiere cambiar contraseña">
        <laber for="rol">Rol: </label><select class="select-css" name="rol" value=value=<?php $rol?>>
          <option>Administrador</option>
          <option>Usuario</option>
        </select>
        <input type="submit" name="submitupdate" value="Actualizar usuario">
      </form>
      <?php }; ?>
    </main>
    <div class="overlay" id="overlay">
    <div class="popup" id="popup">
      <a href="javascript.void(0)" id="btn-cerrar-popup" class="btn-cerrar-popup"><i class="fas fa-times"></i></a>
      <h4><?= $message ?></h4>
      <form action="">
        <input type="submit" class="btn-submit" value="Cerrar">
      </form>
    </div>
  </div>
  <script src="js/jquery.js"></script>
  <script src="js/popup.js"></script>
</body>
</html>
