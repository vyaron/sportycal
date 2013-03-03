<?php
	$currOrderBy = $orderBy;
	
	if ($orderBy == 'desc') $orderBy = 'asc';
	else $orderBy = 'desc';
?>

<div style="text-align:left;">

<h3><a href='<?php echo url_for("admin/index")?>' class="" > &lt; Back to Master</a></h3>
<br/>


<table border="0px" width="" cellspacing="0px" class="">
    <tr class="">
        <td class="tdCorner tdBoxTopLeft"></td>
        <td class="tdBoxTopMiddle"></td>
        <td class="tdCorner tdBoxTopRight"></td>
    </tr>
     <tr class="trBox">
        <td class="tdBoxLeft"></td>

        <td class="tdBox">

            <center>
            <h2>Users Calendars<br/>
			<span class="tGrayHead">Shows data in the last month</span></h2>

            </center>            


                <table cellspacing="15px">
                  <thead>
                    <tr>
                      <th><a href='<?php echo url_for("admin/displayUserCals?sort=id&orderBy=" . $orderBy)?>' class="tableSorter<?php echo ($sort == 'id') ? ' sort_' . $currOrderBy : '';?>" >Id</a></th>
                      <th><a href='<?php echo url_for("admin/displayUserCals?sort=type&orderBy=" . $orderBy)?>' class="tableSorter<?php echo ($sort == 'type') ? ' sort_' . $currOrderBy : '';?>" >Cal Type</a></th>
                      <th><a href='<?php echo url_for("admin/displayUserCals?sort=taken&orderBy=" . $orderBy)?>' class="tableSorter<?php echo ($sort == 'taken') ? ' sort_' . $currOrderBy : '';?>" >Taken At</a></th>
                      <th>User Id</th>
                      <th>IP Address</th>
                      <th>Cal Name</th>
                      <th>Context</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($userCals as $userCal): ?>
                    
                    <?php 
                    	$cal = $userCal->getCal();
                    	if (!$cal->getId()) $cal = null;
                    	if ($cal) 	{
                    		$ctg 	= $userCal->getCal()->getCategory();
        					$name 	= $cal->getName(); 
        					$url 	= url_for('cal/show?id='.$cal->getId());                   		
                    	} else {
                    		$ctg 	= $userCal->getCategory();
        					$name 	= $ctg->getName() . Cal::AGG_EXT;
        					$url	= url_for('cal/show?ctgId='.$ctg->getId());                    		
                    	}
                    ?>
                    
                    <tr>
                      <td><?php echo $userCal->getId() ?></td>
                      <td><?php echo $userCal->getCalType() ?></td>
                      <td><?php echo $userCal->getTakenAt() ?></td>
                      <td><?php echo $userCal->getUserId() ?></td>
                      <td><?php echo $userCal->getIpAddress() ?></td>

                      <td><a href="<?php echo $url ?>"><?php echo $name ?></td>

                      <td><?php echo $ctg->getCategoryPathAsNavigationForSearch(ESC_RAW) ?></td>
                      
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
            
            
            <br/>
            <center>
            <a href='<?php echo url_for("main/index")?>' > Back to Home Page </a> | 
            <a href='<?php echo url_for("admin/userCalsCSV")?>' > CSV Export </a>

            </center>
        </td>
        <td class="tdBoxRight"></td>
    </tr>
    <tr class="">
        <td class="tdCorner tdBoxBottomLeft"></td>
        <td class="tdBoxBottomMiddle"></td>
        <td class="tdCorner tdBoxBottomRight"></td>
    </tr>

</table>

</div>





