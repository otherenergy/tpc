// console.log(vocabularios.anadir_carrito);
$(document).ready(function() {

	$(function () {
	  $('[data-bs-toggle="tooltip"]').tooltip()
	});

	// /*Alerta sobre envios a Canarias*/
	// $(document).on( 'change', '.form_dir_envio #input_prov', function(event) {
	// 	if ( $(this).val() == 35 || $(this).val() == 38 ) {
	// 		var msg = "Actualmente no podemos realizar pedidos a las Islas Canarias a través de la web. Por favor ponte en contacto con nosotros y te atenderemos personalmente con tu pedido:\n\n  Telefono/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/contacto";
	// 		alert( msg );
	// 		$("#input_prov").val("-").change();
	// 	}
	// });

	/*Alerta sobre envios USA*/
	// $(document).on( 'change', '.form_dir_envio #input_pais', function(event) {
	// 	if ( $(this).val() == 'US' ) {
	// 		var msg = "Actualmente no podemos realizar pedidos a EE.UU. a través de la web. Póngase en contacto con nosotros y le ayudaremos personalmente con su pedido:\n Teléfono/Whatsapp: +34 674 409 942 \n Email: info@smartcret.com\n www.smartcret.com/en/contact"
	// 		alert( msg );
	// 		$("#input_pais").val("ES").change();
	// 	}
	// });

  $('.nav-item.carrito a').click(function(event) {
  	$('#lista-productos').fadeIn('10');
  	$('#lista-productos').fadeIn('100', function() {$('.navbar-collapse').addClass('show')});
	if ( $(window).width() < 991) {
		$('#lista-productos').fadeIn('100', function() {$('#menu-header').addClass('ocultar')});
		$('.perfil').addClass('ocultar');
		$('.nav-item.carrito').addClass('ocultar');
		$('.idiomas').addClass('ocultar');
	}
  });

  $('#lista-productos .tit .fa-times').click(function(event) {
  	$('#lista-productos').fadeOut('100', function() {
  		$('.navbar-collapse').removeClass('show');
		$('#menu-header').removeClass('ocultar');
		$('.perfil').removeClass('ocultar');
		$('.carrito').removeClass('ocultar');
		$('.idiomas').removeClass('ocultar');
  	});
  });

  $('.perfil a').click(function(event) { //AÑADIDO
	$('#menu-usuario').fadeIn('10');
	$('#menu-usuario').fadeIn('100', function() {$('.navbar-collapse').addClass('show')});
	if ( $(window).width() < 991) {
		$('#menu-usuario').fadeIn('100', function() {$('#menu-header').addClass('ocultar')});
		$('.perfil').addClass('ocultar');
		$('.nav-item.carrito').addClass('ocultar');
		$('.idiomas').addClass('ocultar');
	}
  });

  $('#menu-usuario .tit .fa-times').click(function(event) { //AÑADIDO
	$('#menu-usuario').fadeOut('100', function() {
	  $('#menu-header').removeClass('ocultar');
	  $('.navbar-collapse').removeClass('show');
	  $('.perfil').removeClass('ocultar');
	  $('.nav-item.carrito').removeClass('ocultar');
	  $('.idiomas').removeClass('ocultar');
	});
  });


//   $("<div class='envios'>* Nuestros plazos de entrega son de 48-72h (hábiles) desde que el pedido se prepara y sale de nuestro almacen (para envíos a la Península) y de una semana (7 días hábiles) para los pedidos a Baleares, Canarias, Ceuta y Melilla.</div>").insertAfter('.info_producto .desc_product .tocart');
//   $('<div class="metodos-pago"><img src="./assets/img/metodos-pago.jpg" alt="Metodos de pago"></div>').appendTo('.desc_product form');





//NUEVA VERSIÓN-->

var productos_img_var = document.querySelectorAll('#productos_img_var');

if (productos_img_var.length > 0) {

	var contenedor_video_producto = document.getElementById("contenedor_video_producto");
	var img = document.getElementById("img-principal-prod");

	contenedor_video_producto.setAttribute("class", "ocultar");
	img.setAttribute("class", "img_product");

	var i = 0;
	$('.sel-color').not('.agotado').click(function (e) {
		var video = document.getElementById("miVideo");
		video.pause();
	
		$('#contenedor_video_producto').attr('class', 'ocultar');
		$('#img-principal-prod').attr('class', 'img_product');
	
		// Selecciona la imagen dentro del contenedor clickeado
		var img = $(this).find('img');
	
		$('img.img_product').attr('src', img.attr('src'));
		$('img.img_product').attr('color', img.attr('color'));
	
		var color = img.attr('color');
		if (color && color.trim() != '') {
			$('p.p_color_ncs').html('NCS: <br>' + color);
		}
	
		$('.video_product_activado').attr('class', 'video_products');
	
		if (!$('.close-img')[0]){
			$('div.img_product').append('<i class="close-img fa fa-times"></i>');
		}
	});
	


	$('.imgs_products').click(function (e) {
		$('.img_product .rebaja').css('z-index', '1000');
		$('p.p_color_ncs').html('');

		i = 0;
		$('.close-img').remove();
		var video = document.getElementById("miVideo");
		video.pause();

		$('#contenedor_video_producto').attr('class', 'ocultar');
		$('#img-principal-prod').attr('class', 'img_product');
		$('img.img_product').attr('src', $(this).attr('src'));
		$('.imgs_products').attr('class', 'imgs_products');

		$('p.p_color_ncs').html('');

		$('.video_product_activado').attr('class', 'video_products');

		$(this).attr('class', 'imgs_products img_product_activado');

		if (i == 0) $('img.img_product').attr('img-prod', $('img.img_product_activado').attr('src'));
		
	});

	$('.video_products').click(function(event) {
		$('.img_product .rebaja').css('z-index', '-1');

		$('.imgs_products').attr('class', 'imgs_products');
		$('.video_products').attr('class', 'video_products');
		contenedor_video_producto.classList.remove("ocultar");
		img.setAttribute("class", "ocultar");
		contenedor_video_producto.classList.add("mostrar");
		this.setAttribute("class", "video_products video_product_activado");
		video_product_activado = this.getAttribute("video");

		$('p.p_color_ncs').html('');

		var precio = '<video style=" z-index: 52 !important; " width="100%" id="miVideo" controls autoplay><source src="' + video_product_activado + '" type="video/mp4"></video>';
		$('#div-video-home').html(precio);

		var video = document.getElementById("miVideo");
		video.style.display = "block";
		video.play();

		video.addEventListener('ended', terminoVideo);
		function terminoVideo() {
			video.style.display ="none";
			contenedor_video_producto.setAttribute("class", "ocultar");
			img.setAttribute("class", "img_product");
		}

	});

	$(document).on('click', '.close-img', function(event) {
		$('.close-img').remove();
		$('img.img_product').attr('src', $('img.img_product').attr('img-prod'));
		$('.class_imgs_products').attr('class', 'class_imgs_products')
		$('p.p_color_ncs').html('');

	});
}else{
	var i = 0;
	$('.sel-color img').not('.sel-color.agotado img').click(function (e) {
		if (i == 0) $('img.img_product').attr('img-prod', $('img.img_product').attr('src'));
		i = 1;
		$('img.img_product').attr('src', $(this).attr('src'));
		if (!$('.close-img')[0]){
			$('div.img_product').append('<i class="close-img fa fa-times"></i>');
		}
	});
	$(document).on('click', '.close-img', function(event) {
		$('.close-img').remove();
		$('img.img_product').attr('src', $('img.img_product').attr('img-prod'));
	});
}

//<--

	$('.img_galeria, .colores-hormigon-impreso img').click(function (e) {
        if( $(window).width() >= 768 ) {
            e.preventDefault();
            $('.fondo img').attr('src', $(this).attr('src'));
            $('.fondo .txt-fondo').text($(this).attr('title'));
            $('.fondo').fadeIn();
        }
    });

	  $('.img_color').click(function (e) {
        if( $(window).width() >= 768 ) {
            e.preventDefault();
            $('html').css('overflow', 'hidden');
            $('.fondo img').attr('src', $(this).attr('src-zoom'));
            $('.fondo .txt-fondo').text($(this).attr('title'));
            $('.fondo').fadeIn();
        }
    });
    $('.fondo, .fondo .close').click(function (e) {
        e.preventDefault();
        $('.fondo').fadeOut();
        $('html').css('overflow', 'auto');
    });


	// if ( $('body.carrito').length > 0 && $(window).width() < 768) {
	// 	$('.btns').appendTo('.col-md-3');
	// 	$('mess.span.cab.perfil').insertAfter('a.navbar-brand.logo');
	// }
	// if ( $(window).width() < 768) {
	// 	$('li.nav-item.perfil').appendTo('#icons');
	// 	$('li.nav-item.carrito').appendTo('#icons');
	// 	$('li.nav-item.idiomas').appendTo('#icons');

	// }

	// // if ( $('body.carrito').length > 0 && $(window).width() < 991) {
	// // 	$('.btns').appendTo('.col-md-3');
	// // 	$('mess.span.cab.perfil').insertAfter('a.navbar-brand.logo');
	// // }
	// if ( $(window).width() < 991) {
	// 	$('li.nav-item.perfil').appendTo('#icons');
	// 	$('li.nav-item.carrito').appendTo('#icons');
	// 	$('li.nav-item.idiomas').appendTo('#icons');

	// }

	$('.sel-color').not('.agotado').click(function(event) {
		$('.sel-color').removeClass('selected');
		$(this).addClass('selected');
		$('#submit').attr('color', $(this).attr('color'));
	});

	$('.sel-acabado').click(function(event) {
		$('.sel-acabado').removeClass('selected');
		$(this).addClass('selected');
		$('#submit').attr('acabado', $(this).attr('acabado'));
	});

	$('.sel-juntas').click(function(event) {
		$('.sel-juntas').removeClass('selected');
		$(this).addClass('selected');
		$('#submit').attr('juntas', $(this).attr('juntas'));
	});

	$('.sel-formato').click(function(event) {
		$('.sel-formato').removeClass('selected');
		$(this).addClass('selected');
		$('#submit').attr('formato', $(this).attr('formato'));
	});

	$(document).on('click', '.click_valora', function(event) {

			sku = $(this).attr('sku');
			url = 'lista_valoraciones';

			var myModal = new bootstrap.Modal(document.getElementById('datos'), {
			  keyboard: false
			})
			$.ajax({
				url: './includes/' + url + '.php',
				type: 'GET',
				datatype: 'html',
				data: {sku:sku}
			})
			.done(function(result) {
					$('#datos .modal-body').html(result);
					myModal.show();

				})
			.fail(function() {
				alert(vocabularios.vocabulario_se_ha_producido_un_error);
			})
		});

	$(document).on('click', '.ico_preguntas', function(event) {

			sku = $(this).attr('sku');
			url = 'lista_preguntas';

			var myModal = new bootstrap.Modal(document.getElementById('datos'), {
			  keyboard: false
			})
			$.ajax({
				url: './includes/' + url + '.php',
				type: 'GET',
				datatype: 'html',
				data: {sku:sku}
			})
			.done(function(result) {
					$('#datos .modal-body').html(result);
					myModal.show();

				})
			.fail(function() {
				alert(vocabularios.vocabulario_se_ha_producido_un_error);
			})
		});

	$(document).on('click', '.contadores .btn-mas', function(event) {
		var selector=$(this).closest('.contadores').find('input');
		var valor=(Number(selector.val())>=999)?999:Number(selector.val())+1;
		selector.val(valor).change();
		$('#submit').attr('cantidad', valor);
	});

	$(document).on('click', '.contadores .btn-menos', function(event) {
		var selector=$(this).closest('.contadores').find('input');
		var valor=(Number(selector.val())==1)?1:Number(selector.val())-1;
		selector.val(valor).change();
		$('#submit').attr('cantidad', valor);
	});

	$('#boton-arriba').click(function(event) {
		$("html, body").animate({ scrollTop: 0 }, 500);
	});

	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}
	function checkScroll(scroll) {
		if (scroll > 20) {
			if ( $('nav.navbar').length > 0 ) {
				$('nav.navbar').addClass("fixed");
			}
		}
		else {
			$('nav.navbar').removeClass("fixed");
		}
	}

	$(".perfil .nav-link").click(function(event) {
		$('.menu-user').fadeIn();
	});

	// $('.td-unidades #cant').change(function(e) {
	// 	var uid=$(this).closest('tr').attr('uid');
	// 	var cantidad=$(this).val();

	// 	$.ajax({
	// 		url: '../assets/lib/carrito.php',
	// 		type: 'post',
	// 		datatype: 'text',
	// 		data: {'accion': '8', 'uid': uid, 'cantidad': cantidad}
	// 	})
	// 	.done(function(result) {
	// 		// alert(result);
	// 		window.location.reload();
	// 	})
	// 	.fail(function() {
	// 		alert("error");
	// 	});
	// });

	$('.producto-cantidad #cant').change(function(e) {
		var uid = $(this).closest('.producto-item').attr('uid');
		var cantidad = $(this).val();
	
		$.ajax({
			url: '../assets/lib/carrito.php',
			type: 'post',
			datatype: 'text',
			data: {'accion': '8', 'uid': uid, 'cantidad': cantidad}
		})
		.done(function(result) {
			// alert(result);
			window.location.reload();
		})
		.fail(function() {
			alert("error");
		});
	})

	$('#aplica_descuento').click(function(e){
		$.ajax({
			url: '../class/control.php',
			type: 'POST',
			datatype: 'html',
			data: {accion: 'aplica_codigo_descuento', nombre_descuento: $('#nombre_descuento').val()}
		})
		.done(function(result) {
			var result = $.parseJSON(result);
			muestraMensajeLn(result.msg);
			setTimeout( function() {
				location.reload();
			},2000);
		})
		.fail(function() {
			alert(vocabularios.vocabulario_se_ha_producido_un_error);
		})
	});

});

