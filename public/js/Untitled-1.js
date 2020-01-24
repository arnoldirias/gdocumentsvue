M.Toast.dismissAll();
M.toast({html: '<div class="preloader-wrapper small active"><div class="spinner-layer spinner-green-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> Guardando', classes: 'rounded'});

M.toast({html: '<i class="material-icons prefix">cancel</i>Verifique sus datos, No se pudo cambiar la contraseña', classes: 'rounded'});
M.toast({html: '<i class="material-icons prefix">check_circle</i>La contraseña fue cambiada satisfactoriamente', classes: 'rounded'});
M.toast({html: '<i class="material-icons prefix">error</i>La contraseña nueva debe ser diferente a la actual contraseña', classes: 'rounded'});

M.Toast.dismissAll();
M.toast({html: '<i class="material-icons prefix">cancel</i> Se produjo un error', classes: 'rounded'});
M.toast({html: '<i class="material-icons prefix">check_circle</i> Cambios guardados satisfactoriamente', classes: 'rounded'});