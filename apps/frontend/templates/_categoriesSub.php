<?php 
	if (isset($fromFbApp)) $fromFbApp = true;
	else $fromFbApp = false;
	
	
?>

<div id="divAllCtgSub" >
<table id="tblCtgSub" border="0px" width="100%" cellspacing="10px">
    <tr>
    <?php for ($i=0; $i < $categoriesCount; $i+=1): ?>

        <td class="tdCtgSub" ctgId="<?php echo $categories[$i]->getId();?>">
            <a href="<?php echo url_for($categories[$i]->getUrl(ESC_RAW)) ?>" class="linkCtg <?php echo ($categories[$i]->getDeletedAt()) ? 'tdCtgSub_deleted' : '';?>">
                <?php echo $categories[$i]->getName() ?>
            </a>
        </td>
        <td class="tdCategorySubCals">
                [<?php echo $categories[$i]->getCalsCount() . ' ' . __('calendars'); ?>]
        </td>
        <td class="tdSpacerSub">
            &nbsp;
        </td>
        
        <!-- right column -->
        <?php if (!$fromFbApp):?>
        	<?php ++$i ?>
	        <td class="tdCtgSub" ctgId="<?php echo $categories[$i]->getId();?>">
	            <?php if ($i < $categoriesCount) :?>
	                <a href="<?php echo url_for($categories[$i]->getUrl(ESC_RAW)) ?>" class="linkCtg <?php echo ($categories[$i]->getDeletedAt()) ? 'tdCtgSub_deleted' : '';?>">
	                    <?php echo $categories[$i]->getName() ?>
	                </a>
	            <?php else  :?>
	                &nbsp;
	            <?php endif?>                
	
	        </td>
	        
	        <td class="tdCategorySubCals">
	            <?php if ($i < $categoriesCount) :?>
	                [<?php echo $categories[$i]->getCalsCount() . " " . __('calendars'); ?>]
	            <?php else  :?>
	                &nbsp;
	            <?php endif?>                
	
	        </td>
        <?php endif;?>
    </tr>
    <?php endfor; ?>

</table>
</div>