$('#form-login, #form-, form.desc').submit(function(e) {
	e.preventDefault();
});

if( $('.login-form').length > 0) {
	$('.login-button').click(function(e) {
		if(compruebaDatos()){
			$.ajax({
				url: '../class/control.php',
				type: 'post',
				dataType: 'html',
				data: $('#form-login').serialize()
			})
			.done(function(result) {
				var result = $.parseJSON(result);
				if(result.res==0) {
					muestraMensajeAlerta(result.msg)
				}else if (result.res==1) {
					muestraMensajeLn(result.msg);
					setTimeout(function() {
						// window.location.href = "../"+idiomaUrl;
						location.reload()
					},2000);
				}
			})
			.fail(function() {
				alert("error");
			});
		}
	});
}

$('#form-registro').submit(function(e) {
    e.preventDefault();
});

if ($('.registro-form').length > 0) {
    $('.registro-button').click(function(e) {
        e.preventDefault(); // Previene el envío del formulario

        if (compruebaDatosRegistro()) {
            $.ajax({
                url: '../class/control.php',
                type: 'post',
                dataType: 'text',
                data: $('#form-registro').serialize()
            })
            .done(function(result) {
                try {
                    var parsedResult = $.parseJSON(result);
                    muestraMensajeLn(parsedResult.msg);
                    if (parsedResult.res == 0) {
                        muestraMensajeLn(parsedResult.msg);
                    } else if (parsedResult.res == 1) {
                        muestraMensajeLn(parsedResult.msg);
                        setTimeout(function() {
                            location.reload()
                        }, 2000);
                    }
                } catch (e) {
                    console.error('Error parsing JSON response: ', e);
                    muestraMensajeLn('Error en la respuesta del servidor.');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error in AJAX request: ', textStatus, errorThrown);
                alert("error");
            });
        }
    });
}

function compruebaDatosRegistro() {
    if ($('#input_email').val() == '' || $('#input_pass').val() == '' || $('#input_nombre').val() == '') {
        muestraMensajeLn(vocabularios.vocabulario_debe_introducir_nombre_email_contrasena);
        return false;
    } else if ($('#input_pass').val() != $('#input_repite_pass').val()) {
        muestraMensajeLn(vocabularios.vocabulario_contrasenas_no_coinciden);
        return false;
    } else if (!validaEmail($('#input_email').val())) {
        muestraMensajeLn(vocabularios.vocabulario_email_no_valido);
        return false;
    } else {
        return true;
    }
}

function validaEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}


