<header>
  <a href="/AuditoriasNae/index.php"><label class="pagina_inicio">Auditorias EMR</label></a>
  <?php if (isset($_SESSION['user_name'])) {
    echo "<br/>";
    echo "<label class='usuario'><h2>Bienvenido " .$_SESSION['user_name'] ."</h2></label>";
    $logout = "logout.php";
    echo "<a href='logout.php'>Cerrar sesión</a>";
  }
  ?>
</header>
