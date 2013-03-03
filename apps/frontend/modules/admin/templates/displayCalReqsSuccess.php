<div style="text-align:left;">
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
            <h2>Calendars Update Request</h2><br/>
            </center>            


                <table cellspacing="15px">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Type</th>
                      <th>When</th>
                      <th>What</th>
                      <th>Context</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($calReqs as $calReq): ?>
                    <tr>
                      <td><?php echo $calReq->getId() ?></td>
                      <td><?php echo $calReq->getCalType() ?></td>
                      <td><?php echo $calReq->getCreatedAt() ?></td>
                      <td><?php echo $calReq->getCal()->getName() ?></td>
                      <td><?php echo $calReq->getCal()->getCategory()->getCategoryPathAsNavigationForSearch(ESC_RAW) ?></td>
                      
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
            
            
            <br/>
            <center>
            <a href='<?php echo url_for("main/index")?>' > Back to Home Page </a> | 
            <a href='<?php echo url_for("admin/calReqsCSV")?>' > CSV Export </a>

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





