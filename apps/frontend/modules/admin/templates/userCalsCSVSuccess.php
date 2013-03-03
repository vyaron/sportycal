<?php
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="sportYcal_userCals.csv"');

?>
Id,Type,When,What,UserId,Context
<?php foreach ($userCals as $userCal): ?>
    <?php echo $userCal->getAsCSV() ?>    
<?php endforeach; ?>



