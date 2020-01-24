  noti.style.display='none';
	var str="";
  var mostrar=false;

	function view_message(cod) {
    spinner.style.display='none';
    btnLogin.disabled=false;
		var ico='<span class="lnr lnr-warning"></span>';
		switch (cod) {
			case 1:
				noti.innerHTML=ico+" Correo o Contraseña Incorrecta";
				break;
			case 2:
				noti.innerHTML=ico+ " Usuario Bloqueado";
				break;
			case 3:
				noti.innerHTML=ico+ " ERROR EN LA CONEXIÓN:<br>No se pudo conectar al Servidor";
        break;
      case 4:
				noti.innerHTML=ico+ " VERIFIQUE SUS DATOS";
				break;
			default:
				break;
		}
		noti.style.display='inline';
	}

	var app= new Vue({
		el: "#app",
		data: {
			mail: "",
			password: "",
      page: "",
      view_spinner: false
		},
		methods: {
			fetchData(){
			  this.nview_message();
        spinner.style.display='inline';
        btnLogin.disabled=true;
				$('#form').submit(function (e) {
					e.preventDefault();
				});
        
        var pattern=/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
        if((pattern.exec(this.mail))&&(this.password!="")){
          fetch('http://http://gdocuments.herokuapp.com/user/' + this.mail +'/'+ CryptoJS.SHA1(this.password) ).then(function(response) {
        			return response.json();
    			}).then(function(d) {
        			console.log('data = ', d);
					if(d.length==0){
						view_message(1);
						console.log("No autenticado");		
					}else{			
						if(d[0]["estado"].data[0]==1){
							spinner.style.display='none';
							btnLogin.disabled=false;
							if(d[0]["admin"].data[0]==1 && d[0]["Organizacion_idOrganizacion"]==0){
								console.log("Configuracion del sistema");
								option_page.value=1;	
							}else if(d[0]["Usuario_detalle_idUsuario"]==0){
								option_page.value=2;
							}
							else{
								option_page.value=3;
							}
							document.form.submit();
						}else{
							view_message(2);
							console.log("Acceso Bloqueado");
						}
					}
    
				}).catch(function(err) {
					view_message(3);
        			console.error(err);
				})
        }else{
          view_message(4);
          console.log("VALIDACION DE DATOS NULA");
        }
        
				
			},
			nview_message(){
				noti.style.display='none';
			}
		},
		computed: {
		}
	})