function eliminaCupon() {
			$.ajax({
				url: '../class/control.php',
				type: 'POST',
				datatype: 'html',
				data: {accion: 'elimina_cupon_descuento'}
			})
			.done(function(result) {
				var result = $.parseJSON(result);
		 		muestraMensajeLn(result.msg);
		 		setTimeout( function() {
		 			location.reload();
		 		},2000);
			})
			.fail(function() {
				alert(vocabularios.vocabulario_se_ha_producido_un_error);
			})
		}


function exit( path ) {
	$.ajax({
		url: path+'class/control.php',
		type: 'post',
		dataType: 'text',
		data: { accion: 'logout' },
	})
	.done(function(result) {
		var result = $.parseJSON(result);
			muestraMensajeLn(result.msg);
			setTimeout(function() {
				window.location.href = path+idiomaUrl;
			},2000);
	})
	.fail(function() {
		alert("error");
	});
}

function compruebaDatos() {
	if($('#input_email').val()=='' || $('#input_pass').val()=='') {
		muestraMensajeLn(vocabularios.debe_introducir_con);
		return false;
	}else {
		return true;
	}
}

function validaDni(abc) {
	dni=abc.substring(0,abc.length-1);
	le=abc.charAt(abc.length-1);
	if (!isNaN(le)) {
		return false;
	}else{
		cadena="TRWAGMYFPDXBNJZSQVHLCKET";
		posicion = dni % 23;
		letra = cadena.substring(posicion,posicion+1);
		if (letra!=le.toUpperCase()) {
			return false;
		}
	}
	return true;
}

