var app = new Vue({
  el: '#app',
  data: {
    path: 'http://http://gdocuments.herokuapp.com/',
    message: '',
    indexTable: 0,
    tipoFiltro: 1,
    createPermision: false,
    isAdmin: false,
    editing: false,
    department: "",
    numDepartment: 0,
    departmentid: 0,
    selectedTag: 0,
    selectedTagAdmin: 0,
    selectedMoths: 0,
    year: 0,
    userid: 0,
    userid_detalle: 0,
    username: "",
    userlastname: "",
    short_username: "",
    numEmployment: 0,
    mail: myemail,
    date: "",
    activeProgressBar: false,
    activeTable: false,
    users: [],
    stateDocuments: 0,
    referencia: "",
    str: "",
    documents: [],
    typeDocuments: [],
    months : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    tags: [
      {index:1,name:"Corresp. Enviada",iconName:"assignment_turned_in", largeName:"Correspondencia Enviada",description:"Contiene la Correspondencia Enviada por este departamento a otras unidades."},
      {index:2,name:"Corresp. Recibida",iconName:"assignment_returned",largeName:"Correspondencia Recibida",description:"Contiene la Correspondencia Recibida de otras unidades."},
      {index:3,name:"Mi Correspondencia",iconName:"search",largeName:"Mi Correspondencia",description:"Contiene su Correspondencia Enviada y Recibida"},
      {index:4,name:"Reporte",iconName:"trending_up",largeName:"Reporte",description:"Datos estadísticos sobre los documentos generados"},
      {index:5,name:"Administración",iconName:"settings",largeName:"Administración del Sistema",description:"Permite gestionar Usuarios, Documentos, Actualizar Información y Cambiar Contraseña"}
      ],
    tagsName: ["Correspondencia Enviada","Correspondencia Recibida","Mi Correspondencia","Reporte"],
    filters:["Reciente","Por Año","Por Mes y Año","General","Categoría"],
    tagsAdmin: ["Mi Información","Usuarios","Documentos","Nombre de la Organización"],
    complemento: [" de la Organización"," de la Organización", " de la Vicerrectoría", " de la Secretaría"," de la Secretaría Ejecutiva", " de la Dirección", " de la Facultad", " del Departamento", " de la Unidad"," de la Organización"],
    correspondence: {
        dest_rem: "", affair:"", dest_proc:"", copy: false, attach:false, obs_attach: "", num_pages: 1, obs:"",
        ref: "", emit_date: "", received_date:"", address: [], response:false, response_ref:""
      },
    table:[],
    table2:[],
    table3:[],
    e: 10,
    p: [],
    a: 1,
    pswd:{
      last_password: "",
      new_password: "",
      repeat_password: "",
      mayus: false,
      c: false,
      num: false,
      n: false,
      correct: false
    },
    btnEdit: {btn1:0, btn2:0, btn3: 0},
    btnSelected: 0,
    dateDoc: ''
  },

  mounted () {
    this.activeProgressBar=true;  
    $('select').addClass('input-field');

    axios.get(this.path+'user/' + this.mail)
    .then(response => {
      this.userid=response.data[0][0]["idUsuario"];
      this.isAdmin=response.data[0][0]["admin"].data[0];
      this.mail=response.data[0][0]["correo"];
      this.short_username=response.data[0][0]["username"];
      this.departmentid=response.data[0][0]["Organizacion_idOrganizacion"];
      this.userid_detalle=response.data[0][0]["Usuario_detalle_idUsuario"];
            
      this.department=response.data[1][0]["nombre"];
      this.numDepartment=response.data[1][0]["Estructura_idEstructura"];
            
      this.username=response.data[2][0]["nombre"];
      this.userlastname=response.data[2][0]["apellido"];
      this.numEmployment=response.data[2][0]["num_empleado"];
      this.activeProgressBar=false;
    })
    .catch(function (error) {
      console.log(error);
      this.activeProgressBar=false;
    })
    .finally(function () {
      this.activeProgressBar=false;
      btn_filter.click();      
    });
    
  },
    
  methods:{
  changeTag(i){
    this.selectedTag=i;
  },
  searching() {
    if(this.str!=""){
      this.str=this.str.toUpperCase();
      var search=this.str.split(" ");
      var num=0;
      this.table3.forEach(el => {
        var estado=true;
        var estado2=true;
        var estado3=true;
        var estado4=true;
        var estado5=true;
        search.forEach(s => {
          if((el["asunto"].toUpperCase()).indexOf(s)==-1){
            estado=false;
          }
          if((el["destinatario"].toUpperCase()).indexOf(s)==-1){
            estado2=false;
          }
          if((el["destino"].toUpperCase()).indexOf(s)==-1){
            estado3=false;
          }
          if(((this.str_ref(el["correlativo"],el["referencia"],el["fecha_envio"])).toUpperCase()).indexOf(s)==-1){
            estado4=false;
          }
          if(((this.dateFormatString(el["fecha_envio"])).toUpperCase()).indexOf(s)==-1){
            estado5=false;
          }
        });

        if(estado || estado2 || estado3 || estado4 || estado5){
          if(num==0){
            this.table=[];
          }
          num=num+1;
          this.table.push(el);
        }
      });

      if(num==0){
        this.table=[];
      }

    }else{
      btn_filter.click();
    }
  },
  searching2(){
    this.table=this.table3;
    this.searching();
  },
  setTabAdmin(i){
    this.editing=false;
    this.selectedTagAdmin=i;
    if(i==1){
      this.activeProgressBar=true;
      axios.get(this.path+'users/' + this.departmentid)
      .then(response => {
        this.users=response.data;
        //console.log(this.users);
        this.activeProgressBar=false;    
      })
      .catch(function (error) {
        console.log(error.response);
        this.activeProgressBar=false;
      })
    }

    if(i==2){
      this.activeProgressBar=true;
      axios.get(this.path+'documents/' + this.departmentid)
      .then(response => {
        this.documents=response.data["data"];
        this.stateDocuments=response.data["estado"];
        //console.log(this.documents);
        this.activeProgressBar=false;
      })
      .catch(function (error) {
        console.log(error);
        this.activeProgressBar=false;
      })
    }
  },

  mostrar_table(){
    this.activeTable=true;
  },

  edit(i){
    if(i==1){
      username.disabled=false;
      lastname.disabled=false;
      num.disabled=false;
      //mail.disabled=false;
    }
    if(i==4){
      $("#name")[0].disabled=false;
    }
    this.editing=true;
  },

  save(i){
    this.editing=false;

    if(i==4){
      if(this.department!=""){
        axios.get(this.path+'name_department/', {params:{
          name: this.department.toUpperCase(),
          org: this.departmentid
        }})
        .then(response => {
          this.toastExito();
        })
        .catch(function (error) {
          this.toastError();
          console.log(error);
        })
    
      $("#name")[0].disabled=true;
          
      }else{
        this.toastVerificar();
        this.editing=true;
        this.edit(4);
      }
    }

    if(i==1){
      if((this.username!="")&&(this.userlastname!="")&&(this.numEmployment>=0)&&(this.numEmployment<=65535)){
        this.activeProgressBar=true;
        axios.get(this.path+'edit_user/',{params:{
          name: this.username.toUpperCase(), 
          lastname: this.userlastname.toUpperCase(), 
          id: this.userid, 
          num: this.numEmployment,
          mail: this.mail
        }})
        .then(response => {
          this.users=response.data;
          //console.log(this.users);
          this.activeProgressBar=false;
          this.toastExito();
        })
        .catch(function (error) {
          this.toastError();
          console.log(error);
          this.activeProgressBar=false;
        })

        username.disabled=true;
        lastname.disabled=true;
        num.disabled=true;
        mail.disabled=true;

      }else{
        this.toastVerificar();
        this.editing=true;
        this.edit(1);
      }
    }
  },

  toastExito(){
    this.activeProgressBar=false;
    M.Toast.dismissAll();
    M.toast({html: '<i class="material-icons prefix">check_circle</i> Cambios guardados satisfactoriamente', classes: 'rounded'});
  },
  toastError(){
    this.activeProgressBar=false;
    M.Toast.dismissAll();
    M.toast({html: '<i class="material-icons prefix">cancel</i> Se produjo un error', classes: 'rounded'});
  },
  toastVerificar(){
    this.activeProgressBar=false;
    M.Toast.dismissAll();
    M.toast({html: '<i class="material-icons prefix">error</i> Verifique sus datos y Vuelva a Intertarlo', classes: 'rounded'});
  },
  toastCargando(){
    M.Toast.dismissAll();
    M.toast({html: '<div class="preloader-wrapper small active"><div class="spinner-layer spinner-green-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> Cargando', classes: 'rounded'});
  },

  editDocument(pos){
    var ref = prompt("Introduzca la Referencia");
    if(ref){
      if(this.stateDocuments==0){
        axios.get(this.path+'newDocument/',{params:{
          org: this.departmentid, 
          type: this.documents[pos]["idTipoDeDocumento"], 
          idUser: this.userid, 
          ref: ref}
        })
        .then(response => {
          this.documents[pos]["referencia"]=ref;
          this.documents[pos]["nombre"]=this.documents[pos]["nombre"]+" ";
          this.toastExito();
        })
        .catch(function (error) {
          console.log(error);
          this.toastError();
        })

      }else{
        axios.get(this.path+'editDocument/',{params:{
          idRef: this.documents[pos]["idReferencia"], 
          ref: ref}
        })
        .then(response => {
          this.documents[pos]["referencia"]=ref;
          this.documents[pos]["nombre"]=this.documents[pos]["nombre"]+" ";
          this.toastExito();
        })
        .catch(function (error) {
          this.toastError();
          console.log(error);
        })
      } 
    }
  },

  activeSystem(){
    axios.get(this.path+'active/' + this.departmentid)
    .then(response => {})
    .catch(function (error) {
      console.log(error);
    })
  },

  modal(){
    axios.get(this.path+'typeDocuments/' + this.departmentid)
    .then(response => {
      this.typeDocuments=response.data;
    })
    .catch(function (error) {
      this.toastError();
      console.log(error);
    })
  },

  modal2(){
    axios.get(this.path+'typeDocuments2/' + this.departmentid)
    .then(response => {
      this.typeDocuments=response.data;
    })
    .catch(function (error) {
      this.toastError();
      console.log(error);
    })
  },
      
  addTypeDocument(){
    var ref = prompt("Introduzca el Nombre");
    if(ref){
      axios.get(this.path+'newTypeDocument/',{params:{
        org: this.departmentid, 
        ref: ref.toUpperCase()
      }})
      .then(response => {
        this.typeDocuments.push({idTipoDeDocumento: response.data[0], nombre: ref});
        this.toastExito();
      })
      .catch(function (error) {
        this.toastError();
        console.log(error);
      })
    }  
  },

  addNewDocument(){
    var pos=-1;
    var radioBottons=$(".with-gap");
    for (var index = 0; index < radioBottons.length; index++) {
      if(radioBottons[index].checked){
        pos=index;
      };  
    }
    if((this.referencia!="")&&(pos>-1)){
      axios.get(this.path+'newDocument/',{params:{
        org: this.departmentid, 
        type: this.typeDocuments[pos]["idTipoDeDocumento"], 
        idUser: this.userid, 
        ref: this.referencia}
      })
      .then(response => {
        this.setTabAdmin(2);
      })
      .catch(function (error) {
        console.log(error);
      })
    }else{
      this.toastVerificar();
    }      
  },

  addNewCorrespondence(){
    var pos;
    var radioBottons=$(".radio_botton_corr");
    for (var index = 0; index < radioBottons.length; index++) {
      if(radioBottons[index].checked){
        pos=index;
      };  
    }
    this.correspondence.dest_rem="";
    var destinatarios=$("div#destinatario.chips.chips-autocomplete.input-field")[0].M_Chips.$chips;
    for (var index = 0; index < destinatarios.length; index++) {
      this.correspondence.dest_rem+=destinatarios[index].firstChild.data;
      if(index+1<destinatarios.length){
        this.correspondence.dest_rem+=";";
      }
    }
   
    this.correspondence.dest_proc="";
    var destinos=$("div#destino2.chips.chips-autocomplete.input-field")[0].M_Chips.$chips;
    for (var index = 0; index < destinos.length; index++) {
      this.correspondence.dest_proc+=destinos[index].firstChild.data;
      if(index+1<destinos.length){
        this.correspondence.dest_proc+=";";
      }
    }
    
    if(pos>=0 && this.correspondence.dest_rem!="" && this.correspondence.dest_proc!="" && this.correspondence.affair!="" && this.correspondence.num_pages>0){
      this.correspondence.dest_rem.toUpperCase();
      this.correspondence.dest_proc.toUpperCase();
      this.correspondence.affair.toUpperCase();
      this.correspondence.obs.toUpperCase();
      this.correspondence.obs_attach.toUpperCase();
      this.activeProgressBar=true;
      //destinatario.disabled=true;
      asunto.disabled=true;
      //destino.disabled=true;
      obs.disabled=true;
      num_pages.disabled=true;
      copia.disabled=true;
      anexo.disabled=true;
      if (this.correspondence.attach){obs_anexo.disabled=true;}
      var radioBottons2=$(".radio_botton_corr");
      for (var index = 0; index < radioBottons2.length; index++) {
        radioBottons2[index].disabled=true;
      }
      M.toast({html: '<div class="preloader-wrapper small active"><div class="spinner-layer spinner-green-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> Guardando', classes: 'rounded'});
      
      axios.get(this.path+'newCorrespondenceE/',{params:{
        org: this.departmentid, 
        type: this.typeDocuments[pos]["idTipoDeDocumento"], 
        userid: this.userid, data: this.correspondence}
      })
      .then(response => {
        M.Toast.dismissAll();
        this.correspondence.response=true;
        //console.log(response);
        if(response.data.error){
          this.correspondence.response_ref=response.data.error;
          this.conservarInfo();
        }else if(response.data==0){
          this.correspondence.response_ref="Error Verifique los datos e intente nuevamente";
          this.conservarInfo();
        }else{
          this.toastExito();
          //console.log(response.data);
          this.correspondence.response_ref=response.data.nombre+" N° "+this.str_ref(response.data.ref, response.data.referencia, response.data.date);
        }
        this.activeProgressBar=false;
      })
      .catch(function (error) {
        this.toastError();
        console.log(error.response);
        this.activeProgressBar=false;
        M.toast({html: '<i class="material-icons prefix">error</i> Vuelva a Intertarlo', classes: 'rounded'});
      })
    }else{
      M.toast({html: '<i class="material-icons prefix">error</i> Complete todos los datos obligatorios', classes: 'rounded'});
    }
  },

  emptyModal(){
    this.correspondence.dest_rem="";
    this.correspondence.affair=undefined;
    this.correspondence.dest_proc="";
    this.correspondence.attach=false;
    this.correspondence.copy=false;
    this.correspondence.obs_attach="";
    this.correspondence.num_pages=1;
    this.correspondence.obs="";
    this.correspondence.ref="";
    this.correspondence.emit_date="";
    this.correspondence.address=[];
    this.correspondence.received_date="";
    this.correspondence.response=false;
    this.correspondence.response_ref="";

    // destinatario.disabled=false;
    asunto.disabled=false;
    //destino.disabled=false;
    obs.disabled=false;
    num_pages.disabled=false;
    copia.disabled=false;
    anexo.disabled=false;
    if (this.correspondence.attach){obs_anexo.disabled=true;}

    var radioBottons=$(".radio_botton_corr");
    for (var index = 0; index < radioBottons.length; index++) {
      radioBottons[index].disabled=false;
      if(radioBottons[index].checked){
        radioBottons[index].checked=false;
      };  
    }
    return true;
  },

  emptyModalReload(){
    this.correspondence.dest_rem="";
    this.correspondence.affair=undefined;
    this.correspondence.dest_proc="";
    this.correspondence.attach=false;
    this.correspondence.copy=false;
    this.correspondence.obs_attach="";
    this.correspondence.num_pages=1;
    this.correspondence.obs="";
    this.correspondence.ref="";
    this.correspondence.emit_date="";
    this.correspondence.address=[];
    this.correspondence.received_date="";
    this.correspondence.response=false;
    this.correspondence.response_ref="";

    // destinatario.disabled=false;
    asunto.disabled=false;
    //destino.disabled=false;
    obs.disabled=false;
    num_pages.disabled=false;
    copia.disabled=false;
    anexo.disabled=false;
    if (this.correspondence.attach){obs_anexo.disabled=true;}

    var radioBottons=$(".radio_botton_corr");
    for (var index = 0; index < radioBottons.length; index++) {
      radioBottons[index].disabled=false;
      if(radioBottons[index].checked){
        radioBottons[index].checked=false;
      };  
    }
    location.reload();
    return true;
  },
  conservarInfo(){
    destinatario.disabled=false;
    asunto.disabled=false;
    destino.disabled=false;
    ob.disabled=false;
    num_pages.disabled=false;
    copia.disabled=false;
    anexo.disabled=false;
    if (this.correspondence.attach){obs_anexo.disabled=true;}

    var radioBottons=$(".radio_botton_corr");
    for (var index = 0; index < radioBottons.length; index++) {
      radioBottons[index].disabled=false;  
    }
  },

  user_state(st, id, pos){
    axios.get(this.path+'user_state/',{params:{
      state: st,
      userid: id}
    })
    .then(response => {
      this.setTabAdmin(1);
    })
    .catch(function (error) {
      console.log(error.response);
    })
  },
   
  addUser(){
    var ref = prompt("Introduzca el correo electrónico");
    if(ref){
      axios.get(this.path+'addUser/',{params:{
        org: this.departmentid, 
        idUser: this.userid, 
        mail: ref}
      })
      .then(response => {
        console.log(response.data);
        this.users.push({correo: ref, nombre: '', apellido: '', idUsuario: response.data.result, estado: {data: 0}});
        alert("La contraseña es: "+response.data.pass);
        //this.setTabAdmin(1);
      })
      .catch(function (error) {
        M.toast({html: '<i class="material-icons prefix">cancel</i> Verifique el correo electrónico', classes: 'rounded'});
        console.log(error);
      })
    }
  },
      filter(){
        
      if(this.tipoFiltro==4){
        this.activeProgressBar=true;
        axios.get(this.path+'corr_e/',{params:{
          id: this.departmentid, 
          tag: this.selectedTag, 
          userid: this.userid
        }})
        .then(response => {
          this.table=response.data;
          this.table3=response.data;
          this.activeProgressBar=false;
          
        })
        .catch(function (error) {
          this.toastError();
          //console.log(error.response);
        })

      }else if(this.tipoFiltro==1){
        this.activeProgressBar=true;
        axios.get(this.path+'corr_e/recientes/',{params:{id: this.departmentid, tag: this.selectedTag, userid: this.userid}})
        .then(response => {
          //console.log(response.data);
          this.table=response.data.reverse();
          this.table3=this.table;
          this.activeProgressBar=false;
          
        })
        .catch(function (error) {
          console.log(error.response);
          this.toastError();
        })

        }else if(this.tipoFiltro==2){
          if(document.getElementById("year").value != null){
            this.activeProgressBar=true;
            axios.get(this.path+'corr_e/year/',{params:{
              id: this.departmentid,
              year: document.getElementById("year").value,
              tag: this.selectedTag, userid: this.userid
          }})
          .then(response => {
            //console.log(response.data);
            this.table=response.data;
            this.table3=response.data;
            this.activeProgressBar=false;
            //M.Toast.dismissAll();
          })
          .catch(function (error) {
            console.log(error.response);
            this.toastError();
          })
          }
          
        }else if(this.tipoFiltro==3){
          this.activeProgressBar=true;
          axios.get(this.path+'corr_e/year_month/',{params:{
            id: this.departmentid,
            year: document.getElementById("year").value,
            month: document.getElementById("month").value,
            tag: this.selectedTag, userid: this.userid
          }})
          .then(response => {
            //console.log(response.data);
            this.table=response.data;
            this.table3=response.data;
            this.activeProgressBar=false;
            //M.Toast.dismissAll();
          })
          .catch(function (error) {
            console.log(error.response);
            this.toastError();
          })

        }else if(this.tipoFiltro==5){

        }else if(this.tipoFiltro==6){

        }else if(this.tipoFiltro==7){

        }else if(this.tipoFiltro==8){

        }else {}
        
      },
      str_ref(num, ref, fecha_envio) {
        ref=ref.replace("<num>",this.str_num(num));
        var fecha=this.dateFormat(fecha_envio);
        ref=ref.replace("<y>",fecha.year);
        ref=ref.replace("<m>",fecha.month);
        ref=ref.replace("<d>",fecha.day);
       return ref; 
      },
      dateFormat(date){
        var fecha=date.split('-');
        var dia=fecha[2].split('T');
        var time=dia[1].split(':');
        return {year: fecha[0], month: fecha[1], day: dia[0], hour: time[0]-6, min: time[1]}
      },
      dateFormatString(date){
        var fecha=this.dateFormat(date);
        var s="am";
        if(fecha.hour>=12){
          s="pm";
          if(fecha.hour>=13){
          fecha.hour-=12;
          }
        }
        var str=fecha.day+"/"+fecha.month+"/"+fecha.year+" "+fecha.hour+":"+fecha.min+" "+s;
        return str;
      },
      str_num(num){
        if(num<10){
          num="000"+num;
        }else if (num<100){
          num="00"+num;
        }else if (num<1000){
          num="0"+num;
        }
        return num;
      },
      getDocumentName(num){
        //console.log(num);
        if(this.typeDocuments.length==0){
          axios
        .get(this.path+'typeDocuments/' + this.departmentid)
        .then(response => {
          this.typeDocuments=response.data;
        })
        .catch(function (error) {
          console.log(error);
        })
        }
        
        var str="";
        this.typeDocuments.forEach(element => {
          if(element.idTipoDeDocumento==num){
            str=element.nombre;
            return;
          }
        });
        return str;
      },
      modalInfo(num){
        this.indexTable=num;
        var doc=this.table[num];
        num_oficio.innerHTML=this.getDocumentName(doc.TipoDeDocumento_idTipoDeDocumento)+" N° "+this.str_ref(doc.correlativo, doc.referencia, doc.fecha_envio);
        //destino_info.innerHTML=doc["destino"];
        this.correspondence.dest_proc=doc["destino"].split(";");
        asunto_info.innerHTML=doc["asunto"];
        this.correspondence.dest_rem=doc["destinatario"].split(";");

        axios
          .get(this.path+'documentInfo/', {params:{
            idDocument: doc["CorrespondenciaEnviada_detalle_idCorrespondenciaEnviada"],
            userid: doc["idUsuario"]
          }})
          .then(response => {
            //console.log(response);
            num_pages_info.innerHTML=""+response.data.info[0].num_paginas;
            obs_anexo_info.innerHTML=response.data.info[0].obs_anexo;
            fecha_info.innerHTML=this.dateFormatString(doc.fecha_envio) + this.getUserInfo(response.data.usuario_detalle[0]);
            if(response.data.info[0].fecha_remision==null){
              envio_info.innerHTML="NO ESPECIFICADO";
              this.btnEdit.btn1=1;
            }else{
              envio_info.innerHTML=this.dateFormatString(response.data.info[0].fecha_remision) + this.getUserInfo(response.data.usuario_detalle1[0]);
              this.btnEdit.btn1=0;
            }
            if(response.data.info[0].fecha_escaneo==null){
              escaneo_info.innerHTML="SIN ESCANEAR";
              this.btnEdit.btn3=1;
            }else{
              escaneo_info.innerHTML=this.dateFormatString(response.data.info[0].fecha_escaneo) + this.getUserInfo(response.data.usuario_detalle3[0]);
              this.btnEdit.btn3=0;
            }

            if(response.data.info[0].fecha_recepcion=="0000-00-00 00:00:00"){
              recepcion_info.innerHTML="NO ESPECIFICADO";
              this.btnEdit.btn2=1;
            }else{
              recepcion_info.innerHTML=this.dateFormatString(response.data.info[0].fecha_recepcion) + this.getUserInfo(response.data.usuario_detalle2[0]);
              this.btnEdit.btn2=0;
            }
            
            if(response.data.info[0].observaciones==""){
              obs_info.innerHTML="NINGUNA";
            }else{
              obs_info.innerHTML=response.data.info[0].observaciones;
            }
            
            if(response.data.info[0].copia.data[0]==1){
              copy.checked=true;
            }
            if(response.data.info[0].anexo.data[0]==1){
              attach.checked=true;
            }
/*
fecha_ultimamodificacion: null
​​​idUserUltimaModificacion: null
​​​ ""*/

          })
          .catch(function (error) {
            console.log(error);
          })
      },
      btnEnvio(i){
        this.btnSelected=i;
        modalDate.M_Modal.open()
      }
        ,
      btnEnvio2(date){
        if(this.btnSelected==1){
          btnEnvio.disabled=true;
        }else if(this.btnSelected==2){
          btnRecepcion.disabled=true;
        }else if(this.btnSelected==3){
          btnEscaneo.disabled=true;
        }
        var doc=this.table[this.indexTable];
        console.log(doc);
    //aqui
      this.activeProgressBar=true;
      axios.get(this.path+'tracing/',{params:
        { idDoc: doc.CorrespondenciaEnviada_detalle_idCorrespondenciaEnviada,
          userid: this.userid,
          case: this.btnSelected,
          date: this.dateFormatShort(date)
        }})
        .then(response => {
          this.activeProgressBar=false;
          this.modalInfo(this.indexTable);
        })
        .catch(function (error) {
          console.log(error);
          this.modalInfo(this.indexTable);
          this.activeProgressBar=false;
        })
        }
        ,
        dateFormatShort(date){
          var params=date.split(" ");
          console.log(params);

          switch (params[0]) {
            case 'Ene': params[0]=1;
            break;  
            case 'Feb': params[0]=2;
            break;  
            case 'Mar': params[0]=3;
            break;  
            case 'Abr': params[0]=4;
            break;  
            case 'May': params[0]=5;
            break;  
            case 'Jun': params[0]=6;
            break;  
            case 'Jul': params[0]=7;
            break;  
            case 'Ago': params[0]=8;
            break;  
            case 'Sep': params[0]=9;
            break;  
            case 'Oct': params[0]=10;
            break;  
            case 'Nov': params[0]=11;
            break;  
            case 'Dic': params[0]=12;
            break;  
          }
          return params[2]+'-'+params[0]+'-'+params[1].replace(',','');
        },
      getUserInfo(data){
        return "<p class='mayusc'>" + data.num_empleado + " " + data.nombre + " " + data.apellido+"</p>"
      },
      navegar(num){
        if((this.indexTable+num >=0)&&(this.indexTable+num < this.table.length)){
          this.modalInfo(this.indexTable+num);
        }else{
          M.toast({html: '<i class="material-icons prefix">error</i> No hay más documentos que mostrar', classes: 'rounded'});
        }
      },
      verificarPassword2(){
        var reg_ex = new RegExp("[A-Z]+");
        var result= reg_ex.test(this.pswd.new_password);
        if(result){
          this.pswd.mayus=true;
        }else{
          this.pswd.mayus=false;
        }

        reg_ex = new RegExp("[0-9]+");
        result= reg_ex.test(this.pswd.new_password);
        if(result){
          this.pswd.num=true;
        }else{
          this.pswd.num=false;
        }

        reg_ex = new RegExp("[,;.:\-_\[\]\{\}\|°!\"$%&\/\(\)\=¿\?]+");//no funcional
        result= reg_ex.test(this.pswd.new_password);
        if(result){
          this.pswd.c=true;
        }else{
          this.pswd.c=false;
        }

        if(this.pswd.new_password.length>=8){
          this.pswd.n=true;
        }else{
          this.pswd.n=false;
        }

    
      },
      savePassword(){
        this.activeProgressBar=true;
        axios
        .get(this.path+'password/', {params:{
          new_password: CryptoJS.SHA1(this.pswd.new_password)+"",
          last_password: CryptoJS.SHA1(this.pswd.last_password)+"",
          id: this.userid
        }})
        .then(response => {
          if(response.data==0){
            M.toast({html: '<i class="material-icons prefix">cancel</i>Verifique sus datos, No se pudo cambiar la contraseña', classes: 'rounded'});
            //No se pudo cambiar la contraseña
            
          }else if(response.data==1){
            //Exito
            M.toast({html: '<i class="material-icons prefix">check_circle</i>La contraseña fue cambiada satisfactoriamente', classes: 'rounded'});
          }
          this.pswd.last_password="";
            this.pswd.new_password="";
            this.pswd.repeat_password="";
            this.pswd.mayus=false;
            this.pswd.num=false;
            this.pswd.c=false;
            this.pswd.n=false;
            this.correct=false;
            this.activeProgressBar=false;
        })
        .catch(function (error) {
          this.activeProgressBar=false;
          console.log(error);
        })
      
      },
      exitApp(){
        document.cookie.split(";").forEach(function(c) {
          document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        });
        location.reload();
      },
      closeSide(){
        location.reload();
      },
      isThisAdmin(mail){
        if(mail==this.mail){
          return "none";
        }
        return "inline";
      },
      changePag(pag){
        this.a=pag;
      },
      isA(){
        return (this.a==1);
      },
      maxpage(){
        var total=this.a*this.e;
        if(total>this.table.length){
          return this.table.length;
        }
        return total;
      }

    },

    computed: {

      verificarPassword(){
        var reg_ex = new RegExp("[A-Z]+");
        var result= reg_ex.test(this.pswd.new_password);
        var equals=this.pswd.last_password==this.pswd.new_password;
        if(!equals){
          if((this.pswd.new_password==this.pswd.repeat_password)&&(this.pswd.last_password!="")&&(result)){
            return true;
          }
        }else if((this.pswd.last_password!="")&&(this.pswd.new_password!="")){
          M.toast({html: '<i class="material-icons prefix">error</i>La contraseña nueva debe ser diferente a la actual contraseña', classes: 'rounded'});
        }
        
        return false;
      },

      mostrar_meses(){
        var estado='none';
        if(this.tipoFiltro==3){
          estado='inline';
        }
        return estado;
      },
      mostrar_menu(){
        var estado='inline';
        if(this.selectedTag==null){
          estado='none';
        }else if(this.selectedTag==0 || this.selectedTag==2){ //0||1
          btn_filter.click();
          estado='inline';
        }else{estado='none'}
        return estado;
      },
      mostrar_etable(){
        var t=this.table.length;
        var estado='inline';
        if(t!=0){
          return estado;
        }
        estado='none';
        return estado;
      },
      getIconName(){
        var name="";
        if(this.selectedTag==0){
          name="add_circle"
        }
        if(this.selectedTag==1){
          name="add_box"
        }
        return name;
      },
      table_pagination(){
        var t=this.table.length;
        if(t!=0){
          
          if(this.e!=0){
            var pag =t/this.e;
            if(pag<=1){
              this.p=[1];
              return this.table;
            }
            this.p=new Array(Math.ceil(pag));
            this.table2=[];
            if((this.a-1)*this.e>this.table.length){
              this.a=1;
            }
            for(var i=(this.a-1)*this.e;(i<this.table.length)&&(i<((this.a-1)+1)*this.e);i++){
              this.table2.push(this.table[i]);
            }
            return this.table2;
          }else{
            this.p=[1];
            return this.table;
          }
        }
        this.p=[];
        return null;
      }
    }

  })