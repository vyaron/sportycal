<div style="width:800px;margin: auto">
	<h2 style="color:#333333">Inside your calendar-events, you might find some useful information and links: </h2>

        <table cellspacing="15px" width="100%">
          <tbody>
            <tr>
            <?php foreach ($ctgLinks as $ctgLink): ?>
            <?php if ($ctgLink->getType() == 'bet') continue;?>
              <td>
              	<a class="aCtgLink" href="<?php echo $ctgLink->getUrlToGive($cal->getId()) ?>" target="_blank">
              		<img class="imgCtgLink" src="<?php echo $ctgLink->getImgPath() ?>" title="<?php echo $ctgLink->getTxt() ?>" />
              		<?php echo $ctgLink->getTxt() ?>
              	</a>
              </td>
            <?php endforeach; ?>
            </tr>
            </tbody>
        </table>
</div>

<script>

window.addEvent('domready', function() {
	$$(".imgCtgLink").addEvents({
		mouseenter:  function() {this.src = changeImg(this.src, false);},
		mouseleave:  function() {this.src = changeImg(this.src, true);}
	});
});

function changeImg(imgSrc, isHover) {
	if (isHover) {
		imgSrc = imgSrc.replace("1.png", ".png");
	} else {
		imgSrc = imgSrc.replace(".png", "1.png");
	}
	return imgSrc;
}

</script>