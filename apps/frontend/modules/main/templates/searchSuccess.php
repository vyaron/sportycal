<?php include_partial('global/search',array('txtSearch' => $txtSearch, 'fromDate' => $fromDate, 'toDate' => $toDate)) ?>

<?php
	if (count($categorys)){
		$resCount = count($categorys);
	} else if (count($events)){
		$resCount = count($events);
	} else {
		$resCount = 0;
	}
?>

<?php if (count($events) && !UserUtils::getUserTZ()):?>
	<script type="text/javascript" src="/js/getUserTZ.js"></script>
<?php endif;?>

<div style="text-align:left;clear:both">
    
    
    <div id="divCtgNav">
        <span class="ctgNav">
            <a href='<?php echo url_for("main/index")?>' > <?php echo __('Home');?> </a>
            <?php echo image_tag('layout/navSep.gif', '" class="imgCtgSub"')?>
            <?php echo __('Search Results');?>
        </span>
        <br/><br/>
        <p class="tGray" style="font-size: 12px;">
        	&nbsp;&nbsp;<?php echo __('Looked for');?>
            <?php if ($txtSearch) : ?>
                :
                <span class="strong">
        		<?php echo $txtSearch?>
        		</span>
			<?php else : ?>
				<?php echo __('Sport Events');?>        		
        	<?php endif; ?>
        	

        	<?php if ($fromDate) : ?>
        		<?php echo __('From');?>:         	
        		<span class="strong"><?php echo GeneralUtils::getDateForDisplay($fromDate)?></span>
        	<?php endif; ?>
        	<?php if ($toDate) : ?>
        		<?php echo __('To');?>:
        		<span class="strong"> <?php echo GeneralUtils::getDateForDisplay($toDate)?></span>
        	<?php endif; ?>
        	
        	  - <?php echo __('Found');?> <span class="strong"><?php echo $resCount?></span> <?php echo __('results');?> 
        </p><br/>
        <?php if ($resCount == 0): ?>
            <h3>&nbsp;&nbsp;<?php echo __('No results found, please try again');?></h3>
            <br/><br/>
            <center>
            <a href='<?php echo url_for("main/index")?>' > <?php echo __('Back to Home Page');?> </a>
            </center>
    
        <?php endif; ?>
        
    </div>
    
    <br/>
    
</div>


<div>
<div style="float: left; position: relative; left: 50%;">
<div style="float: left; position: relative; left: -50%;z-index:0">


    <div style="text-align:left;z-index:5;background:transparent;">

        <?php if (count($categorys) > 0): ?>

            <table border="0px" width="" cellspacing="0px" class="">
                <tr class="">
                    <td class="tdCorner tdBoxTopLeft"></td>
                    <td class="tdBoxTopMiddle"></td>
                    <td class="tdCorner tdBoxTopRight"></td>
                </tr>
                 <tr class="trBox">
                    <td class="tdBoxLeft"></td>
            
                    <td class="tdBox">
                        <?php echo image_tag('layout/searchResults.gif', '" style="float:left"')?>
            
                        <center>
                        <h2><?php echo __('Categories');?></h2><br/>
                        
                        </center>            
                            <table cellspacing="15px">
                              <thead>
                                <tr>
                                  <th><?php echo __('Name');?></th>
                                  <th><?php echo __('Calendars');?></th>
                                  <th>&nbsp;</th>
                                  <th><?php echo __('Context');?></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($categorys as $category): ?>
                                <tr>
                                  <td style="font-size:16px;min-width:150px;"><a href="<?php echo url_for($category->getUrl(ESC_RAW)) ?>"><?php echo $category->getName() ?></a></td>
                                  <td class="center" style="font-size:16px;"><?php echo $category->getCalsCount() ?></td>
                                  <td style="width:40px;">&nbsp;</td>
                                  <td class="small" style="max-width: 400px;"><?php echo $category->getCategoryPathAsNavigationForSearch(ESC_RAW) ?></td>
                                </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
            
                        <br/>
                        <center>
                        <a href='<?php echo url_for("main/index")?>' > <?php echo __('Back to Home Page');?> </a>
                        </center>
                    </td>
                    <td class="tdBoxRight">
                        <?php echo image_tag('layout/searchGuy.png', 'class="imgSearchGuy" style="z-index:6"' )?>
    
                    </td>
                </tr>
                <tr class="">
                    <td class="tdCorner tdBoxBottomLeft"></td>
                    <td class="tdBoxBottomMiddle"></td>
                    <td class="tdCorner tdBoxBottomRight"></td>
                </tr>

            </table>
        <?php endif; ?>
		<?php if (count($events)): ?>
			<table border="0px" width="" cellspacing="0px" class="">
                <tr class="">
                    <td class="tdCorner tdBoxTopLeft"></td>
                    <td class="tdBoxTopMiddle"></td>
                    <td class="tdCorner tdBoxTopRight"></td>
                </tr>
                 <tr class="trBox">
                    <td class="tdBoxLeft"></td>
                    <td class="tdBox">
                        <?php echo image_tag('layout/searchResults.gif', '" style="float:left"')?>
            
                        <center>
                        <h2><?php echo __('Events');?></h2><br/>
                        
                        </center>            
                            <table cellspacing="15px" style="width:100%;">
                              <thead>
                                <tr>
                                  <th><?php echo __('Name');?></th>
                                  <th><?php echo __('Calendar');?></th>
                                  <th><?php echo __('Date');?></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($events as $event): ?>
                                <?php $cal = $event->getCal();?>
                                <tr>
                                	<td><?php echo $event->getName();?></td>
                                	<?php if ($cal):?>
                                		<td><a href="<?php echo url_for($cal->getUrl(ESC_RAW));?>"><?php echo $cal->getName();?></a></td>
                                	<?php else:?>
                                		<td>&nbsp;</td>
                                	<?php endif;?>
                                	<td style="width:170px;"><?php echo $event->getDateForDisplay();?></td>
								</tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
            
                        <br/>
                        <center>
                        <a href='<?php echo url_for("main/index")?>' > <?php echo __('Back to Home Page');?> </a>
                        </center>
                    </td>
                    <td class="tdBoxRight">
                        <?php echo image_tag('layout/searchGuy.png', 'class="imgSearchGuy" style="z-index:6"' )?>
    
                    </td>
                </tr>
                <tr class="">
                    <td class="tdCorner tdBoxBottomLeft"></td>
                    <td class="tdBoxBottomMiddle"></td>
                    <td class="tdCorner tdBoxBottomRight"></td>
                </tr>
            </table>
		<?php endif;?>
    </div>

</div>
</div>
</div>
<div class="clearIt"></div>



