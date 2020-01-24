<?php
session_start();
if(!isset($_SESSION["email"])){
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
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="materialize/css/materialize.min.css"  media="screen,projection"/>
  <!--===============================================================================================-->
  <!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
  <!--===============================================================================================
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<input type="" class="datepicker" placeholder="Fecha de nacimiemto:" v-model="date">
  -->
  <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
  <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
  <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
  <!--===============================================================================================-->
</head>
<body>
	<div class="limiter" id="app">
		<div class="container-login100">
			<div class="wrap-login101 p-l-30 p-r-30 p-t-20 p-b-20" >
        <img src="images/img-06.png" alt="" id="imagen3">
        <span class="login100-form-title p-b-25">GESTIÓN DE DOCUMENTOS
					<div class="divider"></div>
				</span>

				<p class="fs-25 p-b-10 text-center" >{{title[step]}}</p>

				<div class="row" >
					<div class="login100-form validate-form col s12">
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

				<div class="container-login101-form-btn p-t-15 float-r">
					<a href="main.php">
						<button class="login100-form-btn waves-effect" @click="nextStep" v-if="active">GUARDAR</button>
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
	<script type="text/javascript" src="materialize/js/materialize.min.js"></script>
	<script src="js/jquery-3.3.1.min.js" ></script>
	<script type="text/javascript" src="js/initialize.js"></script>
  <!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
  <!--===============================================================================================-->

  <!-- production version, optimized for size and speed -->
	<script src="js/vue.js"></script>
	<script type="text/javascript" src="node_modules/axios/dist/axios.min.js"></script>

  <script type="text/javascript">
  
	var app= new Vue({
		el: "#app",
		data: {
			path: 'http://10.10.176.32:3333/',
			numEstructure: 0,
			step: 0,
			name: "",
			username: "",
			userlastname: "",
			numEmployment: null,
			mail: "<?php echo $_SESSION["email"] ?>",
			date: "",
			title: ["Es momento de completar tu información"]
		},
		methods: {
			nextStep(){
				if(this.activeNext()){
					this.step++;
				}
			},
			activeNext(){
				if((this.username!="")&&(this.userlastname!="")&&(this.mail!="")&&(this.numEmployment!=null)&&(this.numEmployment>=0)&&(this.numEmployment<=65535)&&(this.step==0)){
					this.save();
					return true;
				}
				return false;
			},
			save(){
				axios
				.get(this.path+'user/',
				{
          params:{
						num: this.numEmployment,
						name: this.username,
						lastname: this.userlastname,
						mail: this.mail
				}
				}).then(response => {
					if(response.data==0){
						alert("ERROR: VERIFIQUE LOS DATOS");	
					}else{
						console.log("Exito");
					}
        }).catch(function (error) {
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
		    if((this.username!="")&&(this.userlastname!="")&&(this.mail!="")&&(this.numEmployment!=null)&&(this.numEmployment>=0)&&(this.numEmployment<=65535)&&(this.step==0)){
					return true;
				}
				return false;
			}
		}
	})
	</script>

</body>
</html>