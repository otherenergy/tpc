<?php
$urls_activo['es'] = array (
	'',
	'/',
	'/blog',
	'/microcemento-listo-al-uso',
	'/pintura-azulejos-smartcover',
	'/hormigon-impreso',
	'/distribuidores',
	'/login',
	'/reformas-diy',
	'/signup',
	'/smartcret/',
	'/smartcret/blog/',
	'/smartcret/microcemento-listo-al-uso',
	'/smartcret/pintura-azulejos-smartcover',
	'/smartcret/hormigon-impreso',
	'/smartcret/distribuidores',
	'/smartcret/login',
	'/smartcret/reformas-diy',
	'/smartcret/signup'
);

if ( ( !in_array( $_SERVER['REQUEST_URI'], $urls_activo[ 'es' ] ) ||
       strpos( $_SERVER['REQUEST_URI'], '/smartcret/blog/') !== false || strpos( $_SERVER['REQUEST_URI'], '/blog/' ) !== false ) ) {
?>

<style>
	#calcula-box {
		opacity: 1;
		background-color: #92bf23;
		display: inline-block;
		width: 190px;
		border-radius: 12px;
		padding: 10px 10px 10px 13px;
		position: fixed;
		bottom: 103px;
		right: 30px;
		color: #fff;
		font-size: 14px;
		font-weight: 500;
		line-height: 16px;
	}
#calcula-box:hover {
	opacity: 1;
	cursor: pointer;
}
#calcula-box .texto_completo {
	display: none;
	margin-top: 6px;
}
#calcula-box {
	z-index: 99;
}
.texto_completo p {
    margin-top: 10px;
}
.texto_completo a.btn {
    width: 100%;
    background-color: #35471e;
    border: 1px solid #35471e;
    color: #fff;
    margin-top: 10px;
}
.texto_completo a.btn:hover {
    background-color: transparent;
    color: #35471e;
}
.animacion {
    width: 55px;
    height: 55px;
    position: absolute;
    background-position: 1px -8px;
    background-size: 120%;
    background-repeat: no-repeat;
    border-radius: 100%;
    top: -36px;
    left: -35px;
    border: 3px solid #a8cc4f;
    z-index: -19;
}


@media only screen and (max-device-width: 762px) {
	#calcula-box {
    bottom: 20px;
    right: 85px;
    font-size: 13px;
    width: 248px;
  }
}

</style>

<div id="calcula-box">
	<div class="contenido">
		<div class="animacion" style="background-image: url('assets/img/calculadora.gif')"></div>
		<div translate-tag class="texto_entrada">
			<!-- <div class="animacion" style="background-image: url('assets/img/calculadora.gif')"></div> -->
			CALCULA EL PRECIO DE TU REFORMA SIN OBRAS CON SMARTCRET
		</div>
		<div translate-tag class="texto_completo">
			<p translate-tag>¿Cuánto te costará poner microcemento en tu casa?</p>
			<p translate-tag>¿Y usar pintura para azulejos? ¿Reparar hormigón impreso?</p>
			<p translate-tag>Descúbrelo ahora y házte DIY lover.</p>
			<a translate-link href="calculadora-presupuestos" translate-tag class="btn">Calcular gratis</a>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){

	$("#calcula-box").on('click', function () {
		if ($("#calcula-box").hasClass('open')) {
			$('#calcula-box .texto_completo').slideUp()
		}else {
			$('#calcula-box .texto_completo').slideDown()
		}
		$(this).toggleClass('open')
 	});
});
</script>

<?php } ?>