function addProductCar($element){

	var accion = 1;
	var idProd = $element.attr('idProd');
	var variante = $element.attr('variante');
	var color = $element.attr('color');
	var acabado = $element.attr('acabado');
	var juntas = $element.attr('juntas');
	var formato = $element.attr('formato');
	var cantidad = $element.attr('cantidad');
	var id_idioma = $element.attr('id_idioma');

	$.ajax({
		url: '../assets/lib/carrito.php',
		type: 'POST',
		datatype: 'json',
		data: {accion: accion, idProd: idProd, variante: variante, color: color, acabado: acabado, formato: formato, juntas: juntas, cantidad: cantidad, id_idioma: id_idioma },
	})
	.done(function(result) {
		var result = $.parseJSON(result);
			// alert(result.texto);
			$('#numprod').text(result.numProd);
			//MENSAJE DE DESCRIPCIÓN DE PRODUCTO
			// muestraMensajeLn(result.texto);
			fnc_carrito_header()
			actualizaCarrito("../");

		})
	.fail(function() {
		alert(vocabularios.vocabulario_se_ha_producido_un_error);
	})
}

function eliminaArticulo(uid, ruta_link){
    console.log("Eliminar",ruta_link)
    $.ajax({
        url: ruta_link+'assets/lib/carrito.php',
        type: 'POST',
        datatype: 'json',
        data: { accion: 5, uid: uid },
    })
    .done(function(result) {
        var result = $.parseJSON(result);
        muestraMensaje(result.texto);
        $('#numprod').text(result.numProd);
        // Llamar a actualizaCarrito después de eliminar el artículo
        actualizaCarrito(ruta_link);
    })
    .fail(function() {
        alert(vocabularios.vocabulario_se_ha_producido_un_error);
    })
}

