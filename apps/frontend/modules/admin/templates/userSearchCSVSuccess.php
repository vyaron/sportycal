<?php
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="sportYcal_userSearches.csv"');

?>
Id,When,What,UserId
<?php foreach ($userSearches as $userSearch): ?>
    <?php echo $userSearch->getAsCSV() ?>    
<?php endforeach; ?>
?>
