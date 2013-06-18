<?php 
//$list = array('total' => 100, 'limit' => 10, 'offset' => 9);

$max = 4;

$maxOffset = max(floor($list['total'] / $list['limit']) -1, 0);
if ($list['total'] > $list['limit'] && ($list['total'] % $list['limit'] != 0)) $maxOffset++;

$start = max(0, $list['offset'] -2);
$end = min($start + $max, $maxOffset + 2);

//echo "Start: $start, End: $end, MaxOffset: $maxOffset" . '<br/>';
?>
<p class="pull-left">page <?php echo $list['offset'] + 1;?> of <?php echo $maxOffset + 1;?></p>
<div class="pagination pagination-centered">
	<ul>
		<?php for($i=$start; $i <= $end; $i++): $offset = $i -1;?>
			<li class="<?php echo ($offset < 0 || $offset > $maxOffset) ? 'disabled' : ($offset == $list['offset'] ? 'active' : '');?>">
				<a href="<?php echo ($offset < 0 || $offset > $maxOffset) ? '#' : url_for($url . '?p=' . $offset);?>"><?php echo ($i == $start) ? '&lt;' : (($i == $end) ? '&gt;' : $i);?></a>
			</li>
		<?php endfor;?>
	</ul>
</div>