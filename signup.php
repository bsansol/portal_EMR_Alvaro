<?php
  require 'session.php';
  if (($_SESSION['user_rol'])!="Administrador"){
    header('Location: noacceso.php');
  }

  require 'database.php';
  $message = '';
  if(isset($_POST['submit'])){
    if (!empty($_POST['username']) && !empty($_POST['name'])) {
      $sql = 'SELECT id FROM users WHERE login = :login';
      $records = $conn->prepare($sql);
      $records->bindParam(':login', $_POST['username']);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);
      if ($records->rowCount() == 1 ){
        $message = 'Usuario ya existente.';
      }
      else{
        $sql = 'SELECT id FROM users WHERE user = :user';
        $records = $conn->prepare($sql);
        $records->bindParam(':user', $_POST['name']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($records->rowCount() == 1 ){
          $message = 'Usuario ya existente.';
        }
        else{
          $sql = "INSERT INTO users (user, login, password, rol) VALUES (:user, :login, :password, :rol)";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':user', $_POST['name']);
          $stmt->bindParam(':login', $_POST['username']);
          $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
          $password = substr(str_shuffle($permitted_chars), 0, 10);
          $salt = "m#yvA*Q4";
          $hash = hash('sha512',hash('sha512',hash('sha512',$salt).$password));
          $stmt->bindParam(':password', $hash);
          $stmt->bindParam(':rol', $_POST['rol']);
          if ($stmt->execute()) {
            $message = 'Usuario creado correctamente, la contraseña es '.$password;
          } else {
            $message = 'Lo siento, pero ha habido un error al crear el usuario';
          }
        }
      }
    }
    else {
      $message = "Falta introducir alguno de los dos parámetros.";
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
      <h1>Crear usuario</h1>

      <form action="signup.php" method="POST">
        <input name="username" type="text" placeholder="Introduce el usuario">
        <input name="name" type="text" placeholder="Introduce el un nombre">
      </label><select class="select-css" name="rol" placeholder="Selecciona un rol">
          <option>Administrador</option>
          <option>Usuario</option>
        </select>
        <input type="submit" name="submit" value="Crear usuario">
      </form>
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
