<?php 
$max = 4;
$maxOffset = floor($list['total'] / $list['limit']);
$start = max(0, $list['offset'] - 2); 
			$end = min($start + $max, $maxOffset + 2); 
?>
<p class="pull-left">page <?php echo $list['offset'] + 1;?> of <?php echo $maxOffset + 1;?></p>
<div class="pagination pagination-centered">
	<ul>
		<?php for($i=$start; $i <= $end; $i++): $offset = $i -1;?>
			<li class="<?php echo ($offset < 0 || $offset > $maxOffset) ? 'disabled' : ($offset == $list['offset'] ? 'active' : '');?>">
				<a href="<?php echo ($offset < 0 || $offset > $maxOffset) ? '#' : url_for('/nm/list/?p=' . $offset);?>"><?php echo ($i == $start) ? '&lt;' : (($i == $end) ? '&gt;' : $i);?></a>
			</li>
		<?php endfor;?>
	</ul>
</div>