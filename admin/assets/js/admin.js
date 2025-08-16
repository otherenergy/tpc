$(function () {
  $('[data-bs-toggle="tooltip"]').tooltip()
});

$('#form-login.admin').submit(function(e) {
	e.preventDefault();
});

	$.fn.datepicker.dates['es'] = {
		days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
		daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
		daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
		months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		today: "Hoy",
		clear: "Borrar",
		format: "dd/mm/yyyy",
		titleFormat: "MM yyyy",
		weekStart: 1
	};

	$(function(){
		$('.input_fecha, #input_fecha_ini, #input_fecha_fin').datepicker({
			orientation: 'bottom',
			language: 'es'
		});
	});

	$('.input_hora, #input_hora_ini, #input_hora_fin').timepicker({
		minuteStep: 1,
		secondStep: 1,
		useCurrent: false,
		format : 'DD/MM/YYYY HH:mm:ss',
		showMeridian: false,
		showSeconds: true,
	})


if( $('#form-login.admin').length > 0) {
	$('.login-button').click(function(e) {
		if(compruebaDatos()){
			$.ajax({
				url: './assets/lib/admin_control.php',
				type: 'post',
				dataType: 'html',
				data: $('#form-login').serialize()
			})
			.done(function(result) {
				var result = $.parseJSON(result);
				if(result.res==0) {
					muestraMensajeLn(result.msg)
				}else if (result.res==1) {
					muestraMensajeLn(result.msg);
					setTimeout(function() {
						window.location.href = "./";
					},2000);
				}
			})
			.fail(function() {
				alert("error");
			});
		}
	});
}

function exit() {
	$.ajax({
		url: './assets/lib/admin_control.php',
		type: 'post',
		dataType: 'text',
		data: { accion: 'logout' },
	})
	.done(function(result) {
		var result = $.parseJSON(result);
			muestraMensajeLn(result.msg);
			setTimeout(function() {
				window.location.href = './';
			},2000);
	})
	.fail(function() {
		alert("error");
	});
}
function muestraMensaje(mensaje) {
	$('#mensaje').text(mensaje).fadeIn('200', function() {
		setTimeout(function() {
			$('#mensaje').html(mensaje).fadeOut('200');
		},500);
	});
}
function muestraMensajeLn(mensaje) {
	$('header').addClass('overall');
	$('#mensaje').text(mensaje).fadeIn('200', function() {
		setTimeout(function() {
			$('header').removeClass('overall');
			$('#mensaje').html(mensaje).fadeOut('200');
		},2000);
	});
}
function muestraMensajeMl(mensaje) {
	$('header').addClass('overall');
	$('#mensaje').text(mensaje).fadeIn('200', function() {
		setTimeout(function() {
			$('header').removeClass('overall');
			$('#mensaje').html(mensaje).fadeOut('200');
		},6000);
	});
}
function validaEmail( email ) {
	emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
	if (emailRegex.test( email )) {
		return true;
	} else {
		msg = "La dirección email no es correcta";
		muestraMensajeLn ( msg );
		return false;
	}
}

function compruebaDatos() {
	if($('#input_email').val()=='' || $('#input_pass').val()=='') {
		muestraMensajeLn('Debe introducir email y contraseña');
		return false;
	}else {
		return true;
	}
}

$('td.izq img').click(function (e) {
	if( $(window).width() >= 768 ) {
		e.preventDefault();
		$('.fondo img').attr('src', $(this).attr('src'));
		$('.fondo .txt-fondo').text($(this).attr('title'));
		$('.fondo').fadeIn();
	}
});

function openModalConf( url) {
	var myModal = new bootstrap.Modal(document.getElementById('configuracion'), {
		keyboard: false
	})
	$.ajax({
		url: './includes/' + url + '.php',
		type: 'POST',
		datatype: 'html',
	})
	.done(function(result) {
		$('#configuracion .modal-body').html(result);
		myModal.show();

	})
	.fail(function() {
		alert('Se ha producido un error');
	})
}

$('.act_des').click(function(e) {
		$('i', this).toggleClass('fa-check-square fa-square far fas on');
});

function cambiaEstado ( elemento, id, estado ) {

	$.ajax({
		url: './assets/lib/admin_control.php',
		type: 'post',
		dataType: 'text',
		data: {"accion": "cambia_estado_" + elemento , "id": id, "estado": estado}
})
	.done(function(result) {
		var result = $.parseJSON(result);
		if(result.res==0) {
			muestraMensajeLn(result.msg);
		}else if (result.res==1) {
			muestraMensajeLn(result.msg);
			// setTimeout(function() {
			// 	location.reload();
			// },2000);
		}
	})
	.fail(function() {
		alert("error");
	});
}





