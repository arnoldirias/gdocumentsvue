<?php
session_start();
if(isset($_POST['email'])&&isset($_POST['option_page'])){
	
	$_SESSION["email"]=$_POST['email'];

	if($_POST['option_page']==2){
		echo "OK";
		header("Location: welcome_user.php");
	}else if($_POST['option_page']==3){
		echo "OK";
		header("Location: main.php");
	}
	
}else{
	session_destroy();
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Gestión de Documentos UNAH</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="public/images/icons/favicon.ico"/>
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="public/materialize/css/materialize.min.css"  media="screen,projection"/>
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="public/images/icons/favicon.ico"/>
<!--===============================================================================================
	<link rel="stylesheet" type="text/css" href="public/vendor/bootstrap/css/bootstrap.min.css">
	<input type="" class="datepicker" placeholder="Fecha de nacimiemto:" v-model="date">
-->
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
			<div class="wrap-login101 p-l-30 p-r-30 p-t-20 p-b-20" >
				<img src="public/images/img-06.png" alt="" id="imagen3">

				<span class="login100-form-title p-b-25">GESTIÓN DE DOCUMENTOS
					<div class="divider"></div>
				</span>

				<p class="fs-25 p-b-10 text-center" >{{title[step]}}</p>

				<div class="login100-form validate-form" v-if="step==0">

					<div class="input-field m-b-5">
						Tipo de Estructura Organizativa:
						<select v-model.number="numEstructure" id="selectEstructure" required>
						  <option value="0" selected disabled>Seleccione</option>
						  <option value="1">Rectoría</option>
						  <option value="2">Vicerrectoría</option>
						  <option value="3">Secretaría</option>
						  <option value="4">Secretaría Ejecutiva</option>
						  <option value="5">Dirección</option>
						  <option value="6">Facultad</option>
						  <option value="7">Departamento</option>
						  <option value="8">Unidad</option>
						  <option value="9">Otro</option>
						</select><br>
					  </div>

					<div class="input-field wrap-input100 validate-input m-b-10" data-validate = "Escriba un nombre">
							<input id="name" type="text" name="text" class="input100 validate" v-model="name" required>
							<label for="name" id="label-name">Nombre{{complemento[numEstructure]}}:</label>
					</div>
			</div>

			<div class="row" >
				<div class="login100-form validate-form col s12" :style="{display: mostrar_menu}">
					<div class="row">
								<div class="input-field col s6">
										<input id="num" type="number" name="num" class="validate" v-model.number="numEmployment" min="0" max="65535" maxlength="5" data-length="5" required>
										<label for="num" >Número de Empleado:</label>
										<span class="helper-text" data-error="Escriba un número válido" data-success=""></span>
								</div>
								<div class="input-field col s6">
										<input id="mail" type="email" name="mail" class="validate" v-model="mail" disabled readonly>
										<label class="active" for="mail" >Correo Electrónico:</label>
										<span class="helper-text" data-error="Escriba un correo válido" data-success=""></span>
								</div>
								<div class="input-field col s6">
										<input id="username" type="text" name="username" class="validate" v-model="username" required>
										<label for="username" >Nombre:</label>
								</div>
								<div class="input-field col s6">
										<input id="lastname" type="text" name="lastname" class="validate" v-model="userlastname" required>
										<label for="lastname" >Apellidos:</label>
								</div>		
						  </div>
					</div>
				</div>
			
			<div class="row" v-if="step==2">
				<div class="col s12">
					<div class="card">
						<div class="card-image">
							<img src="public/images/screenshot_user.png" alt="">
							<span class="card-title">Administrar Usuarios</span>
						</div>
						<div class="card-content">
							<p id="text-card-content">En el menú de Administración del sistema vaya a la pestaña Usuarios,
								 desde ahí podrá agregar, eliminar y dar permisos a los Usuarios que utilizarán el sistema.</p>
						</div>
						<div class="card-action">
							<p>VER COMO</p>
						</div>
					</div>
				</div>
			</div>

			<div class="row" v-if="step==3">
					<div class="col s12">
						<div class="card">
							<div class="card-image">
								<img src="public/images/screenshot_docs.png" alt="">
								<span class="card-title">Documentos</span>
							</div>
							<div class="card-content">
								<p id="text-card-content">Los tipos de Documentos tienen un correlativo predefinido, 
									pero usted los podrá cambiar desde el menú de Administración en la pestaña Documentos.
									El sistema estará listo cuando configure sus Documentos. 
								</p>
							</div>
						</div>
					</div>
				</div>

				<div class="container-login101-form-btn p-t-15 float-r" v-if="step<3 && active">
					<button class="login100-form-btn waves-effect" @click="nextStep">Siguiente</button>
				</div>
				<div class="container-login101-form-btn p-t-15 float-r" v-if="step==3">
					<a href="main.php">
						<button class="login100-form-btn waves-effect" @click="nextStep">IR A DOCUMENTOS</button>
					</a>
				</div>
			</div>
		</div>

		<footer>
			<!-- Copyright -->
			<div class="footer-copyright text-center py-2">© 2019 Copyright:
				<a href="#">Arnold Irias</a>
			  </div>
		</footer>
	</div>

	<!--JavaScript at end of body for optimized loading-->
	<script type="text/javascript" src="public/materialize/js/materialize.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="public/js/jquery-3.3.1.min.js">x3C/script>')</script>
	<script type="text/javascript" src="public/js/initialize.js"></script>
<!--===============================================================================================-->

<!--===============================================================================================-->
	<script src="public/vendor/bootstrap/js/popper.js"></script>
	<script src="public/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="public/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->

<!-- production version, optimized for size and speed -->
	<script src="public/js/vue.js"></script>
	<script type="text/javascript" src="public/node_modules/axios/dist/axios.min.js"></script>


	<script type="text/javascript">
	
	//var mivariable = "<?php echo $_POST['email'] ?>";
	var app= new Vue({
		el: "#app",
		data: {
			path: 'http://gdocuments.herokuapp.com/',
			numEstructure: 0,
			step: 0,
			name: "",
			username: "",
			userlastname: "",
			numEmployment: null,
			mail: "<?php echo $_POST['email'] ?>",
			date: "",
			title: ["Bienvenido  ¡Configuremos el Sistema!",
					"Es momento de completar tu información",
					"Controlar el acceso al sistema",
					"Tipos de Documentos a Manejar"],
			complemento: ["","", " de la Vicerrectoría", " de la Secretaría"," de la Secretaría Ejecutiva", " de la Dirección", " de la Facultad", " del Departamento", " de la Unidad",""]
		},
		methods: {
			nextStep(){
				if(this.activeNext()){
					this.step++;
					if(this.step>0){
						$('.select-wrapper')[0].hidden=true;
					}
				}
			},
			activeNext(){
				if((this.numEstructure>0)&&(this.name!="")&&(this.step==0)){
					return true;
				}else if((this.username!="")&&(this.userlastname!="")&&(this.mail!="")&&(this.numEmployment!=null)&&(this.step==1)){
					this.save();
					return true;
				}else if(this.step==2){
					return true;
				}
				return false;
			},
			save(){
				axios
          		.get(this.path+'user-information/',
          		{
           		 	params:{
						numE: this.numEstructure,
						nameE: this.name,
						num: this.numEmployment,
						name: this.username,
						lastname: this.userlastname,
						mail: this.mail
					}
				})
				.then(response => {
					console.log(response);
					if(response.data==0){
						//view_message(1);
						alert("ERROR: VERIFIQUE LOS DATOS");	
					}else{
						console.log("Exito");
					}
          		})
          		.catch(function (error) {
					alert("ERROR: VERIFIQUE LOS DATOS");
            		console.log(error);
				})
			}
		},
		computed:{
			mostrar_menu(){
		var estado='none';
		if(this.step==1){
		  estado='inline';
		}else if(this.step==0 || this.step>1 ){
		  estado='none'
		}
		return estado;
	  },
	  active(){
				if((this.numEstructure>0)&&(this.name!="")&&(this.step==0)){
					return true;
				}else if((this.username!="")&&(this.userlastname!="")&&(this.mail!="")&&(this.numEmployment!=null)&&(this.numEmployment>=0)&&(this.numEmployment<=65535)&&(this.step==1)){
					return true;
				}else if(this.step==2){
					return true;
				}
				return false;
			}
		}
	})
	</script>

</body>
</html>