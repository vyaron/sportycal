<?php //sfContext::getInstance()->getResponse()->addJavascript('basics.js'); ?>
<div class="adminLinks">    
    (
		<a href="<?php echo url_for('category/new?id='.$category->getId()) ?>">Create sub Category</a>
   		&nbsp;|&nbsp;

		<?php if ($category->getParentId()) :?>   		
		    <a href="<?php echo url_for('category/edit?id='.$category->getId()) ?>">Edit this Category</a>
		    &nbsp;|&nbsp;
		    <?php echo link_to('Delete this Category', 'category/delete?id='.$category->getId(), array('method' => 'delete', 'confirm' => 'DELETE this CATEGORY?')) ?>
			&nbsp;|&nbsp;
		<?php endif ?>
    	<a href="<?php echo url_for('cal/new?ctgId='.$category->getId()) ?>">Create new Calendar</a>
    )

	    &nbsp;|&nbsp;
	    <a href="javascript:toggleIt('ctgLinks', true)">Show Links [<?php echo count($ctgLinks)?>]</a>)

        
    <div id="ctgLinks" style="display:none;">
        <table cellspacing="15px">
          <thead>
            <tr>
              <th>id</th>
              <th>Link Type</th>
              <th>Link Txt</th>
              <th>Link Urls</th>
            </tr>
          </thead>
          <tbody>
        
            <?php foreach ($ctgLinks as $ctgLink): ?>
            <tr>
              <td><?php echo $ctgLink->getId() ?></td>
              <td><?php echo $ctgLink->getType() ?></td>
              <td><?php echo $ctgLink->getTxt() ?></td>
              <td><?php echo link_to('Click', $ctgLink->getUrl(), 'target="_blank"') ?> &nbsp;&nbsp;|&nbsp;&nbsp;
                  <?php echo link_to('Dest', $ctgLink->getTargetUrl(), 'target="_blank"') ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a href="javascript:toggleIt('frmAdd', true)">Add Link</a>
        <div id="frmAdd" style="display:none;">
            <form action="<?php echo url_for('category/addLink')?>">
                <input type="hidden" name="ctgId" value="<?php echo $category->getId()?>" />
                <select id="ddCtgLinks" name="type" onchange="linkChanged()">
                    <option>website</option>
                    <option>ticket</option>
                    <option>bet</option>
                </select>
                
                <input type="text" id="linkTxt" name="txt" value="Official Site" />
                <input type="text" name="url" value="http://Click-URL" /> 
                <input type="text" name="targetUrl" value="http://Dest-URL" />
                <input type="submit" value="Carefully Add" />
            </form>
        </div>
  
    </div>

<script type="text/javascript">

function linkChanged() {
	var s 				= document.getElementById("ddCtgLinks");
	var chosenOption	= s.options[s.selectedIndex];
	var val 			= chosenOption.value;
	var t 				= document.getElementById("linkTxt");

	if (val == "website") {
		t.value = "Official Site";
	} else if (val == "ticket") {
		t.value = "Buy a Ticket";
	} else if (val == "bet") {
		t.value = "Bet on this Game";
	}
	
}

</script>

    
</div>