function eliminaDireccion(id, tipoDir, ruta_link){

	$.ajax({
		url: ruta_link+'class/control.php',
		type: 'POST',
		datatype: 'json',
		data: { accion: 'elimina_direccion', id: id, 'tipo_direccion': tipoDir },
	})
	.done(function(result) {
		var result = $.parseJSON(result);
			muestraMensajeLn(result.msg);
			setTimeout( function() {
			location.reload();
		},2000);

		})
	.fail(function() {
		alert(vocabularios.vocabulario_se_ha_producido_un_error);
	})
}

function eliminaArticuloListado(uid){

	$.ajax({
		url: '../assets/lib/carrito.php',
		type: 'POST',
		datatype: 'json',
		data: { accion: 5, uid: uid },
	})
	.done(function(result) {
		var result = $.parseJSON(result);
		muestraMensaje(result.texto);
		$('#numprod').text(result.numProd);
		setTimeout( function() {
			location.reload();
		},1500);
	})
	.fail(function() {
		alert(vocabularios.vocabulario_se_ha_producido_un_error);
	})
}

function vaciarCarrito(){

	$.ajax({
		url: '../assets/lib/carrito.php',
		type: 'POST',
		datatype: 'json',
		data: { accion: 6},
	})
	.done(function(result) {
		var result = $.parseJSON(result);
		muestraMensajeLn(result.texto);
		setTimeout( function() {
			location.reload();
		},2000);
	})
	.fail(function() {
		alert(vocabularios.vocabulario_se_ha_producido_un_error);
	})
}


