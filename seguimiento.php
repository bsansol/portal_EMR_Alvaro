<?php
  require 'session.php';

  require 'database.php';
  $message = '';
  $encontrado = false;
  if(isset($_POST['submitseguimiento'])){
    if (($_SESSION['user_rol'])=="Administrador"){
      $sql = 'SELECT * FROM seguimientoemr';
      $records = $conn->prepare($sql);
      $records->execute();
    }
    elseif (($_SESSION['user_rol'])=="Usuario") {
      $sql = 'SELECT * FROM seguimientoemr WHERE AUDITOR = :Auditor';
      $records = $conn->prepare($sql);
      $records->bindParam(':Auditor', $_SESSION['user_name']);
      $records->execute();
    }
  }
  if(isset($_POST['submitAA'])){
    $sql = 'SELECT * FROM seguimientoemr WHERE ID_AA = :AA';
    $records = $conn->prepare($sql);
    $records->bindParam(':AA', $_POST['AA']);
    $records->execute();
    if ($records->rowCount() == 0 ){
      $message = 'No hay ninguna AA con el código '.$_POST['AA'].'.';
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

    <link rel="stylesheet" href="assets/css/seguimiento.css">

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

      <h1>Buscar AA</h1>

      <form action="seguimiento.php" method="POST">
        <input id="AA" name="AA" type="text" placeholder="Introduce la AA que quieres buscar">
        <input type="submit" name="submitAA" value="Buscar AA">
      </form>

      ó
      <p>
      <form action="seguimiento.php" method="POST">
        <input type="submit" name="submitseguimiento" value="Cargar seguimiento">
      </form>
      <?php if(isset($_POST['submitseguimiento']) || isset($_POST['submitAA'])) {?>
      <table>
        <h1>Resultado de la búsqueda</h1><br/>
        <tr>
          <th><label for='"id"'>id: </label></th>
          <th><label for="ID_AA">ID AA: </label></th>
          <th><label for="ID_Job_Sol-Pro">ID Job Sol-Pro: </label></th>
          <th><label for="Order_N">Order N: </label></th>
          <th><label for="Dias_Aviso">Días Aviso: </label></th>
          <th><label for="PRIORIDAD">PRIORIDAD: </label></th>
          <th><label for="APLICA_EMR">APLICA EMR: </label></th>
          <th><label for="Tecnologias">Tecnologías: </label></th>
          <th><label for="N_EMRs">N EMRs: </label></th>
          <th><label for="W_realización">W realización: </label></th>
          <th><label for="FECHA_REALIZACION">FECHA_REALIZACION: </label></th>
          <th><label for="RTI_DT">RTI_DT: </label></th>
          <th><label for="FECHA_EMR">FECHA_EMR: </label></th>
          <th><label for="EMR_INV">EMR_INV: </label></th>
          <th><label for="FECHA_BQA">FECHA_BQA: </label></th>
          <th><label for="BQA_INV">BQA_INV: </label></th>
          <th><label for="AUDITOR">AUDITOR: </label></th>
          <th><label for="Site_Code">Side code: </label></th>
          <th><label for="Network_Element">Network Element: </label></th>
          <th><label for="Vendor/Ingenieria">Vendor/Ingeniería: </label></th>
          <th><label for="Date_AA">Date AA: </label></th>
          <th><label for="CADUCIDAD">CADUCIDAD: </label></th>
          <th><label for="ZONA">ZONA: </label></th>
          <th><label for="UBICACION">Ubicación: </label></th>
          <th><label for="COMENTARIO">COMENTARIO: </label></th>
          <th><label for="AIE2">AIE2 - Entregables: </label></th>
          <th><label for="AIE3">AIE3 - Documentación: </label></th>
          <th><label for="AIE4">AIE4 - EMR: </label></th>
          <th><label for="AIE4">AIE5 - EMR: </label></th>
          <th><label for="AIE4">AIE6 - EMR: </label></th>
          <th><label for="AIE4">AIE7 - EMR: </label></th>
          <th><label for="AIE4">AIE8 - EMR: </label></th>
          <th><label for="AIE4">AIE9 - EMR: </label></th>
          <th><label for="Errores">Nº Errores: </label></th>
          <th><label for="submitupdate">a</label></th>
        </tr>

        <?php while ($results = $records->fetch(PDO::FETCH_ASSOC)){
          $diasAviso	= (strtotime(date_format(date_create_from_format('d/m/Y', $results['Date AA']), 'Y-m-d'))-strtotime(date("Y-m-d")))/86400;
          $diasAviso 	= abs($diasAviso);
          $diasAviso = -floor($diasAviso);
          ?>
        <tr>
          <form action="seguimiento.php" method="POST" class="row justify-content-center">
            <th><input name="id" type="text" value="<?= htmlspecialchars($results['id'])?>" readonly style="background-color:#C7C7C7;"></th>
            <th><input name="ID_AA" type="text" value="<?= htmlspecialchars($results['ID_AA'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="ID_Job_Sol-Pro" type="text" value="<?= htmlspecialchars($results['ID Job Sol-Pro'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="Order_N" type="text" value="<?= htmlspecialchars($results['Order N'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="Dias_Aviso" type="text" value="<?= htmlspecialchars($diasAviso)?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="PRIORIDAD" type="text" value="<?= htmlspecialchars($results['PRIORIDAD'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="APLICA_EMR" type="text" value="<?= htmlspecialchars($results['APLICA EMR'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="Tecnologias" type="text" value="<?= htmlspecialchars($results['Tecnologías'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="N_EMRs" type="text" value="<?= htmlspecialchars($results['N EMRs'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="W_realización" type="text" value="<?= htmlspecialchars($results['W realización'])?>"></th>
            <th><input name="FECHA_REALIZACION" type="text" value="<?= htmlspecialchars($results['FECHA_REALIZACION'])?>"></th>
            <th><input name="RTI_DT" type="text" value="<?= htmlspecialchars($results['RTI_DT'])?>"></th>
            <th><input name="FECHA_EMR" type="text" value="<?= htmlspecialchars($results['FECHA_EMR'])?>"></th>
            <th><input name="EMR_INV" type="text" value="<?= htmlspecialchars($results['EMR_INV'])?>"></th>
            <th><input name="FECHA_BQA" type="text" value="<?= htmlspecialchars($results['FECHA_BQA'])?>"></th>
            <th><input name="BQA_INV" type="text" value="<?= htmlspecialchars($results['BQA_INV'])?>"></th>
            <th><input name="AUDITOR" type="text" value="<?= htmlspecialchars($results['AUDITOR'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="Site_Code" type="text" value="<?= htmlspecialchars($results['Site Code'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="Network_Element" type="text" value="<?= htmlspecialchars($results['Network Element'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="Vendor/Ingenieria" type="text" value="<?= htmlspecialchars($results['Vendor/Ingeniería'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="Date_AA" type="text" value="<?= htmlspecialchars($results['Date AA'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="CADUCIDAD" type="text" value="<?= htmlspecialchars($results['CADUCIDAD'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="ZONA" type="text" value="<?= htmlspecialchars($results['ZONA'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="UBICACION" type="text" value="<?= htmlspecialchars($results['UBICACION'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <th><input name="COMENTARIO" type="text" value="<?= htmlspecialchars($results['COMENTARIO'])?>"></th>
            <th><input name="AIE2" type="text" value="<?= htmlspecialchars($results['AIE2 - CAP/FSC'])?>"></th>
            <th><input name="AIE3" type="text" value="<?= htmlspecialchars($results['AIE3 - EMR'])?>"></th>
            <th><input name="AIE4" type="text" value="<?= htmlspecialchars($results['AIE4 - EMR'])?>"></th>
            <th><input name="AIE5" type="text" value="<?= htmlspecialchars($results['AIE5 - EMR'])?>"></th>
            <th><input name="AIE6" type="text" value="<?= htmlspecialchars($results['AIE6 - EMR'])?>"></th>
            <th><input name="AIE7" type="text" value="<?= htmlspecialchars($results['AIE7 - EMR'])?>"></th>
            <th><input name="AIE8" type="text" value="<?= htmlspecialchars($results['AIE8 - EMR'])?>"></th>
            <th><input name="AIE9" type="text" value="<?= htmlspecialchars($results['AIE9 - EMR'])?>"></th>
            <th><input name="Errores" type="text" value="<?= htmlspecialchars($results['Nº Errores'])?>" <?php if (($_SESSION['user_rol'])!="Administrador"){ ?> readonly style="background-color:#C7C7C7;"<?php }; ?>></th>
            <?php if(!isset($_POST['submitseguimiento'])) { ?>
                <th><label for="submitupdate"></label><input type="submit" name="submitupdate" value="Actualizar usuario"></th>
            <?php }; ?>
          </tr>
          <?php }; ?>
          </table>
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
