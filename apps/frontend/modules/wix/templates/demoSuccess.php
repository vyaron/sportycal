<?php sfContext::getInstance()->getResponse()->addStylesheet('/bundle/mediaelement/mediaelementplayer.css'); ?>
<?php sfContext::getInstance()->getResponse()->addStylesheet('/bundle/mediaelement/mejs-inevermiss.css'); ?>




<div id="banner-col-right">
    <div id="player-wrapper">
        <video id="player" class="mejs-inevermiss pull-right" width="100%" height=100% poster="/videos/nevermiss/iNeverMiss.jpg">
            <!-- Pseudo HTML5 -->
            <source type="video/webm" src="/videos/nevermiss/iNeverMiss.webm"/>
            <source type="video/mp4" src="/videos/nevermiss/iNeverMiss.mp4"/>
            <!-- <source type="video/youtube" src="http://www.youtube.com/watch?v=UQHZCQfMBB8"/> -->
        </video>
        <div class="cb"></div>
    </div>
</div>

<script type="text/javascript" src="/bundle/jquery/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/bundle/mediaelement/mediaelement-and-player.min.js"></script>
<script type="text/javascript" src="/js/neverMiss/index.js"></script>