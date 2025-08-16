<?php
$userClass = new userClass();
$url_metas=$userClass->url_metas($id_url,$id_idioma);

include($ruta_link1 . 'includes/vocabulario.php');
include($ruta_link1 . 'includes/urls.php');
?>

<div class="modal fade gen" id="datos">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
    <div class="fondo fix-ed" style="display: none;">
        <div class="btn-close"><i class="fa fa-times"></i></div>
        <img class="centrado" src="" alt="">
        <div class="txt-image" style="text-align: center;"><div class="txt-fondo"></div></div>
    </div>
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
		<footer>
			<div id="boton-arriba"><img src="<?php echo $ruta_link1 ?>assets/img/chevron.png" alt="Arriba" width="25" height="14"></div>
			<div class="row">
				<div class="col-md-2 logo_footer"><a href="<?php echo $ruta_link2 ?>"><img src="<?php echo $ruta_link1 ?>assets/img/logo_smartcret_blanco.png" alt="Smartcret" title="Smartcret" height="122" width="250" class="logo_footer_img"></a></div>
				<div class="col-md-8 secciones_footer">
					<div class="row">
						<div class="col-md-4">
							<p class="secciones_footer_p"><?php echo mb_strtoupper($vocabulario_microcemento, 'UTF-8')?></p>
							<div class="barra_horizontal_footer"> </div>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_microcemento_listo_al_uso?>"><?php echo $vocabulario_microcemento_listo_al_uso?></a></p>
							<p class="secciones_footer_p"><?php echo mb_strtoupper($vocabulario_pintura, 'UTF-8')?></p>
							<div class="barra_horizontal_footer"> </div>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_pintura_smartcover?>"><?php echo $vocabulario_pintura_azulejos?></a></p>
							<p class="secciones_footer_p"><?php echo mb_strtoupper($vocabulario_hormigon_impreso, 'UTF-8')?></p>
							<div class="barra_horizontal_footer"> </div>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_hormigon_impreso?>"><?php echo $vocabulario_barniz_mortero_reparador?></a></p>
						</div>
						<div class="col-md-4">
							<p class="secciones_footer_p"><?php echo mb_strtoupper($vocabulario_nosotros, 'UTF-8')?></p>
							<div class="barra_horizontal_footer"> </div>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_fabricante_microcemento?>"><?php echo $vocabulario_fabrica?></a></p>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_distribuidores?>"><?php echo $vocabulario_distribuidores?></a></p>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_blog?>">Smart Blog</a></p>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_tienda?>"><?php echo $vocabulario_tienda?></a></p>
						</div>
						<div class="col-md-4">
							<p class="secciones_footer_p"><?php echo mb_strtoupper($vocabulario_ayuda, 'UTF-8')?></p>
							<div class="barra_horizontal_footer"> </div>
							<?php if (0) { ?>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_calculadora_presupuestos?>" rel="nofollow"><?php echo $vocabulario_calculadora_presupuestos?></a></p>
							<?php } ?>
							<p><a href="<?php echo $ruta_link2 ?><?php echo $link_contacto?>" rel="nofollow"><?php echo $vocabulario_contacto?></a></p>
							<p style="margin-bottom: 4px;"><a href="<?php echo $ruta_link2 ?><?php echo $link_politica_privacidad?>" rel="nofollow"><?php echo $vocabulario_politica_privacidad?></a></p>
							<p style="line-height: 20px !important"><a href="<?php echo $ruta_link2 ?><?php echo $link_politica_venta_devolucion?>" rel="nofollow"><?php echo $vocabulario_politica_venta_devolucion?></a></p>
						</div>
					</div>
				</div>
				<div class="col-md-2 secciones_footer">
					<p class="secciones_footer_p"><?php echo mb_strtoupper($vocabulario_siguenos, 'UTF-8')?></p>
					<div class="barra_horizontal_footer"> </div>
					<p class="secciones_footer_p">
                        <a href="https://www.instagram.com/smartcret/" rel="noopener" rel="noopener" target="_blank" rel="nofollow"><img src="<?php echo $ruta_link1 ?>assets/img/iconos/instagram.png" alt="Instagram Smartcret" title="Instagram Smartcret" style="width:35px;margin-right: 3%;" width="35" height="35"></a>
                        <a href="https://www.facebook.com/Smartcret/" rel="noopener" rel="noopener" target="_blank" rel="nofollow"><img src="<?php echo $ruta_link1 ?>assets/img/iconos/facebook.png" alt="Facebook Smartcret" title="Facebook Smartcret" style="width:35px;margin-right: 3%;" width="35" height="35"></a>
                        <a href="https://open.spotify.com/playlist/3c5eXu6mBL6D9zaiCBp11q?si=RviN-3LJTNizOLRJRIHNcg" rel="noopener" rel="noopener" target="_blank" rel="nofollow"><img src="<?php echo $ruta_link1 ?>assets/img/iconos/spotify.png" alt="Spotify Smartcret" title="Spotify Smartcret" style="width:35px;" width="35" height="35"></a>
                    </p>
					<div class="sep05"></div>
					<p class="secciones_footer_p"><?php echo mb_strtoupper($vocabulario_reformas_reales, 'UTF-8')?></p>
					<div class="barra_horizontal_footer"> </div>
					<p><a class="ref_diy" href="<?php echo $ruta_link2 ?><?php echo $link_reformas_diy?>" rel="nofollow"><span class="hashtag_footer">#</span>real<span class="hash_diy_footer">DIY</span></a></p>
				</div>
			</div>
			<?php
				include_once ( __DIR__ . '/mensaje_anuncio.php' );
				// include_once ( __DIR__ . '/pop_up_promocion.php');
				// include_once ( __DIR__ . '/pop_up.php');
				// include_once ( __DIR__ . '/calcula_box.php');

			?>
		</footer>
		<script style="display: none;">
			var vocabularios = <?php echo json_encode($vocabularioArray); ?>;
			// console.log(vocabularios); // Para verificar que todo está bien
		</script>
		<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
        </script> -->
		<script src="<?php echo $ruta_link1 ?>assets/js/custom-js.js"></script>
		<!-- <script type="text/javascript" id="zsiqchat">var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode: "siqe1107525b343a12feb6d1d056e283a7e957f5c97b7a7e50199dc2c6ea9e5d3ae", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zohopublic.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);</script> -->