function finalizaPedido(){

	$.ajax({
		url: './assets/lib/carrito.php',
		type: 'POST',
		datatype: 'json',
		data: { accion: 6},
	})
	.done(function(result) {
		window.location.href="./";
	})
	.fail(function() {
		alert(vocabularios.vocabulario_se_ha_producido_un_error);
	})
}

function actualizaCarrito(ruta_link){
    console.log("Actualizar", ruta_link)
    $.ajax({
        url: ruta_link+'includes/actualiza_carrito.php',
        type: 'POST',
        datatype: 'html',
    })
    .done(function(result) {
        $('#lista-productos').html(result);
        $('.nav-item.carrito a').click(function(event) {
            $('#lista-productos').fadeIn('10');
            $('#lista-productos').fadeIn('100', function() {$('.navbar-collapse').addClass('show')});
            if ( $(window).width() < 991) {
                $('#lista-productos').fadeIn('100', function() {$('#menu-header').addClass('ocultar')});
                $('.perfil').addClass('ocultar');
                $('.nav-item.carrito').addClass('ocultar');
                $('.idiomas').addClass('ocultar');
            }
        });

        $('#lista-productos .tit .fa-times').click(function(event) {
            $('#lista-productos').fadeOut('100', function() {
                $('.navbar-collapse').removeClass('show');
                $('#menu-header').removeClass('ocultar');
                $('.perfil').removeClass('ocultar');
                $('.carrito').removeClass('ocultar');
                $('.idiomas').removeClass('ocultar');
            });
        });

        $('.perfil a').click(function(event) { //AÑADIDO
            $('#menu-usuario').fadeIn('10');
            $('#menu-usuario').fadeIn('100', function() {$('.navbar-collapse').addClass('show')});
            if ( $(window).width() < 991) {
                $('#menu-usuario').fadeIn('100', function() {$('#menu-header').addClass('ocultar')});
                $('.perfil').addClass('ocultar');
                $('.nav-item.carrito').addClass('ocultar');
                $('.idiomas').addClass('ocultar');
            }
        });

        $('#menu-usuario .tit .fa-times').click(function(event) { //AÑADIDO
            $('#menu-usuario').fadeOut('100', function() {
                $('#menu-header').removeClass('ocultar');
                $('.navbar-collapse').removeClass('show');
                $('.perfil').removeClass('ocultar');
                $('.nav-item.carrito').removeClass('ocultar');
                $('.idiomas').removeclass('ocultar');
            });
        });
    })
    .fail(function() {
        alert(vocabularios.vocabulario_se_ha_producido_un_error);
    })
}

