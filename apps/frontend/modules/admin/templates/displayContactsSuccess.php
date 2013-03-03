<div class="tal">
	<h3><a href='<?php echo url_for("admin/index")?>' class="" > &lt; Back to Master</a></h3>
	<br/>
</div>


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
            	<h2>Contacts List (<?php echo count($contacts) ?>)</h2>
            </center> 

			<table cellspacing="15px">
				<thead>
					<tr>
						<th>Id</th>
						<th>Subject</th>
						<th>Message</th>
						<th>Created At</th>
						<th>By User Id</th>
						<th>Sender Name</th>
						<th>Sender Email</th>
						<th>Ip Address</th>
					</tr>
				</thead>
				<tbody>
			  	<?php foreach ($contacts as $contact): ?>
			  		<tr>
			  			<td><?php echo $contact->getId();?></td>
			  			<td><?php echo $contact->getSubject();?></td>
			  			<td><?php echo $contact->getMessage();?></td>
			  			<td><?php echo $contact->getCreatedAt();?></td>
			  			<td><?php echo $contact->getByUserId();?></td>
			  			<td><?php echo $contact->getSenderName();?></td>
			  			<td><?php echo $contact->getSenderEmail();?></td>
			  			<td><?php echo $contact->getIpAddress();?></td>
			  		</tr>
			  	<?php endforeach;?>
			  	</tbody>
			</table>
		</td>
		<td class="tdBoxRight"></td>
    </tr>
    <tr class="">
        <td class="tdCorner tdBoxBottomLeft"></td>
        <td class="tdBoxBottomMiddle"></td>
        <td class="tdCorner tdBoxBottomRight"></td>
    </tr>
</table>

