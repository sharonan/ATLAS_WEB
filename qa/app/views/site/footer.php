<?php if(!$nofooter){ ?>

	<div class="divider">
		<img class="space" src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
	</div>
	<div class="divider">
		<img style="margin-left: 15px;" class="halfspace" src="<?=PATH?>/img/phone.png" width="46" height="47" alt="phone" />
		<h2 class="seewhy">See why <?php echo $user->first_name; ?>'s using Atlas.  Get Atlas for free!</h2>
	</div>
	<div class="divider">
		<a href="http://ios.getatlas.com" target="_blank"><img src="<?=PATH?>/img/app-store2.png" width="208" height="61" alt="App Store" /></a>
		<img class="itunes" src="<?=PATH?>/img/itunes-art.png" width="65" height="66" alt="Atlas" />
		<a href="http://android.getatlas.com" target="_blank"><img src="<?=PATH?>/img/google-play2.png" width="191" height="66" alt="Google Play" /></a>
	</div>
<?php } ?>
<?php if($invited_footer){ ?>
	<div class="divider">
		<img class="space" src="<?=PATH?>/img/4.png" width="46" height="47" alt="phone" />
		<h2 class="seewhy">See why <?php echo $user->first_name; ?>'s using Atlas.  Get Atlas for free!</h2>
	</div>
	<div class="divider">
		<a href="http://ios.getatlas.com" target="_blank"><img src="<?=PATH?>/img/app-store2.png" width="208" height="61" alt="App Store" /></a>
		<img class="itunes" src="<?=PATH?>/img/itunes-art.png" width="65" height="66" alt="Atlas" />
		<a  href="http://android.getatlas.com" target="_blank"><img src="<?=PATH?>/img/google-play2.png" width="191" height="66" alt="Google Play" /></a>
	</div>
<?php } ?>
	<div class="divider">
		<img <?php if(!$nospace){ ?>class="space"<?php } ?> src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
	</div>
	<footer>
		<div class="divider">
			<ul>
				<li><a href="/" class="blue-gradient" title="Get Atlas"><span>Get Atlas</span></a></li>
				<li><a href="http://www.getatlas.com/go/about" class="blue-gradient" title="About Us"><span>About Us</span></a></li>
				<li><a href="http://support.getatlas.com" class="blue-gradient" title="Support"><span>Support</span></a></li>
				<li><a href="#" class="blue-gradient" title="Terms"><span>Terms</span></a></li>
				<li><a href="#" class="blue-gradient" title="Privacy"><span>Privacy</span></a></li>
				<li><a href="#" class="blue-gradient" title="&copy;2013 Atlas"><span>&copy;2013 Atlas</span></a></li>
			</ul>
		</div>
	</footer>
</body>
</html>