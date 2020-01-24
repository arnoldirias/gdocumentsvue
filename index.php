<!DOCTYPE html>
<html lang="en">
<head>
	<title>Gestión de Documentos UNAH</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="public/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="public/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="public/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="public/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="public/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="public/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="public/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="public/css/util.css">
	<link rel="stylesheet" type="text/css" href="public/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	<div class="limiter" id="app">
		<div class="container-login100">
			<div class="wrap-login100 p-l-20 p-r-20 p-t-20 p-b-20">

				<img src="public/images/img-06.png" alt="" id="imagen2">	
				<form class="login100-form validate-form" id="form" method="POST" action="welcome.php" name="form">

					<span class="login100-form-title p-b-25">INGRESAR A GESTIÓN DE DOCUMENTOS</span>
					<div id="noti" class="alert alert-danger text-center sizefull" role="alert"></div>
				
					<div class="wrap-input100 validate-input m-b-15" data-validate = "Escriba un correo electrónico válido">
						<input class="input100" type="email" name="email" placeholder="Correo Electrónico" v-model="mail" required @keyup.enter="btnLogin.click()" @click="nview_message">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<span class="lnr lnr-envelope"></span>
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-16" data-validate = "Este campo es obligatorio">
						<input class="input100" type="password" name="pass" placeholder="Contraseña" v-model="password" @keyup.enter="btnLogin.click()" @click="nview_message" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<span class="lnr lnr-lock"></span>
						</span>
					</div>

					<input id="option_page" name="option_page" type="text" style="display: none" readonly>

          <button id="btnLogin" class="container-login100-form-btn login100-form-btn p-t-15" type="button"  @click="fetchData">
            <span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="width: 2rem; height: 2rem;display: none;"></span>
            Iniciar Sesión
          </button>

					<div class="w-full p-t-50">
						<a class="txt1 bo1 hov1" href="#">¿Has olvidado tu contraseña?</a>
					</div>
					
				</form>
			</div>
		</div>

		<footer>
			<!-- Copyright -->
			<div class="footer-copyright text-center py-2">© 2019 Copyright:
				<a href="#">Arnold J. Irias</a>
        <p>Desarrollado para el Departamento de Bienes Nacionales</p>
        <p>UNAH</p>
		  	</div>
		</footer>
	</div>
	
<!--===============================================================================================-->	
	<script src="public/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="public/vendor/bootstrap/js/popper.js"></script>
	<script src="public/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="public/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	
<!-- production version, optimized for size and speed -->
	<script src="public/js/vue.js"></script>
	<script src="public/js/login.js"></script>
	<script src="public/js/core-min.js"></script>
	<script src="public/js/sha1-min.js"></script>
</body>
</html>