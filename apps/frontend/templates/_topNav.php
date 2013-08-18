<ul id="main-nav" class="nav">
	<li <?php echo has_slot('homepage') ? ' class="active"' : '';?>>
		<a href="/">Home</a>
	</li>
	<li <?php echo has_slot('pricing') ? ' class="active"' : '';?>>
		<a href="<?php echo url_for('nm/pricing') ?>">Pricing</a>
	</li>
	<li <?php echo has_slot('caseStudies') ? ' class="active"' : '';?>>
		<a href="<?php echo url_for('nm/caseStudies') ?>">Case Studies</a>
	</li>
	<?php if ($user):?>
	<li <?php echo has_slot('calList') ? ' class="active"' : '';?>>
		<a href="<?php echo url_for('nm/calList') ?>">My Calendars</a>
	</li>
	<?php endif;?>
</ul>

<ul id="user-nav" class="nav pull-right">
	<?php if ($user):?>
	<li>
		<a class="dropdown-toggle" data-toggle="dropdown" href="#"> 
		<?php if ($user->getFbCode()):?>
			<img class="user-pic" src="//graph.facebook.com/<?php echo $user->getFbCode();?>/picture" />
		<?php endif;?>
		<?php echo $user->getFullName();?> <b class="caret"></b>
		</a>
		
		<ul class="dropdown-menu">
			<li><a href="<?php echo url_for('main/logout') ?>"><?php echo __('Sign Out');?>
			</a></li>
		</ul>
	</li>
	<?php else:?>
	<li id="nav-login-btn" <?php echo has_slot('login') ? ' class="active"' : '';?>>
		<a href="<?php echo url_for('partner/login');?>"><?php echo __('Log In');?></a>
	</li>
	<?php endif;?>
</ul>
