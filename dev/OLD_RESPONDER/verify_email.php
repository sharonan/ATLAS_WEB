<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!--CSS-->
<link rel="stylesheet" type="text/css" href="css/verify_email.css" />

<!--JQuery-->
<script src="js/jquery-1.9.1.min.js"></script>

<!--Parse-->
<script src="js/parse-1.2.1.min.js"></script>

<!--Javascript-->
<script src="js/verify_email.js"></script>

<script type="text/javascript">
<!--Get Object Id-->
window.objectId = "<?php echo $_GET["id"]; ?>";

<!--IOS Alert-->
function iPhoneAlert() {
	if((navigator.userAgent.match(/iPhone/i))||(navigator.userAgent.match(/iPod/i))){
		setTimeout(function() { window.scrollTo(0, 1) }, 100);
	}
}
</script>

<!--Website Icon-->
<link rel="shortcut icon" href="https://launchrock-assets.s3.amazonaws.com/favicon-files/y3d2El5mjKplmgt.png">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<!--Title-->
<title>GET ATLAS</title>
</head>

<body>

<!--Header-->
<div class="header_bar">
    <div class="header_img_static">
        <a href=""><img src="images/header_img_static.png" width="897" height="53" alt="Atlas"></a>
    </div>
</div>

<div class="sub_header">
	<div class="sub_header_img_static">
        <img src="images/phones.png" alt="iPhone &amp; Android" name="phones" width="436" height="321" class="phones">
        <img src="images/app_store_logo.png" alt="App Store" name="app_store" width="208" height="66" class="app_store">
        <img src="images/google_play_logo.png" alt="Google Play" name="google_play" width="187" height="66" class="google_play">
        <img src="images/cloud.png" alt="Calendars" name="cloud" width="524" height="239" class="cloud">
	</div>
</div>

<!--Loader-->
<center><img class="loader" src="images/loader.gif" width="100" height="100" /></center>

<!--Content-->
<center><div class="content">
<br />
<br />
<br />

<span class="verified">The email address "<span class="email_address"></span>" has been verified.</span>
<span class="not_verified">The email address could not be found.</span>

<br />
<br />
<br />
</div></center>

<!--Footer Section-->
<div class="footer">
	<ul class="navlist">
		<li><a href="#"><img src="images/about_footer.png" width="132" height="25" alt="About"></a></li>
		<li><a href="#"><img src="images/facebook_footer.png" width="132" height="25" alt="Facebook"></a></li>
		<li><a href="#"><img src="images/twitter_footer.png" width="132" height="25" alt="Twitter"></a></li>
		<li><a href="#"><img src="images/press_footer.png" width="132" height="25" alt="Press"></a></li>
		<li><a href="#"><img src="images/contact_footer.png" width="132" height="25" alt="Contact"></a></li>
	</ul>
    <center><span class="version">v301.16.01L</span></center>
</div>

</body>
</html>