// function actualizaCarritoSegundoNivel(){
// 	$.ajax({
// 		url: '../includes/actualiza_carrito.php',
// 		type: 'POST',
// 		datatype: 'html',
// 	})
// 	.done(function(result) {
// 			$('#lista-productos').html(result);
// 			$('.nav-item.carrito a').click(function(event) {
// 				$('#lista-productos').fadeIn('10');
// 				$('#lista-productos').fadeIn('100', function() {$('.navbar-collapse').addClass('show')});
// 			  if ( $(window).width() < 991) {
// 				  $('#lista-productos').fadeIn('100', function() {$('#menu-header').addClass('ocultar')});
// 				  $('.perfil').addClass('ocultar');
// 				  $('.nav-item.carrito').addClass('ocultar');
// 				  $('.idiomas').addClass('ocultar');
// 			  }
// 			});

// 			$('#lista-productos .tit .fa-times').click(function(event) {
// 				$('#lista-productos').fadeOut('100', function() {
// 					$('.navbar-collapse').removeClass('show');
// 				  $('#menu-header').removeClass('ocultar');
// 				  $('.perfil').removeClass('ocultar');
// 				  $('.carrito').removeClass('ocultar');
// 				  $('.idiomas').removeClass('ocultar');
// 				});
// 			});

// 			$('.perfil a').click(function(event) { //AÑADIDO
// 			  $('#menu-usuario').fadeIn('10');
// 			  $('#menu-usuario').fadeIn('100', function() {$('.navbar-collapse').addClass('show')});
// 			  if ( $(window).width() < 991) {
// 				  $('#menu-usuario').fadeIn('100', function() {$('#menu-header').addClass('ocultar')});
// 				  $('.perfil').addClass('ocultar');
// 				  $('.nav-item.carrito').addClass('ocultar');
// 				  $('.idiomas').addClass('ocultar');
// 			  }
// 			});

// 			$('#menu-usuario .tit .fa-times').click(function(event) { //AÑADIDO
// 			  $('#menu-usuario').fadeOut('100', function() {
// 				$('#menu-header').removeClass('ocultar');
// 				$('.navbar-collapse').removeClass('show');
// 				$('.perfil').removeClass('ocultar');
// 				$('.nav-item.carrito').removeClass('ocultar');
// 				$('.idiomas').removeClass('ocultar');
// 			  });
// 			});

// 		})
// 	.fail(function() {
// 		alert(vocabularios.vocabulario_se_ha_producido_un_error);
// 	})
// }

