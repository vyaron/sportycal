<div class="divUserImg">
    
    <?php if ($user->getFbCode()) : ?>
        <img src="http://graph.facebook.com/<?php echo $user->getFbCode() ?>/picture"  />
    <?php else : ?>
        <?php echo image_tag('user/profile.jpg', 'class="imgProfile"')?>
    <?php endif; ?>
</div>
<div class="divUserInfo">
    <table>
      <tbody>
        <tr>
          <th>Name:</th>
          <td><?php echo $user->getFullName() ?></td>
        </tr>
        <tr>
          <th>Joined:</th>
          <td><?php echo $user->getCreatedAt() ?></td>
        </tr>
      </tbody>
    </table>
</div>    

