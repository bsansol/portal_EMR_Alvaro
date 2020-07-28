<?php
  require 'session.php';

  require 'database.php';
  $message = '';
  if(isset($_POST['submit'])){
    if (!empty($_POST['password']) && !empty($_POST['confirmpassword']) && !empty($_POST['newpassword'])) {
      If ($_POST['newpassword'] == $_POST['confirmnewpassword']){
        $sql = 'SELECT id FROM users WHERE login = :login and password = :password';
        $records = $conn->prepare($sql);
        $records->bindParam(':login', $_SESSION['user_login']);
        $salt = "m#yvA*Q4";
        $hash = hash('sha512',hash('sha512',hash('sha512',$salt).$_POST['password']));
        $records->bindParam(':password', $hash);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($records->rowCount() == 1 ){
            $sql = 'UPDATE users SET password= :password WHERE login = :login';
            $records = $conn->prepare($sql);
            $salt = "m#yvA*Q4";
            $hash = hash('sha512',hash('sha512',hash('sha512',$salt).$_POST['newpassword']));
            $records->bindParam(':password', $hash);
            $records->bindParam(':login', $_SESSION['user_login']);
            if ($records->execute()){
              $message = 'La contraseña se ha cambiado correctamente.';
            }
            else{
              $message = 'Ha habido un problema al cambiar la contraseña.';
            }
        }
        else{
          $message = 'Las contraseña actual es incorrecta.';
        }
      }
      else{
        $message = 'Las contraseñas no coinciden.';
      }
    }
    else {
      $message = 'Uno de los campos está vacio.';
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
      <div class="line"><label class="lnr lnr-list"><font>Seguimiento</font></label></div>
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
      <h1>Cambiar contraseña</h1>

      <form action="changepassword.php" method="POST">
        <input name="password" type="password" placeholder="Introduce tu actual contraseña">
        <input name="newpassword" type="password" placeholder="Introduce tu nueva contraseña">
        <input name="confirmnewpassword" type="password" placeholder="Confirma tu nueva contraseña">
        <input type="submit" name="submit" value="Cambiar contraseña">
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
