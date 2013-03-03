<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $short_url->getId() ?></td>
    </tr>
    <tr>
      <th>Cal:</th>
      <td><?php echo $short_url->getCalId() ?></td>
    </tr>
    <tr>
      <th>Category:</th>
      <td><?php echo $short_url->getCategoryId() ?></td>
    </tr>
    <tr>
      <th>Event:</th>
      <td><?php echo $short_url->getEventId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $short_url->getUserId() ?></td>
    </tr>
    <tr>
      <th>Partner:</th>
      <td><?php echo $short_url->getPartnerId() ?></td>
    </tr>
    <tr>
      <th>Url:</th>
      <td><?php echo $short_url->getUrl() ?></td>
    </tr>
    <tr>
      <th>Comment:</th>
      <td><?php echo $short_url->getComment() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $short_url->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Used at:</th>
      <td><?php echo $short_url->getUsedAt() ?></td>
    </tr>
    <tr>
      <th>Count used:</th>
      <td><?php echo $short_url->getCountUsed() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('shortURL/edit?id='.$short_url->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('shortURL/index') ?>">List</a>
