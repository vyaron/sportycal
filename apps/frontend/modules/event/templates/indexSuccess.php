<h1>Events List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Cal</th>
      <th>Name</th>
      <th>Description</th>
      <th>Image path</th>
      <th>Location</th>
      <th>Starts at</th>
      <th>Ends at</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($events as $event): ?>
    <tr>
      <td><a href="<?php echo url_for('event/show?id='.$event->getId()) ?>"><?php echo $event->getId() ?></a></td>
      <td><?php echo $event->getCalId() ?></td>
      <td><?php echo $event->getName() ?></td>
      <td><?php echo $event->getDescription() ?></td>
      <td><?php echo $event->getImagePath() ?></td>
      <td><?php echo $event->getLocation() ?></td>
      <td><?php echo $event->getStartsAt() ?></td>
      <td><?php echo $event->getEndsAt() ?></td>
      <td><?php echo $event->getCreatedAt() ?></td>
      <td><?php echo $event->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('event/new') ?>">New</a>
