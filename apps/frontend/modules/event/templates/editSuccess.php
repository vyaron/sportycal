<h1>Edit Event - <?php echo $form->getObject()->getName(); ?></h1>

<?php include_partial('form', array('user' => $user, 'form' => $form, 'startsAt' => $startsAt, 'endsAt' => $endsAt, 'countryCodes' => $countryCodes, 'languageCodes' => $languageCodes, 'tags' => $tags)) ?>
