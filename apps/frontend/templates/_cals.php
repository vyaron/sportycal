<?php //use_helper('Date') ?>

<div class="calsWrapper">
<div class="centererOuter1">
<div class="centererInner1">


<table border="0px white" width="" cellspacing="0px" class="tblCals">
<?php if ($calsCount > 1) : ?>

    <?php for ($i=0; $i < $calsCount; $i+=1): ?>
    <tr class="trCal">
	    <?php include_partial('global/calBox',array('cal' => $cals[$i])) ?>

	    <?php if (!$fromFbApp):?>
	    	<td>&nbsp;&nbsp;&nbsp;</td>
		    <?php if (++$i < $calsCount) :?>
		    	<?php include_partial('global/calBox',array('cal' => $cals[$i])) ?>
		    <?php else :?>
		    	<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		    <?php endif ?>
	    <?php endif;?>

    </tr>
    <tr class="trCalSep"><td>&nbsp;</td></tr>
    <?php endfor; ?>
<?php elseif ($calsCount == 1) : ?>
    <tr class="trCal" >
	<?php include_partial('global/calBox',array('cal' => $cals[0])) ?>
    </tr>
<?php endif ?>
</table>

</div>
</div>
</div>