function muestraMensaje(mensaje) {
	$('#mensaje').html(mensaje).fadeIn('200', function() {
		setTimeout(function() {
			$('#mensaje').html(mensaje).fadeOut('200');
		},500);
	});
}
function muestraMensajeAlerta(mensaje) {
	$('header').addClass('overall');
	$('#mensajeAlerta').html(mensaje).fadeIn('200', function() {
		setTimeout(function() {
			$('header').removeClass('overall');
			$('#mensajeAlerta').html(mensaje).fadeOut('200');
		},2000);
	});
}
function muestraMensajeLn(mensaje) {
	$('header').addClass('overall');
	$('#mensaje').html(mensaje).fadeIn('200', function() {
		setTimeout(function() {
			$('header').removeClass('overall');
			$('#mensaje').html(mensaje).fadeOut('200');
		},2000);
	});
}
function muestraMensajeMl(mensaje) {
	$('header').addClass('overall');
	$('#mensaje').html(mensaje).fadeIn('200', function() {
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
		msg = vocabularios.direccion_email_incorrecta;

		muestraMensajeLn ( msg );
		return false;
	}
}
// function cambioIdioma (){

// 	$.ajax({
// 		url: 'assets/lib/carrito.php',
// 		type: 'POST',
// 		datatype: 'json',
// 		data: { accion: 6},
// 	})
// 	.done(function(result) {
// 		var result = $.parseJSON(result);
// 		muestraMensajeLn(result.texto);
// 		setTimeout( function() {
// 			location.reload();
// 		},2000);
// 	})
// 	.fail(function() {
// 		alert(vocabularios.vocabulario_se_ha_producido_un_error);
// 	})
// }
// $('ul.dropdown-menu.dropdown-menu-light li').click(function(){ cambioIdioma() });

function cambioUrlEnvio ( url ){
	$.ajax({
		url: '../assets/lib/carrito.php',
		type: 'POST',
		datatype: 'json',
		data: { accion: 6 },
	})
	.done(function(result) {
		window.location.href = url;
	})
	.fail(function() {
		alert(vocabularios.vocabulario_se_ha_producido_un_error);
	})
}

// function cambioIdioma (id_idioma){


// }

function cambioIdioma (){
	console.log("Se ha cambiado de idioma");
}
$('ul.dropdown-menu.dropdown-menu-light li').click(function(){ cambioIdioma() });

function cambioUrlEnvio ( url ){
	$.ajax({
		url: '../assets/lib/carrito.php',
		type: 'POST',
		datatype: 'json',
		data: { accion: 6 },
	})
	.done(function(result) {
		window.location.href = url;
	})
	.fail(function() {
		alert(vocabularios.vocabulario_se_ha_producido_un_error);
	})
}

const anuncio = document.querySelector(".anuncio");
if(anuncio) {
    const icons = document.querySelector("ul#icons");

	if ( $(window).width() < 769) {
		if (icons) {
			icons.style.top = "90px";
		}
	}

	else{
		if (icons) {
			icons.style.top = "56px";
		}
	}

}

var menu_header = document.querySelector(".menu-header");
var lista_productos = document.querySelector("#lista-productos");
var close_hamburguesa = document.querySelector("#close-hamburguesa");
var menu_hamburguesa = document.querySelector(".menu-hamburguesa")

function fnc_menu_hamburguesa(){
	menu_header.classList.toggle("mostrar-header");
	close_hamburguesa.classList.toggle("ocultar");
	close_hamburguesa.classList.toggle("close-hamburguesa");
	menu_hamburguesa.classList.toggle("ocultar");
}

function fnc_carrito_header(){
	menu_header.classList.remove("mostrar-header");
	lista_productos.style.display = "block";
}

function fnc_ocultar_carrito_header(){
	menu_header.classList.add("mostrar-header");
	lista_productos.style.display = "none";
}

document.addEventListener("click", function (event) {
	if (!menu_header.contains(event.target) && !menu_hamburguesa.contains(event.target) && !close_hamburguesa.contains(event.target)) {
		menu_header.classList.remove("mostrar-header");
		close_hamburguesa.classList.add("ocultar");
		close_hamburguesa.classList.remove("close-hamburguesa");
		menu_hamburguesa.classList.remove("ocultar");
	}
});


window.addEventListener('scroll', function() {
	var nav_header = document.querySelector('.nav-header-principal');
	var logo_smartcret = document.querySelector('.img-logo-smartcret');
	var nav_logo_smartcret = document.querySelector('.contenedor-logo-smartcret')
	var nav_moverse_logo_smartcret = document.querySelector('.contenedor-moverse-logo-smartcret')

	var sectionTop = nav_header.offsetTop;
	var scrollPosition = window.scrollY;

	if (scrollPosition >= sectionTop - 20 && scrollPosition > 0) {
		nav_header.classList.add('estilos-scroll-header');
		logo_smartcret.classList.add('estilos-scroll-img-logo');
		menu_header.classList.add('estilos-scroll-menu-header');
		nav_logo_smartcret.classList.add('estilos-scroll-contenedor-logo-smartcret')
		nav_moverse_logo_smartcret.classList.add('contenedor-scroll-moverse-logo-smartcret')

	} else {
		nav_header.classList.remove('estilos-scroll-header');
		logo_smartcret.classList.remove('estilos-scroll-img-logo');
		menu_header.classList.remove('estilos-scroll-menu-header');
		nav_logo_smartcret.classList.remove('estilos-scroll-contenedor-logo-smartcret')
		nav_moverse_logo_smartcret.classList.remove('contenedor-scroll-moverse-logo-smartcret')

	}
});

