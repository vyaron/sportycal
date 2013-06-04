<h2>Unsubscribe Success</h2>

<?php if ($mailingList->getCalId()):?>
<p>Calendar name: <?php echo $mailingList->getCal()->getName();?></p>
<?php else:?>
<p>Category Name: <?php echo $mailingList->getCategory()->getName();?></p>
<?php endif;?>

<p>Full Name: <?php echo $mailingList->getFullName();?></p>
<p>Email: <?php echo $mailingList->getEmail();?></p>