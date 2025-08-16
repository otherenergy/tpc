<section>
	<div class="container">
		<img class="slide-banos" src="assets/img/aplicacion-paso-paso.jpg" style="width:100%">
		<img class="slide-banos" src="assets/img/colores_smartcover.jpg" style="width:100%">
	</div>
	<script>
		var slideIndex = 0;
			carousel();

			function carousel() {
			  var i;
			  var x = document.getElementsByClassName("slide-banos");
			  for (i = 0; i < x.length; i++) {
				x[i].style.display = "none";
			  }
			  slideIndex++;
			  if (slideIndex > x.length) {slideIndex = 1}
			  x[slideIndex-1].style.display = "block";
			  setTimeout(carousel, 2000); // Change image every 2 seconds
			}
	</script>
</section>