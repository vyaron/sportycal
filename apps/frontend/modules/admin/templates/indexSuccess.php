<style>

.divSimple {
    background-color:lightgray;
    padding:10px;
    float:left;
    margin:10px;
    min-height:100px;
    min-width:200px;
}
    
</style>

<div style="width:800px;margin:auto">

<h1> sportYcal Master</h1></div>

<div class="divSimple">
    <h3> Pages </h3><br/>
    <a href="<?php echo url_for('admin/displayUsers') ?>">Users</a> &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/displaySearch') ?>">Searches</a> &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/displayUserCals') ?>">User Calendars</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <!-- a href="<?php echo url_for('admin/displayCalReqs') ?>">Calendars Updates</a>&nbsp;&nbsp;|&nbsp;&nbsp;-->
    <!-- <a href="<?php echo url_for('admin/displayLinkUsage') ?>">Links Usage</a>&nbsp;&nbsp;|&nbsp;&nbsp;  -->
    <a href="<?php echo url_for('admin/displayContacts') ?>">Contacts</a>
    <br/>
    
    <a href="<?php echo url_for('admin/displayCtgs') ?>">Categories</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/displayCalendars') ?>">Calendars</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/displayEndingCals') ?>">Ending Cals </a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('shortURL/index') ?>">Short URLs </a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <br/>
    <a href="<?php echo url_for('main/calDownloadWidget') ?>">CalDown Widget Customization<img src="/images/layout/new.gif"/> </a>
    

</div>

<div class="divSimple">
    <h3> Intels</h3><br/>
    <a href="<?php echo url_for('admin/displayIntels') ?>">Events Open</a> &nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="<?php echo url_for('admin/displayInvites') ?>">Invites</a> &nbsp;&nbsp;|&nbsp;&nbsp;    
	<a href="<?php echo url_for('admin/displayPromo') ?>">Promo</a> &nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="<?php echo url_for('admin/displayBirthdayCals') ?>">Birthday Cals</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="<?php echo url_for('admin/displayBirthdayCalSignups') ?>">Birthday Cal Sign Ups</a>
</div>


<div class="divSimple">
    <h3> CSV Exports </h3><br/>
    <a href='<?php echo url_for("admin/userCalsCSV")?>' > User Calendars</a> &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href='<?php echo url_for("admin/userSearchCSV")?>' > Searches </a>     &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href='<?php echo url_for("admin/linkUsageCSV")?>' > Link Usage </a>

</div>






<div class="divSimple">
    <h3>Data Providers</h3><br/>
    <a href="<?php echo url_for('admin/fSpider') ?>">F-Spider</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/eSpiderCollegeFootball') ?>">E-Spider - College Football</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/eSpiderMensCollegeBasketball') ?>">E-Spider - Mens College Basketball</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/sSpider') ?>">Soccernet-Spider</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/espnscrumSpider') ?>">Rogby-Spider</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="<?php echo url_for('admin/nHLSpider') ?>">NHL-Spider</a>    
</div>

<div class="divSimple">
    <h3>Partners Data</h3><br/>
    <a href="<?php echo url_for('admin/updateWinnerCals') ?>">Toto Update</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/updatePokerstarsCals') ?>">Pokerstars Update<img src="/images/layout/new.gif"/></a> &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="<?php echo url_for('admin/updateEventStats') ?>">SportWiser Update</a>
</div>

<div class="divSimple">
    <h3> Partners </h3><br/>
    <a href="<?php echo url_for('partner/index') ?>">Partners</a>

</div>



<div class="divSimple">
    <h3>Restricted! - David Operations </h3><br/>
    <a href="<?php echo url_for('admin/countCals') ?>">Fix Cals Count</a>&nbsp;&nbsp;|&nbsp;&nbsp;
</div>

</div>
<a href='<?php echo url_for("main/index")?>' > Back to Home Page </a>
