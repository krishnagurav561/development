<?php
/*
Template Name: New Enter Details Page
*/
?>
<?php
$eltdf_sidebar_layout  = mrseo_elated_sidebar_layout();

get_header();
mrseo_elated_get_title();
get_template_part('slider');
?>
<script src="<?php echo get_template_directory_uri();?>/assets/js/custom.js"></script>
<div class="container-lg step1">
	<div class="checkflex">
		<div class="flex-c1">
			<div class="box">
				<div class="heading">
					<div class="title">Get started</div>
					<hr>
				</div>
				<div class="body">
					<form id="frmDetails" class="form " method="POST" action="">
						<input type="hidden" name="user_id" value="">
						<div class="alerts">
						</div>
						<input type="hidden" name="crumb" value="FdX6UAesTI9k88n9lh0DS8lh9n8KdNXVgw0zs1mL88s">
						<div class="form-group">
							<img class="input-icon" src="/assets/img/checkout/instagram.svg">
							<input type="text" name="username" class="form-control" value="instagram" placeholder="Instagram username">
						</div>
						<div class="form-group">
							<img class="input-icon pusht" src="/assets/img/checkout/heart.svg">
							<div class="selector">
								<input type="hidden" name="plan" value="147">
								<button type="button" class="form-control"><strong>100</strong> likes<span class="alt">$2.97</span></button>
								<div class="dropdown-menu">
									<a href="#" data-value="146"><span class="desc"><strong>50</strong> likes</span><span class="alt">$1.47</span></a>
									<a href="#" data-value="147"><span class="desc"><strong>100</strong> likes</span><span class="alt">$2.97</span></a>
									<a href="#" data-value="148"><span class="desc"><strong>250</strong> likes</span><span class="alt">$4.99</span></a>
									<a href="#" data-value="149"><span class="desc"><strong>500</strong> likes</span><span class="alt">$6.99</span></a>
									<a href="#" data-value="150"><span class="desc"><strong>1000</strong> likes</span><span class="alt">$12.99</span></a>
									<a href="#" data-value="151"><span class="desc"><strong>2500</strong> likes</span><span class="alt">$24.99</span></a>
									<a href="#" data-value="152"><span class="desc"><strong>5000</strong> likes</span><span class="alt">$44.99</span></a>
									<a href="#" data-value="153"><span class="desc"><strong>10000</strong> likes</span><span class="alt">$88.99</span></a>
								</div>
							</div>
						</div>
						<div class="text-center">
							<div class="mar-opt checkbox">
								<input id="marCheck" type="checkbox" name="mailopt">
								<label for="marCheck"><span>Send me special promotions and discounts</span></label>
							</div>
						</div>
						<div class="button-group">
							<button type="submit" class="btn btn-block">Continue</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="flex-c2">
			<div class="user-review">
				<div class="img">
					<img src="/assets/img/testimonials/account-ct.png">
				</div>
				<p></p>
				<p>If you are in search of high quality Instagram services then Buzzoid is the place to go for. Service they deliver attract further growth.</p>
				<div class="stars">
					<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
				</div>
				<div class="author">
					<strong>Cassandra T.</strong>
				</div>
			</div> </div>
		</div>
	</div>
	<?php get_footer(); ?>