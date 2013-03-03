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
            <h2>Links Usage<br/>
			<span class="tGrayHead">Shows data in the last month</span></h2>
            </center>            


                <table cellspacing="15px">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Link Type</th>
                      <th>Link Txt</th>
                      <th>Link Urls</th>
                      <th>Cal Type</th>
                      <th>Context</th>
                      <th>When</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($linksUsage as $linkUsage): ?>
                    <tr>
                      <td><?php echo $linkUsage->getId() ?></td>
                      <td><?php echo $linkUsage->getCategoryLink()->getType() ?></td>
                      <td><?php echo $linkUsage->getCategoryLink()->getTxt() ?></td>
                      <td><?php echo link_to('Click', $linkUsage->getCategoryLink()->getUrl()) ?> &nbsp;&nbsp;|&nbsp;&nbsp;
                          <?php echo link_to('Dest', $linkUsage->getCategoryLink()->getTargetUrl()) ?>
                      <td><?php echo $linkUsage->getUserCal()->getCalType() ?></td>
                      <td>
                        <?php echo $linkUsage->getUserCal()->getCal()->getCategory()->getCategoryPathAsNavigationForSearch(ESC_RAW) ?>
                        
                        <?php $calName = $linkUsage->getUserCal()->getCal()->getName() ?>
                        
                        <br/><b><?php echo ($calName)? link_to($calName, 'cal/show?id=' . $linkUsage->getUserCal()->getCal()->getId()) : ''?></b>
                      </td>
                      <td><?php echo $linkUsage->getCreatedAt() ?></td>
                      
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
            
            
            <br/>
            <center>
            <a href='<?php echo url_for("main/index")?>' > Back to Home Page </a> | 
            <a href='<?php echo url_for("admin/linkUsageCSV")?>' > CSV Export </a>

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





