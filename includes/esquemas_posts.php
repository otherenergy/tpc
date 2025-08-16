<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
      "@type": "WebPage",
      "@id": "https://www.smartcret.com/<?php echo $idioma_url ?>/blog/<?php echo $url ?>"
  },
  "headline": "<?php echo $post_contenido[0]->h1 ?>",
  "image": "<?php echo $image_url ?>",
  "datePublished": "<?php echo $post_contenido[0]->fecha; ?>",
  "dateModified": "<?php echo $post_contenido[0]->fecha; ?>",
  "author": {
      "@type": "Organization",
      "name": "Smartcret",
      "logo": "https://www.smartcret.com/assets/img/logo-smartcret.png",
      "url": "https://www.smartcret.com"
  },
  "publisher": {
      "@type": "Organization",
      "url": "https://www.smartcret.com",
      "name": "Smartcret",
      "logo": {
          "@type": "ImageObject",
          "url": "https://www.smartcret.com/assets/img/logo-smartcret.png"
      }
  },
  "description": "<?php echo $post_contenido[0]->description ?>"
}
  </script>