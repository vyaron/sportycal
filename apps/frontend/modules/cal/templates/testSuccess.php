<ul>
<?php foreach ($events as $e) : ?>
<li>
<?php echo $e->getId();?> - <?php echo $e->getName();?>
</li>
<?php endforeach;?>


</ul>