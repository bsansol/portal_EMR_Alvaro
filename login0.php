<?php

  session_start();

  if (isset($_SESSION['user_name'])) {
    header('Location: /main.php');
  }
  require 'database.php';
  $message = '';
  if(isset($_POST['submitenter'])){
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
      $sql = 'SELECT user, login, rol FROM users WHERE login = :login and password = :password';
      $records = $conn->prepare($sql);
      $records->bindParam(':login', $_POST['username']);
      $salt = "m#yvA*Q4";
      $hash = hash('sha512',hash('sha512',hash('sha512',$salt).$_POST['password']));
      $records->bindParam(':password', $hash);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      if ($records->rowCount() == 1 ){
          $_SESSION['user_name'] = $results['user'];
          $_SESSION['user_login'] = $results['login'];
          $_SESSION['user_rol'] =  $results['rol'];
          header("Location: main.php");
      } else {
        $message = 'Lo siento, los datos son incorrectos';
      }
    }
    else{
      $message = 'Por favor, introduce usuario y contraseña';
    }
  }
  if(isset($_POST['submitrecover'])){
    if (!empty($_POST['username'])) {
      $sql = 'SELECT id FROM users WHERE login = :login';
      $records = $conn->prepare($sql);
      $records->bindParam(':login', $_POST['username']);
      $records->execute();
      if ($records->rowCount() == 1 )
      {
        $sql = 'UPDATE users SET password= :password WHERE login = :login';
        $records = $conn->prepare($sql);
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = substr(str_shuffle($permitted_chars), 0, 10);
        $salt = "m#yvA*Q4";
        $hash = hash('sha512',hash('sha512',hash('sha512',$salt).$password));
        $records->bindParam(':password', $hash);
        $records->bindParam(':login', $_POST['username']);
        if ($records->execute()){
          $message = 'La nueva contraseña es '.  $password;
        }
        else{
          $message = 'Ha habido un problema al recuperar la contraseña.';
        }
      }
      else {
        $message = 'El usuario no existe.';
      }
    }
    else {
      $message = 'Instroduzca su nombre de usuario para recuperar la contraseña.';
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body  <?php if(!empty($message)): ?>onload="openDialog()"<?php endif; ?>>
    <?php require 'partials/header.php' ?>

    <h1>Acceso</h1>

    <form action="login.php" method="POST">
      <input name="username" type="text" placeholder="Introducide tu nombre de usuario">
      <input name="password" type="password" placeholder="Introduce tu contraseña">
      <input name="submitenter" type="submit" value="Acceder a la aplicación">
      <br/>
      <input name="submitrecover" type="submit" value="Recuperar contraseña">
    </form>
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
