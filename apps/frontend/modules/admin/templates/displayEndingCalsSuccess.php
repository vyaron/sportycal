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
            <h2>Ending Calendars (<?php echo count($calsInfo) ?>)<br/>
			<span class="tGrayHead">Shows cals that are NOT ended yet but ENDING by <?php echo $endDate ?> </span></h2>

            </center>            


                <table cellspacing="15px">
                  <thead>
                      <th>Id</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($calsInfo as $calInfo): ?>
                    
                    <tr>
                      <td><?php echo $calInfo["cal_id"] ?></td>
                      <td><a href="<?php echo url_for('cal/show?id=' . $calInfo["cal_id"]) ?>"><?php echo $calInfo["name"] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
            
            
            <br/>
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





