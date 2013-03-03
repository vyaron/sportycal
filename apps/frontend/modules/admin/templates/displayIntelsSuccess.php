<div class="tal">
	<h3><a href='<?php echo url_for("admin/index")?>' class="" > &lt; Back to Master</a></h3>
	<br/>
</div>


<h1><?php echo $reportTitle ?> (<?php echo count($intels) ?>)</h1> <br/>

<table cellpadding="10px" cellspacing="5px">
  <thead>
    <tr>
      <th>Id</th>
      <th>Cal</th>
      <th>Category</th>
      <th>Event</th>
      <th>User</th>
      <th>Session</th>
      <th>Partner</th>
      <th>Section</th>
      <th>Action</th>
      <th>Label</th>
      <th>Value</th>
      <th>Created at</th>
      <th>User cal</th>
      <th>Ip address</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($intels as $intel): ?>
    <tr>
      <!-- td><a href="<?php echo url_for('intel/show?id='.$intel->getId()) ?>"><?php echo $intel->getId() ?></a></td-->
      <td><?php echo $intel->getId() ?></td>
      <td><a target="_blank" href="<?php echo url_for('cal/show?id='.$intel->getCalId()) ?>"><?php echo $intel->getCalId() ?></a></td>
      <td><a target="_blank" href="<?php echo url_for('category/show?id='.$intel->getCategoryId()) ?>"><?php echo $intel->getCategoryId() ?></a></td>
      <td><?php echo $intel->getEventId() ?></td>
      <td><?php echo $intel->getUserId() ?></td>
      <td><?php echo $intel->getSessionCode() ?></td>
      <td><?php echo $intel->getPartnerId() ?></td>
      <td><?php echo $intel->getSection() ?></td>
      <td><?php echo $intel->getAction() ?></td>
      <td><?php echo $intel->getLabel() ?></td>
      <td><?php echo $intel->getValue() ?></td>
      <td><?php echo $intel->getCreatedAt() ?></td>
      <td><?php echo $intel->getUserCalId() ?></td>
      <td><?php echo $intel->getIpAddress() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
