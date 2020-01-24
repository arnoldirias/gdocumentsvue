<?php
  session_start();
  if(!isset($_SESSION["email"])){
    session_destroy();
	  header("Location: index.php");
  }
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Gestión de Documentos UNAH</title>
    <meta charset="UTF-8">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->	
	  <link rel="icon" type="image/png" href="public/images/icons/favicon.ico"/>
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="public/materialize/css/materialize.min.css"  media="screen,projection"/>
    
    <link type="text/css" rel="stylesheet" href="public/css/estilos.css" />
    <style>
      @font-face {
        font-family: Raleway-Black;
        src: url('fonts/raleway/Raleway-Black.ttf'); 
      }

      .login100-form-title {
        font-family: Raleway-Black;
        font-size: 20px;
        color: white;
        line-height: 1.2;
        text-transform: uppercase;
        text-align: center;
        width: 100%;
        display: block;
      }
    </style>
  </head>
  
  <body>

    <div id="app">
    <div class="progress" v-if="activeProgressBar">
      <div class="indeterminate"></div>
    </div>

    <nav class="nav-extended" id="my-navbar">

      <div class="nav-wrapper">
          <a href="#!" class="brand-logo">
            <h4 class="principal">
              <strong class="login100-form-title pdl25">Gestión de Documentos | {{department}}</strong>
            </h4>
          </a>
          
        <a href="#" data-target="mobile-demo" class="sidenav-trigger">
          <i class="material-icons">menu</i>
        </a>

        <ul id="nav-mobile" class="right hide-on-med-and-down">   
          <!--
            <li><a href="#modal_corr_enviada" class="modal-trigger tooltipped" data-position="bottom" data-tooltip="Registrar Correspondencia Enviada" @click="modal">
            <i class="small material-icons waves-effect" >add_circle</i></a>
          </li>
          
          <li><a href="#" class="tooltipped" disabled="true"data-position="bottom" data-tooltip="Registrar Correspondencia Recibida">
            <i class="small material-icons">archive</i></a>
          </li>
          -->
          <li><a  data-target="slide-out" onclick="miboton.click();" class="waves-teal">
            Cerrar Sesión
            <i class="material-icons left">chevron_right</i>
            </a>
          </li>
        </ul>
      </div>

      <div class="nav-content row">
        <ul class="tabs tabs-transparent" >
          <li class="tab col s2" v-for="tag, k in tags" @click="changeTag(k)">
            <a><i class="material-icons left">{{tag.iconName}}</i>{{tag.name}}</a>
          </li>
        </ul>
      </div>

    </nav>

    <div class="section" id="selection"  v-if="selectedTag!=null">
    <!---->
    <a href="#modal_corr_enviada" class="modal-trigger btn-floating btn-large right waves-effect waves-light waves-yellow teal tooltipped pulse"  data-position="bottom" data-tooltip="Registrar Correspondencia Enviada" > <!--v-if="selectedTag==0 || selectedTag==1"-->
        <i class="material-icons">add_circle</i>
      </a>
      
      <h5 class="pdl25">{{tags[selectedTag].largeName}}</h5>
      <h6 class="pdl25">{{tags[selectedTag].description}}</h6>
    </div>
    
    <div class="divider"></div>                  
    <div class="row content-main" >

      <div class="col s12 m3 l2 side card-panel grey lighten-4" id="side-filter" :style="{display: mostrar_menu}">

      </br>
      <div id="input-filter" class="input-field">
      <p>Seleccione una opción para filtrar:</p>
      <div>
      <select v-model.number="tipoFiltro">
        <option value="1" selected>Recientes</option>
        <option value="2">Por Año</option>
        <option value="3">Por Mes y Año</option>
        <option value="4">Ver Todos</option>
      </select>
        <br>
      </div>
        
      </div>
        
        <div class="input-field"  :style="{display: mostrar_meses}">
            MES:
            <select class="browser-default" id="month">
              <option v-for="el, i in months" :value="i">{{el}}</option>
            </select>
            <br>
        </div> 
                  
            <div class="input-field" v-if="tipoFiltro==3 || tipoFiltro==2">
                <input id="year" type="text" class="validate">
                <label for="year" >AÑO:</label>
              </div>
              
        <div class="center">
            <a id="btn_filter" class="waves-effect waves-light btn-large" @click="filter">FILTRAR</a>
        </div>
   
      </div>

      <div class="col s12 m9 l10" :style="{display: mostrar_menu}">
        <div id="man" class="col s12">
          <div class="material-table">
            <div class="table-header" >
              <span class="table-title col l7 m7 s6">Correspondencia {{filters[tipoFiltro-1]}}</span>
              
              <div id="search_input" class="input-field col l4 m4 s5">
                <textarea id="icon_prefix2" type="text" class="materialize-textarea" placeholder="Buscar:" @keyup.backspace="searching2" @keyup="searching"  v-model="str"></textarea>
              </div>
              <div class="input-field col l1 m1 s1" :style="{display: mostrar_etable}">
                <select v-model.number="e">
                  <option value="10" >10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="0" selected>Todo</option>
                </select>
                <label>Mostrar:</label>
              </div>
            </div>
              
            <table id="mytable" v-if="table.length>0" class="card striped highlight">
              <thead>
                <tr>
                <th class="pd15" scope="col">N°</th>
                  <th class="pd15" scope="col">Tipo de Documento<br>Referencia</th>
                  <th class="pd15" scope="col">Dirigido a:<br>Asunto:</th>
                  <th class="pd15" scope="col">Departamento/Unidad</th>
                  <th class="pd15" scope="col">Fecha</th>
                  <th class="pd15" scope="col"></th>
                </tr>
              </thead>
              <tbody>
              <tr v-for="doc, x in table_pagination">
                  <th class="pd15" scope="row">{{x+1}}</th>
                  <th class="pd15">{{getDocumentName(doc.TipoDeDocumento_idTipoDeDocumento)}}
                    <br>{{str_ref(doc.correlativo, doc.referencia, doc.fecha_envio)}}
                  </th>
                  <td class="mayusc pd15">{{doc.destinatario}}
                    <br class="mayusc">{{doc.asunto}}
                  </td>
                  <td class="pd15"><strong class="mayusc">{{doc.destino}}</strong></td>
                  <td class="pd15">{{dateFormatString(doc.fecha_envio)}}</td>
                  <td class="pd15" @click="modalInfo(x)"><a href="#modal_info" class="modal-trigger tooltipped"><i class="material-icons prefix" data-tooltip="Ver más">launch</i></a></td>
                </tr> 
              </tbody>
            </table>
            <div class="row s12">
            <ul class="pagination col s7" :style="{display: mostrar_etable}">
              <li v-bind:style="{ disabled: isA()}"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
              <li class="waves-effect" v-for="num, h in p" v-bind:class="{ active: a==h+1 }"><a @click="changePag(h+1)">{{h+1}}</a></li>
              <li ><a href="#!"><i class="material-icons">chevron_right</i></a></li>
            </ul>
            <h6 class="col s5" v-if="(p.length>1)&&(table.length>0)">Mostrando del <b>{{(a-1)*e+1}}-{{maxpage()}} de {{table.length}}</b></h6>
            <h6 class="col s5" v-if="(p.length==1)&&(table.length>0)">Mostrando todos los registros <b>({{table.length}})</b></h6>
            </div>
            <div class="row s12 center" v-if="table.length==0">
              <h5>No hay registros</h5>
            </div>
            
          </div>
        </div>
    </div>

      <div class="" v-if="selectedTag==4">
          <div id="content_main" class="row col s12 m12 l12 no-padding" >
            <ul id="nav-mobile" class="col s12 m4 l3 " >
              <li class="no-padding">
                <ul class="collapsible collapsible-accordion no-padding">
                  <li class="bold white"><a class="collapsible-header waves-effect waves-teal pt15" @click="setTabAdmin(0)">
                    <i class="material-icons left">account_circle</i>
                    Mi Información
                  </a></li>
                  <li class="bold white"><a class="collapsible-header waves-effect waves-teal pt15" @click="setTabAdmin(1)" v-if="isAdmin">
                    <i class="material-icons left">group</i>
                    Usuarios
                  </a></li>
                  <li class="bold active white"><a class="collapsible-header waves-effect waves-teal pt15" @click="setTabAdmin(2)" v-if="isAdmin">
                    <i class="material-icons left">assignment</i>
                   Documentos
                  </a></li>
                  <li class="bold white"><a class="collapsible-header waves-effect waves-teal pt15" @click="setTabAdmin(3)" v-if="isAdmin">
                    <i class="material-icons left">business</i>
                    Nombre{{complemento[numDepartment]}}</a>
                  </li>
                  <li class="bold active white"><a class="collapsible-header waves-effect waves-teal pt15" @click="setTabAdmin(4)">
                    <i class="material-icons left">lock_outline</i>
                    Cambiar Contraseña
                  </a></li>
                  <li class="bold active white"><a class="collapsible-header waves-effect waves-teal pt15" @click="setTabAdmin(5)">
                    <i class="material-icons left">local_post_office</i>
                    Actualizar Correo Electrónico <strong>(EN CONSTRUCCIÓN)</strong>
                  </a></li>
                </ul>
              </li>
            </ul>
            

            <form class="col s12 m8 l9" v-if="selectedTagAdmin==0">
                <div class="">
                  
  <div class="row">
      <div>
        <div class="card">
          <div class="card-image">
            <img src="public/images/img-10.jpg">
            <span class="card-title black-text col s12" style="background-color: white;opacity: 0.5;">Mi Información</span>
            <a class="btn-floating halfway-fab waves-effect waves-light red" @click="edit(1)" v-if="!editing"><i class="material-icons">create</i></a>
            <a class="btn-floating halfway-fab waves-effect waves-light green" @click="save(1)" v-else><i class="material-icons">done</i></a>
          </div>

          <div class="card-content">
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="username" placeholder="" type="text" name="username" class="validate mayus" v-model="username" required disabled>
                    <label for="username" class="active">Nombre:</label>
                  </div>
                <div class="input-field col s12 m6 l6">
                    <i class="material-icons prefix">face</i>
                    <input id="lastname" placeholder="" type="text" name="lastname" class="validate mayus" v-model="userlastname" required disabled>
                    <label for="lastname" class="active">Apellidos:</label>
                  </div>
            </div>
            
                
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <i class="material-icons prefix">work</i>
                    <input id="num" type="number" name="num" class="validate" v-model.number="numEmployment" min="0" max="65535" maxlength="5" data-length="5" disabled>
                    <label for="num" class="active">Número de Empleado:</label>
                    <span class="helper-text" data-error="Escriba un número válido" data-success=""></span>
                  </div>
                <div class="input-field col s12 m6 l6">
                    <i class="material-icons prefix">mail</i>
                    <input id="mail" type="email" name="mail" class="validate" v-model="mail" disabled>
                    <label for="mail" class="active">Correo Electrónico:</label>
                    <span class="helper-text" data-error="Escriba un correo válido" data-success=""></span>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </form>


    <form class="col s12 m8 l9" v-if="selectedTagAdmin==3 && isAdmin">
        <div class="">
            <div class="row">
                  <div class="card">
                    <div class="card-image">
                      <img src="public/images/img-10.jpg">
                      <span class="card-title black-text col s12" style="background-color: white;opacity: 0.5;">Nombre de la Organización</span>
                      <a class="btn-floating halfway-fab waves-effect waves-light red" @click="edit(4)" v-if="!editing"><i class="material-icons">create</i></a>
            <a class="btn-floating halfway-fab waves-effect waves-light green" @click="save(4)" v-else><i class="material-icons">done</i></a>
                    </div>
          
                    <div class="card-content">
                      <div class="row">
                          <div class="input-field">
                                  <input id="name" type="text" name="text" class="validate mayus" v-model="department" required disabled>
                                  <label for="name" class="active">Nombre{{complemento[numDepartment]}}:</label>
                              
                            </div>
                      </div>
                      </div>
                    </div>
                </div>
              

          </div>
          </form>
          <form class="col s12 m8 l9" v-if="selectedTagAdmin==1 && isAdmin">
            <div class="">
                <div class="row">
                      <div class="card">
                        <div class="card-image">
                          <img src="public/images/img-10.jpg">
                          <span class="card-title black-text col s12" style="background-color: white;opacity: 0.5;">Gestión de Usuarios</span>
                          <a class="btn-floating halfway-fab waves-effect waves-light red" @click="addUser"><i class="material-icons">add</i></a>
                        </div>
              
                        <div class="card-content">
                          <div class="row">
                              


      <table class="striped highlight responsive-table" >
        <thead>
          <tr>
              <th>N°</th>
              <th>Nombre</th>
              <th>Correo</th>
              <!--<th>Permisos</th>-->
              <!--<th>Editar</th>-->
              <th>Estado</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="u, w in users">
            <td>{{w+1}}</td>
            <td class="mayusc" >{{u.nombre +" "+ u.apellido}}</td>
            <td>{{u.correo}}</td>
            <!--<td>{{u.permisos.data[0]}}</td>-->
            <!--<td><a class="btn-floating waves-effect waves-light btn-small"><i class="material-icons left">edit</i></a></td>-->
            <td v-if="u.estado.data[0]==1" v-bind:style="{ display: isThisAdmin(u.correo) }"><a class="btn-floating waves-effect waves-light btn-small"><i class="material-icons left" @click="user_state(true, u.idUsuario, w)">check</i></a></td>
            <td v-else><a class="btn-floating waves-effect waves-light btn-small black"><i class="material-icons center" @click="user_state(false, u.idUsuario, w)">block</i></a></td>
          </tr>
        </tbody>
      </table>
            


                          </div>
                          </div>
                        </div>
                    </div>
                  
    
              </div>
              </form>

              <form class="col s12 m8 l9" v-if="selectedTagAdmin==2  && isAdmin">
                <div class="">
                    <div class="row">
                          <div class="card">
                            <div class="card-image">
                              <img src="public/images/img-10.jpg">
                              <span class="card-title black-text col s12" style="background-color: white;opacity: 0.5;">Gestión de Documentos</span>
                              <a id="btn_modal1" class="modal-trigger btn-floating halfway-fab waves-effect waves-light red " href="#modal1" @click="modal2"><i class="material-icons">add</i></a>
                            </div>
                  
                            <div class="card-content">
                              <div class="row">
                              <table class="striped highlight responsive-table">
            <thead>
              <tr>
                  <th>N°</th>
                  <th>Tipo de Documento</th>
                  <th>Referencia</th>
                  <th>Editar</th>
              </tr>
            </thead>
    
            <tbody>
              <tr v-for="doc, k in documents">
                <td>{{k+1}}</td>
                <td>{{doc.nombre}}</td>
                <td>{{doc.referencia}}</td>
                <td><a class="btn-floating waves-effect waves-light btn-small"><i class="material-icons left" @click="editDocument(k)">edit</i></a></td>
              </tr>
            </tbody>
          </table>
           <br>   
          <button style="float: left;" class="waves-effect waves-light btn" v-if="!stateDocuments" @click="activeSystem">TERMINAR CONFIGURACIÓN</button>
          <h5 v-if="documents.length==0" class="center align-center">No hay documentos</h5>
                              </div>
                              </div>
                            </div>
                        </div>
                      
        
                  </div>
                  </form>


                  <form class="col s12 m8 l9" v-if="selectedTagAdmin==4">
                <div class="">
                  
  <div class="row">
      <div>
        <div class="card">
          <div class="card-image">
            <img src="public/images/img-10.jpg">
            <span class="card-title black-text col s12" style="background-color: white;opacity: 0.5;">Cambiar Contraseña</span>
            <a class="btn-floating halfway-fab waves-effect waves-light green" @click="savePassword" v-if="verificarPassword" ><i class="material-icons">done</i></a>
          </div>

          <div class="card-content">
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <i class="material-icons prefix">https</i>
                    <input placeholder="" type="password" name="last_pwd" class="validate mayus" v-model="pswd.last_password" required maxlength="16">
                    <label for="last_pwd" class="active">Contraseña actual:</label>
                  </div>
            </div>
            
            <div class="row">
              <div class="input-field col s12 m6 l6">
                    <i class="material-icons prefix">lock_outline</i>
                    <input placeholder="" type="password" name="new_pwd" class="validate mayus" @keyup="verificarPassword2" v-model="pswd.new_password" min="8" maxlength="16" required>
                    <label for="nwe_pwd" class="active">Nueva contraseña:</label>
              </div>

              <div class="input-field col s12 m6 l6">
                    <label class="active">Debe contener:</label>

                    <div style="margin-top: 10px;">
                      <div>
                      <i class="material-icons tiny" v-if="!pswd.mayus">cancel</i>
                      <i class="material-icons green tiny"v-else>check</i>
                      <span>Al menos una letra mayúscula</span>
                      </div>
                    </div>

                    <!--
                    <div>
                      <div>
                      <i class="material-icons tiny" v-if="!pswd.c">cancel</i>
                      <i class="material-icons green tiny"v-else>check</i>
                      <span>Al menos un caracter especial</span>
                      </div>
                    </div>
                    -->

                    <div>
                      <div>
                      <i class="material-icons tiny" v-if="!pswd.num">cancel</i>
                      <i class="material-icons green tiny"v-else>check</i>
                      <span>Al menos un numero</span>
                      </div>
                    </div>

                    <div>
                      <div>
                      <i class="material-icons tiny" v-if="!pswd.n">cancel</i>
                      <i class="material-icons green tiny"v-else>check</i>
                      <span>Al menos 8 caracteres</span>
                      </div>
                    </div>

                  </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6 l6">
                    <i class="material-icons prefix">lock_outline</i>
                    <input placeholder="" type="password" name="repeat_pwd" class="validate mayus" v-model="pswd.repeat_password" min="8" maxlength="16" required>
                    <label for="repeat_pwd" class="active">Repita la nueva contraseña:</label>
              </div>
            </div>

            

            </div>
          </div>
        </div>
      </div>
    </div>
    </form>


        </div>  
  </div>

  <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Nuevo Documento</h4>
      <div class="divider"></div> 
      <p class="pdleft10">Seleccione un Tipo de Documento:</p>
      
      <div class="row">
      <p>
      <label v-for="doc in typeDocuments" class="col m4 pdleft10">
        <input class="with-gap mayus" name="group1" type="radio"  />
        <span>{{doc.nombre}}</span>
      </label>
    </p>
      </div>

      <DIV class="ROW">
      <P class="pdleft10">No aparece su Tipo de Documento, agreguelo:
      <a class="btn-floating waves-effect waves-light btn-small"><i class="material-icons left" @click="addTypeDocument">add</i></a>
      </P>
      </DIV>
      
        <div class="input-field col s12">
          <input id="ref_name" type="text" class="validate" v-model="referencia" required>
          <label for="ref_name">Referencia:</label>
        </div>
    <div class="modal-footer">
      <button id="addDoc" class="modal-close waves-effect waves-green btn-flat btn" @click="addNewDocument">AGREGAR</button>
    </div>
  </div>
  </div>


  <!-- Modal Structure -->
  <div id="modal_corr_enviada" class="modal" style="top: 0%;max-height: 95%;width: 90%;">
    <div class="modal-content">
      <h5>Registrar Correspondencia Enviada</h5>
      <span id="btnRef" class="btn" v-if="correspondence.response">
        Su número de referencia es: {{correspondence.response_ref}} 
      </span>
      <div class="divider"></div>
      
      <p class="pdl25"><span class="srequired">*</span>Seleccione el Tipo de Documento:</p>
      
      <div class="row">
      <p>
      <label v-for="doc in typeDocuments" class="col s6 m3 l3">
        <input class="with-gap radio_botton_corr" name="group1" type="radio"  />
        <span>{{doc.nombre}}</span>
      </label>
      </p>
      </div>
      
    <div class="row">
        <div class="col s12 m12 l8">
        
        <div class="input-field col s12 m12 l12">
          <span class="srequired">*</span>
          <i class="material-icons prefix">face</i>
          
          <div class="chips chips-autocomplete input-field" id="destinatario">
              <input class="custom-class validate mayus tooltipped" maxlength="500" data-position="right" data-tooltip="Presione ENTER para seguir agregando destinatarios" placeholder="DIRIGIDO A:" required>
          </div>
        </div>
          
          <div class="input-field col s12 m12 l12">
          <span class="srequired">*</span>
            <i class="material-icons prefix">insert_comment</i>
            <input id="asunto" type="text" class="validate mayus" placeholder="Razón de envio:" min="1" maxlength="250" required v-model="correspondence.affair">
            <label for="asunto" class="active" v-if="correspondence.affair!=''">Asunto</label>
          </div>
        
          <!--
          <div class="input-field col s12 m12 l12">
          <span class="srequired">*</span>
            <i class="material-icons prefix">business</i>
            <input id="destino" type="text" class="validate mayus" placeholder="Facultad/Dependencia/Unidad:" maxlength="250" required v-model="correspondence.dest_proc">
            <label for="destino" class="active" v-if="correspondence.dest_proc!=''">Destino</label>
          </div>
          -->

          <div class="input-field col s12 m12 l12">
          <span class="srequired">*</span>
          <i class="material-icons prefix">business</i>
          
          <div class="chips chips-autocomplete input-field" id="destino2">
              <input class="custom-class validate mayus tooltipped" maxlength="500" data-position="right" data-tooltip="Presione ENTER para seguir agregando destinos" placeholder="Facultad/Dependencia/Unidad:" required>
          </div>
        </div>

          <div class="input-field col s12 m12 l12">
          <i class="material-icons prefix">assignment</i>
          <textarea id="obs" class="materialize-textarea mayus" v-model="correspondence.obs" maxlength="250" placeholder="OBSERVACIONES:"></textarea>
            <label for="obs" class="active" v-if="correspondence.obs!=''">Observaciones:</label>
          </div>

        </div>

        <div class="col s12 m12 l4">
        <div class="input-field inline pdleft10">
          <span class="srequired">*</span>
          <i class="material-icons im">description</i>
          <span>Número de páginas:</span> 
          <input id="num_pages" class="validate" min="1" required v-model="correspondence.num_pages">
          <!--<label for="num_pages" class="active"></label>-->
        </div>

        <label class="col s12">
        <input id="copia" type="checkbox" class="filled-in pdleft15" v-model="correspondence.copy"/>
        <span>Copia</span>
        </label>

        <label class="col s12">
        <input id="anexo" type="checkbox" class="filled-in pdleft15" v-model="correspondence.attach"/>
        <span>Anexo</span>
        </label>

        <div class="row" v-if="correspondence.attach">
          <div class="input-field col s12 pdleft10">
             <textarea id="obs_anexo" class="materialize-textarea mayus" v-model="correspondence.obs_attach" maxlength="250" placeholder="CONTENIDO DEL ANEXO:"></textarea>
              <label for="obs_anexo" class="active" v-if="correspondence.obs_attach!=''">Obs. del contenido del Anexo:</label>
          </div>
        </div>

        </div>
    </div>

    

    <div class="modal-footer">
      <button id="addDoc" class="waves-effect waves-green btn-flat btn" @click="addNewCorrespondence" v-if="!correspondence.response">
        <i class="material-icons prefix small">check</i>
        GUARDAR
      </button>
      <button class="modal-close waves-effect waves-green btn-flat btn" v-if="!correspondence.response" @click="emptyModalReload">
        <i class="material-icons prefix">delete</i>
        DESCARTAR
      </button>
      <button class="modal-close waves-effect waves-green btn-flat btn" v-else @click="emptyModal">
        <i class="material-icons prefix">add</i>
        NUEVO
      </button>
      <button class="waves-effect waves-green btn" v-if="correspondence.response" @click="emptyModalReload">SALIR</button>
    </div>
  </div>
  </div>

  <!-- Modal Structure -->
  <div id="modal_info" class="modal" style="top: 0%;max-height: 95%;width: 90%;">
    <div class="modal-content">
    <i @click="navegar(1)" class="material-icons im waves-effect waves-green btn-flat btn">keyboard_arrow_left</i>
    <i @click="navegar(-1)" class="material-icons im waves-effect waves-green btn-flat btn">keyboard_arrow_right</i>
    <h5 id="num_oficio" style="display: contents;"></h5>
    <div class="divider"></div>

    <div class="row">
        <div class="col s12 m12 l8">

          <div class="col s12 m12 l12">
            <ul class="collapsible">
    <li class="active">
      <div class="collapsible-header"><i class="material-icons">group</i>Destinatario(s)</div>
      <div class="collapsible-body">
        <div class="chip mayusc" v-for="person in correspondence.dest_rem">{{person}}</div>
      </div>
    </li>
    <li class="active">
      <div class="collapsible-header"><i class="material-icons">insert_comment</i>Asunto</div>
      <div class="collapsible-body"><span class="mayusc" id="asunto_info"></span></div>
    </li>
    <li class="active">
      <div class="collapsible-header"><i class="material-icons">business</i>Destino</div>
      <div class="collapsible-body">
        <div class="chip mayusc" v-for="destino_str in correspondence.dest_proc">{{destino_str}}</div>
      </div>
    </li>
    <li class="active">
      <div class="collapsible-header"><i class="material-icons">assignment</i>Observaciones</div>
      <div class="collapsible-body mayusc"><span id="obs_info"></span></div>
    </li>
    <li class="active">
      <div class="collapsible-header"><span id="num_pages_info"></span><i class="material-icons">description</i>Información Adicional</div>
      <div class="collapsible-body">
        <label>
          <input id="copy" type="checkbox" class="filled-in pdleft15" v-model="correspondence.copy" disabled/>
          <span>Copia</span>
        </label>

        <label>
          <input id="attach" type="checkbox" class="filled-in pdleft15" v-model="correspondence.attach" disabled/>
          <span>Anexo</span>
        </label>
        <p class="mayusc" id="obs_anexo_info"></p>
      </div>
    </li>
  </ul>
  </div>
  </div>

        <div class="col s12 m12 l4">

        <ul class="collapsible">
    <li class="active">
      <div class="collapsible-header"><i class="material-icons">access_time</i>Emisión</div>
      <div class="collapsible-body"><span id="fecha_info"></></span></div>
    </li>
    <li class="active">
    <a style="float: right;" id="btnEnvio"class="waves-effect waves-light btn blue modal-trigger" @click="btnEnvio(1)" v-if="btnEdit.btn1"><i class="material-icons">edit</i></a>
      <div class="collapsible-header"><i class="material-icons">exit_to_app</i>Envio</div>
      <div class="collapsible-body"><span id="envio_info"></></span></div>
      
      
    </li>
    <li class="active">
    <button id='btnRecepcion'class='btn green' style='float: right;' v-if="btnEdit.btn2" @click="btnEnvio(2)"><i class="material-icons">edit</i></button>
      <div class="collapsible-header"><i class="material-icons">alarm_on</i>Recepción</div>
      <div class="collapsible-body"><span id="recepcion_info"></></span></div>
      
    </li>
    
    <li class="active">
    <button style="float: right;" id='btnEscaneo' class='btn red' v-if="btnEdit.btn3" @click="btnEnvio(3)"><i class="material-icons">edit</i></button>
      <div class="collapsible-header"><i class="material-icons">local_printshop</i>Escaneo</div>
      <div class="collapsible-body"><span id="escaneo_info"></span></div> 
    </li>
    <li class="active">
      <div class="collapsible-header"><i class="material-icons">autorenew</i>Estado</div>
      <div class="collapsible-body"><span id="estado_info"></span></div>
    </li>
  </ul>


        </div>
    </div>

    <div class="modal-footer">
      <button class="modal-close waves-effect waves-green btn">SALIR</button>
    </div>
  </div>
  </div>


   <!-- Modal Trigger -->
   <a class="waves-effect waves-light btn modal-trigger" style="display: none;" href="#modal1">Modal</a>

