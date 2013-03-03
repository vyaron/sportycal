<h1>Partners List</h1>

<table cellpadding="10" cellspacing="10">
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Hash</th>
      <th>Cal Requests</th>
      <th>Test iCal</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($partners as $partner): ?>
    <tr>
      <td><a href="<?php echo url_for('partner/show?id='.$partner->getId()) ?>"><?php echo $partner->getId() ?></a></td>
      <td><?php echo $partner->getName() ?></td>
      <td><?php echo $partner->getHash() ?></td>
      
      <td><?php // TOO MUCH DATA...echo $partner->getCalRequest()->count() ?></td>
      <td><a href="<?php echo $partner->getSampleUrl() ?>" target="_blank">Open TEST iCal</a></td>

    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('partner/new') ?>">New</a>
