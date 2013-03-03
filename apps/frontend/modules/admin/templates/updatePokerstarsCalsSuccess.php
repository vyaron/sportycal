<div class="tal">
	<h3><a href='<?php echo url_for("admin/index")?>' class="" > &lt; Back to Master</a></h3>
	<br/>
</div>


<h1>Imported Pocker Stars Cals</h1> <br/>
<h5>From: http://www.winner.co.il/totoxml/totoxml.asmx/Winner</h5> <br/>

<table>
  <thead>
    <tr>
      <th>Cal Name</th>
      <th>Events count</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($calName2EventsCount as $calName => $eventsCount): ?>
    <tr>
      <td><?php echo $calName ?></td>
      <td><?php echo $eventsCount ?></td>
      
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br/>
<h2>Games List <?php echo count($importedGames)?></h2> <br/>
<br/>
<table>
  <thead>
    <tr>
      <th>Game Name</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($importedGames as $importedGame): ?>
    <tr>
      <td><?php echo $importedGame ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
