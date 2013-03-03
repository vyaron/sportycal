<?php
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="sportYcal_calReqs.csv"');

?>
Id,Type,When,What,Context
<?php foreach ($calReqs as $calReq): ?>
    <?php echo $calReq->getAsCSV() ?>    
<?php endforeach; ?>
