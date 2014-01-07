<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="apple-itunes-app" content="app-id=621589078"/>
	<title><?php echo TITLE ?></title>
	
	<script type="text/javascript" src="<?php echo PATH; ?>/js/ie-html5.js"></script>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

    <?php 
		headerJavascript();
	?>
	<link rel="stylesheet" href="<?php echo PATH; ?>/css/reset.css" />
	<link rel="stylesheet" href="<?php echo PATH; ?>/css/style.css" />
	<!-- UserVoice JavaScript SDK (only needed once on a page) -->
	<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/myxXEuBM5e5sVMLui5zzg.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>

	<!-- A tab to launch the Classic Widget -->
	<script>
	UserVoice = window.UserVoice || [];
	UserVoice.push(['showTab', 'classic_widget', {
	mode: 'full',
	primary_color: '#cc6d00',
	link_color: '#007dbf',
	default_mode: 'support',
	forum_id: 185895,
	tab_label: 'Feedback & Support',
	tab_color: '#ff7049',
	tab_position: 'middle-right',
	tab_inverted: false
	}]);
	</script>
</head>
<body>
<header>
	<section>
		<div class="container">
			<div class="logocontainer">
				<img id="banner" src="<?=PATH?>/img/ribbon.png" />
				<a href="http://<?php echo (DIRECTORY=='prod'?'www':DIRECTORY); ?>.getatlas.com" class="h1">Atlas<span>&trade;</span></a>
			</div>
			<?php if(!$hidepro){ ?><div id="pro_badge"></div><?php } ?>
			<a id="sign_in" title="Sign In" href="#">Sign in</a>
			<p>The easiest way to schedule anything.</p>
		</div>
	</section>
	<div class="background">
		<?php if($header===2){ ?>
		<div class="layer2">
			<div class="inner">
				<a href="http://ios.getatlas.com" target="_blank" title="Download On App Store" class="apple">Download On App Store</a>
				<a href="http://android.getatlas.com" target="_blank" title="Download On Google Play" class="google">Download On Google Play</a>
			</div>
		</div>
		<?php } else { ?>
		<div class="layer">
			<div class="inner">
				<a href="http://ios.getatlas.com" target="_blank" title="Download On App Store" class="apple">Download On App Store</a>
				<a href="http://android.getatlas.com" target="_blank" title="Download On Google Play" class="google">Download On Google Play</a>
			</div>
		</div>
		<?php } ?>
		<?php if($header===2){ ?>
			<div class="imgbox2"><img height="223" width="318" src="<?=PATH?>/img/time-is-everything.png" alt="Time is Everything." /></div>
		<?php } else { ?>
			<div class="imgbox"><img height="165" width="165" src="<?php echo $user->picture->url; ?>" alt="User Image" /></div>
		<?php } ?>
	</div>
</header>