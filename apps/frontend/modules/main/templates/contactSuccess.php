    <!--[if (lte IE 7)]>
    <style>
    .imgContactGuy{
        top: -280px;
        left:280px;
        z-index:0;
            
    }
    </style>
    <![endif]-->

<div style="">
	<div class="centererOuter" style="">
		<div class="centererInner" style="z-index:5;background:transparent">
			<?php include_partial('main/contact', array('user' => $user, 'toki' => $toki)); ?>
		</div>
	</div>
	
	<?php echo image_tag('layout/contactGuy.gif', 'class="imgContactGuy" ' )?>
</div>
