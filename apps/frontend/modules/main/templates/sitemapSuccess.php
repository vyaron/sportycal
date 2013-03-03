<?xml?>
<?php
//header('Content-type: text/xml');
$strLines = $sf_data->getRaw('lines');
//Utils::pp("$strLines!");
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.sitemaps.org/schemas/sitemap-image/1.1" xmlns:video="http://www.sitemaps.org/schemas/sitemap-video/1.1">
<?php //foreach ($lines as $line): ?>
<?php echo $strLines?>
<?php //endforeach; ?>
</urlset>
