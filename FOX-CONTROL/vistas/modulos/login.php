<div class="container mt-5 loginT">
<div class="login-box">
  <br><br>
  <div class="login-logo">
      <div class="centered">
          <img class="centeredh1" src="vistas/img/logo.png" alt="" width="25%">
          <h1 class="centeredh1">FOX CONTROL</h1>
          <p class="subtext">El control de la administración en tus manos</p>
          <button id="toggleBtn" class="btnIni btn btn-outline-light btn-lg">INICIAR SESIÓN</button>
      </div>
  </div>
  

  <div class="login-box-body transparente miDiv">

    <form method="post" role="form">

      <div class="form-group has-feedback">

        <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>

      </div>

      <div class="form-group has-feedback">

        <input type="password" class="form-control" placeholder="Contraseña" name="ingPassword" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      
      </div>

      <div class="row">
       
        <div class="col-xl-12 col-xs-12">

          <button type="submit" class="btn btn-outline-light btn-lg btn-block">Ingresar</button>
        
        </div>

      </div>

      <?php

        $login = new ControladorUsuarios();
        $login -> ctrIngresoUsuario();
        
      ?>

    </form>

  </div>

</div>

</div>
<br><br><br><br>
<hr>