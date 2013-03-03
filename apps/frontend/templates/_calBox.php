<?php 
	$eventsCount	= $cal->getNumEvents(); 
	$calUpdated 	= $cal->getUpdatedAt();
?>

        <td class="tdCalLeft">&nbsp;</td>
        <td class="tdCal">
            <div class="divCalLeft">
                <a href="<?php echo url_for($cal->getUrl(ESC_RAW))?>" class="linkCal" title="<?php echo $cal->getName()?>">
                    <?php echo image_tag($cal->getTheImagePath(), '" class="imgCal" alt="'.$cal->getName().'" title="'.$cal->getName().'"')?>    
                    <div class="spnCalName"><?php echo $cal->getNameLimited() ?></div>
                </a>
            </div>
        </td>
        <td class="tdCalInfo tGray">
            <table cellpadding=0 cellspacing=0>
                <?php if ($calUpdated): ?>            	
                <tr>
                    <td>
                        <?php echo __('Updated:') ?>
                    </td>
                    <td>
                        <?php echo format_date($calUpdated, 'p') ?>
                    </td>
                </tr>
                <?php endif ?>
                <tr>
                    <td>
                        <?php echo __('By:') ?>
                    </td>
                    <td>
                        <?php //echo link_to($cal->getByUserName(), 'user/show?id='.$cal->getByUserId(), 'class="link"') ?>
                        <?php echo $cal->getByUserName() ?>
                    </td>
                </tr>
                <?php if ($eventsCount): ?>
                <tr>
                    <td>
                        <?php echo __('Contains:') ?>
                    </td>
                    <td>
                        <?php echo $eventsCount ." " . __('Events'); ?>
                    </td>
                </tr>
                <?php endif ?>

                <tr>
                    <td colspan="2" class="c tDarkGray">
                        <?php echo $cal->getDateRange() ?>
                    </td>
                </tr>

            </table>
            
        </td>
        <td class="tdCalRight">&nbsp;</td>

