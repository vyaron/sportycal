<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $partner->getId() ?></td>
    </tr>
    <tr>
      <th>Hash:</th>
      <td><?php echo $partner->getHash() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $partner->getName() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('partner/edit?id='.$partner->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('partner/index') ?>">List</a>
