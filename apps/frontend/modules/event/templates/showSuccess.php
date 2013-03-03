<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $event->getId() ?></td>
    </tr>
    <tr>
      <th>Cal:</th>
      <td><?php echo $event->getCalId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $event->getName() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $event->getDescription() ?></td>
    </tr>
    <tr>
      <th>Image path:</th>
      <td><?php echo $event->getImagePath() ?></td>
    </tr>
    <tr>
      <th>Location:</th>
      <td><?php echo $event->getLocation() ?></td>
    </tr>
    <tr>
      <th>Starts at:</th>
      <td><?php echo $event->getStartsAt() ?></td>
    </tr>
    <tr>
      <th>Ends at:</th>
      <td><?php echo $event->getEndsAt() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $event->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $event->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('event/edit?id='.$event->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('event/index') ?>">List</a>
