<?php
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="sportYcal_calReqs.csv"');

?>

id,Link Type,Link Txt,Link Url Click,Link Url Dest,Cal Type,Context,Cal Name,When
<?php foreach ($linksUsage as $linkUsage): ?>
    <?php echo $linkUsage->getAsCSV() ?>    
<?php endforeach; ?>