<!-- Modal Structure -->
<div id="modalDate" class="modal">
  <div class="modal-content">
    <h4 v-if="btnSelected==1">Registro de la Fecha de Remisión (Envío)</h4>
    <h4 v-if="btnSelected==2">Registro de la Fecha de Recepción</h4>
    <h4 v-if="btnSelected==3">Registro de la Fecha de Escaneo</h4>
    <p>Seleccione la fecha:</p>
    <input id="example_datepicker" type="text" class="datepicker" >
  </div>
  <div class="modal-footer">
    <button class="modal-close waves-effect waves-green btn-flat">SALIR</button>
    <a @click="btnEnvio2(example_datepicker.value)" class="modal-close waves-effect waves-green btn">GUARDAR</a>
  </div>
</div>

                
  <ul class="sidenav" id="mobile-demo">
      <li><a href="sass.html">EN CONSTRUCCION</a></li>
      <li><a href="badges.html">EN CONSTRUCCION</a></li>
      <li><a href="collapsible.html">EN CONSTRUCCION</a></li>
  </ul>

  <ul id="slide-out" class="sidenav">
    <div class="row" id="slideout">
    <li><div class="user-view" id="background-profile">
      <div class="background">
      </div>
      <a href="#user"><img class="circle" src="public/images/img-07.png"></a>
      <a href="#email"><span class="white-text email">{{mail}}</span></a>
      <a href="#name"><span class="white-text name">¡Hola!  {{username +" "+ userlastname}}</span></a>
      
    </div></li>
    </div>

    <div class="row">
      <div class="card blue-grey darken-1">
        <div class="card-content white-text">
          <span class="card-title">¿Realmente desea salir del sistema?</span>
        </div>
        <div class="card-action">
          <a href="#" @click="closeSide">CANCELAR</a>
          <a href="#" @click="exitApp">SI</a>
          
        </div>
      </div>
    </div>         

    </ul>

    <a data-position="bottom"  class="sidenav-trigger  btn tooltipped" id="miboton" data-tooltip="I am a tooltip" data-target="slide-out"><i class="material-icons">menu</i></a>
    
    </div>
<div class="row" v-if="selectedTag==1 || selectedTag==3" align="center">
<img src="public/images/sitioc.png" alt="">
<h4>EN CONSTRUCCIÓN</h4>
</div>
    

                     
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="public/materialize/js/materialize.min.js"></script>
    <script src="public///ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="public/js/jquery-3.3.1.min.js">x3C/script>')</script>
    
    <!--
    <script src="public/jquery.dataTables.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="jquery.dataTables.min.css">
    -->
    
    <!--<script type="text/javascript" src="DataTables/datatables.min.js"></script>-->
      
    <script type="text/javascript" src="public/js/initialize.js"></script>
    
    <script>
    var myemail="<?php echo $_SESSION["email"] ?>";
    </script>

    <script>
      miboton.style.display='none';
      //example_datepicker.style.display='none';
    </script>

    <!-- production version, optimized for size and speed -->
    <script type="text/javascript" src="public/js/vue.js"></script>
  
    <script type="text/javascript" src="public/node_modules/axios/dist/axios.min.js"></script>
    <script src="public/js/core-min.js"></script>
	  <script src="public/js/sha1-min.js"></script>
    <script type="text/javascript" src="public/js/main.js"></script>

    

</body>
</html>


        