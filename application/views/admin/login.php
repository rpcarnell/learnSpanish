<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->

<html>
<head>
	<meta charset="utf-8">
	<?php header("Last-Modified: " . gmdate( "D, j M Y H:i:s" ) . " GMT"); // Date in the past 
	header("Expires: " . gmdate( "D, j M Y H:i:s", time() ) . " GMT"); // always modified 
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
	header("Cache-Control: post-check=0, pre-check=0", FALSE); 
	header("Pragma: no-cache");
	?>  

	<title>Admin Login</title>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/reset.css" />   
	<style>
		#forgot:hover{
			text-decoration: underline;
		}
	</style> 
	<script>
		function MyPopUpWin() {
			var iMyWidth;
			var iMyHeight;
			//half the screen width minus half the new window width (plus 5 pixel borders).
			iMyWidth = (window.screen.width/2) - (75 + 10);
			//half the screen height minus half the new window height (plus title and status bars).
			iMyHeight = (window.screen.height/2) - (100 + 50);
			//Open the window.
			var win2 = window.open("<?php echo base_url()?>admin/forgotpass","Window2","status=no,height=150,width=250,resizable=yes,left=" + iMyWidth + ",top=" + iMyHeight + ",screenX=" + iMyWidth + ",screenY=" + iMyHeight + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
			win2.focus();
		}
	</script>
</head>

<body>
<div id="main-container2">
	<div id="header-wrapper"></div>
	<div id="main-wrapper2">
    	<div class="logo">
        </div>
        <div class="login-form">
        <?php echo form_open(); ?>
        	<p class="l-label">Email Or Username</p>
            <p class="l-inp-txt"><input type="text" name="uname"></p>
            <p class="l-label">Password</p>
            <p class="l-inp-txt"><input type="password" name="pword"></p>
            <p style="margin-top:11px;"><input name="send" class="l-inp-sub" value="Login" type="submit"/>
            	<span style="margin-top:5px;" class="fr" ><a href="javascript:MyPopUpWin()" style="color:white;" id="forgot">Forgot Password?</a></span>
				<div class="clr"></div>

			</p>

			<br></br>
			<center><!--<a href="<?php echo base_url()?>admin/forgot_password"><p class="l-label">Forgot Password?</center></p></a>-->
            <div class="clr"></div>
            <?php echo $error ?>
        <?php echo form_close(); ?>
        </div>
    
    </div>
    <!--<div id="footer-wrapper2"> Created By: Mindblow Creatives</div>-->
    
</div>



<!-- Use this when you upload your file -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/lib/jquery1.7.1jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/clearfield.js"></script>
<script type="text/javascript">
$(window).load(function() {
	$('.class-name li').last().css('margin-right',0);
	$('.clearField').clearField();
	<?php  echo $jquerys ?>

});


</script>


</body>
</html>
