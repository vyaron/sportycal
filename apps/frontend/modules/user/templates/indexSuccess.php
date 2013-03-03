<h1>Users List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Email</th>
      <th>Pass</th>
      <th>Full name</th>
      <th>Birthdate</th>
      <th>Country</th>
      <th>State</th>
      <th>City</th>
      <th>Address</th>
      <th>Zip code</th>
      <th>Created at</th>
      <th>Updated at</th>
      <th>Activation key</th>
      <th>Activation date</th>
      <th>Ref user</th>
      <th>Balance</th>
      <th>Type</th>
      <th>Gender</th>
      <th>Last login date</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><a href="<?php echo url_for('user/show?id='.$user->getId()) ?>"><?php echo $user->getId() ?></a></td>
      <td><?php echo $user->getEmail() ?></td>
      <td><?php echo $user->getPass() ?></td>
      <td><?php echo $user->getFullName() ?></td>
      <td><?php echo $user->getBirthdate() ?></td>
      <td><?php echo $user->getCountryId() ?></td>
      <td><?php echo $user->getStateId() ?></td>
      <td><?php echo $user->getCity() ?></td>
      <td><?php echo $user->getAddress() ?></td>
      <td><?php echo $user->getZipCode() ?></td>
      <td><?php echo $user->getCreatedAt() ?></td>
      <td><?php echo $user->getUpdatedAt() ?></td>
      <td><?php echo $user->getActivationKey() ?></td>
      <td><?php echo $user->getActivationDate() ?></td>
      <td><?php echo $user->getRefUserId() ?></td>
      <td><?php echo $user->getBalance() ?></td>
      <td><?php echo $user->getType() ?></td>
      <td><?php echo $user->getGender() ?></td>
      <td><?php echo $user->getLastLoginDate() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('user/new') ?>">New</a>
