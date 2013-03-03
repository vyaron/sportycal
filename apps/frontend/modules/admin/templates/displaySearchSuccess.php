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
            <h2>Users Search</h2><br/>
            </center>            


                <table cellspacing="15px">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>When</th>
                      <th>What</th>
                      <th>Date Range</th>
                      <th>User Id</th>
                      <th>IP Address</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($searches as $search): ?>
                    <tr>
                      <td><?php echo $search->getId() ?></td>
                      <td><?php echo $search->getCreatedAt() ?></td>
                      <td><?php echo $search->getStr() ?></td>
                      <td><?php echo $search->getDateRange() ?></td>
                      <td><?php echo $search->getUserId() ?></td>
                      <td><?php echo $search->getIpAddress() ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
            
            
            <br/>
            <center>
            <a href='<?php echo url_for("main/index")?>' > Back to Home Page </a> &nbsp;&nbsp;|&nbsp;&nbsp;
            <a href='<?php echo url_for("admin/userSearchCSV")?>' > CSV Export </a>

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





