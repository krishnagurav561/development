<?php
/*
Template Name: Enter Details Page
*/
?>
<?php
$eltdf_sidebar_layout  = mrseo_elated_sidebar_layout();


get_header();
mrseo_elated_get_title();
get_template_part('slider');
$ProductID = $_GET["addCart"];
$_product = wc_get_product( $ProductID );
$terms = get_the_terms( $ProductID, 'product_cat' );

foreach ( $terms as $term ) {
	$term_id = $term->term_id;
	if ($term->slug == "buy-instagram-likes" || $term->slug == "premium-likes" || $term->slug == "high-quality-likes") {
		$categ = "Likes";
	}
	if ($term->slug == "buy-instagram-followers" || $term->slug == "high-quality-followers" || $term->slug == "premium-followers") {
		$categ = "Followers";
	}
	if ($term->slug == "buy-instagram-views") {
		$categ = "Views";
	}
}
if ( is_user_logged_in() ) {
	$userID = get_current_user_id();
	$user_info = get_userdata($userID);
	$user_email = $user_info->user_email;
} else {
    
}
$products = wc_get_products( array( 'status' => 'publish' , 'ignore_sticky_posts'   => 1,
    'posts_per_page'        => '12',
    'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                'terms'         => $term_id,
                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
            )
)) );
?>
<div class="eltdf-container eltdf-default-page-template">
	<?php do_action('mrseo_elated_after_container_open'); ?>
	<div class="eltdf-container-inner clearfix">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="eltdf-grid-row">
				<div <?php echo mrseo_elated_get_content_sidebar_class(); ?>>
					<div class="eltdf-content-sidebar-upgrade"></div>
					<div class="eltdf-page-content-holder-inner enterpageflex">
						<div class="eltdf-grid-col-7 eltdf-grid-col-ipad-portrait-12 eltdf-grid-col-phone-portrait-12">
							<div class="enter-details">
								<div class="heading">
									<div class="title">Get started</div>
									<hr>
								</div>
								<script type="text/javascript">
									
									async function getInstagramAll (profileName) {
										var baseUrl = "https://www.instagram.com";
										var profileUrl = `${baseUrl}/${profileName}`;
										var jsonDataUrl = `${profileUrl}/?__a=1`;

										var response = await fetch(jsonDataUrl);
										var jsonData = await response.json();

										if (response.ok) {
											return jsonData;
										} else {
											throw new Error(jsonData);
										}
									}
									
									function errrDisplay(){
										document.getElementById( "InstaUserError" ).show();
									}
									async function getInstagramValidation (profileName) {
										var baseUrl = "https://www.instagram.com";
										var profileUrl = `${baseUrl}/${profileName}`;
										var jsonDataUrl = `${profileUrl}/?__a=1`;

										var response = await fetch(jsonDataUrl);
										
										if (response.ok) {
											return response.status;
										} else {
											errrDisplay();
											throw new Error(response);
										}
									}
									async function getInstagramFullName (profileName) {
										var baseUrl = "https://www.instagram.com";
										var profileUrl = `${baseUrl}/${profileName}`;
										var jsonDataUrl = `${profileUrl}/?__a=1`;

										var response = await fetch(jsonDataUrl);
										var jsonData = await response.json();
										var full_name = jsonData.graphql.user.full_name;
										if (response.ok) {
											return full_name;
										} else {
											throw new Error(full_name);
										}
									}
									async function getInstagramPrivate (profileName) {
										var baseUrl = "https://www.instagram.com";
										var profileUrl = `${baseUrl}/${profileName}`;
										var jsonDataUrl = `${profileUrl}/?__a=1`;

										var response = await fetch(jsonDataUrl);
										var jsonData = await response.json();
										var is_private = jsonData.graphql.user["is_private"];
										if (response.ok) {
											return is_private;
										} else {
											throw new Error(is_private);
										}
									}
									
									jQuery( document ).ready(function($) {
										$('#instaSubmit').prop('disabled', true);
										$('#instaUser').keyup(function() {
											if($(this).val() != '') {
												$('#instaSubmit').prop('disabled', false);
											}
										});
										$("#selectedproduct").on('click', function() {
											$(".changeProduct .dropdown-menu").toggle();
											$("#selectedproduct").toggleClass("active");
											//$("#insta-product-ID").val(this.value);
										});
										$(".changeProduct .dropdown-menu a").on('click', function(e) {
											e.preventDefault();
											$(".changeProduct .dropdown-menu").toggle();
											$("#selectedproduct").toggleClass("active");
											$("#insta-product-ID").val($(this).data("value"));
											$("#pprice").text($(this).data("price"));
											$("#ptitle").text($(this).data("title"));											
											
										});
										$( "#frmDetails" ).submit(function( event ) {

											const fetchInstagramProfile = async () => {
												const profileName = $( "#instaUser" ).val();
												const baseUrl = "https://instafo.ew.r.appspot.com";
												const profileUrl = `${baseUrl}/instagram-user?username=${profileName}`;
												// const jsonDataUrl = `${profileUrl}/?__a=1`;

												const response = await fetch(profileUrl);
												const jsonData = await response.json();
												if (response.ok) {
													return jsonData;
												} else {
													return "0";
												}
											}
											(async () => {
												try {
													const profileName = $( "#instaUser" ).val();
													const profile = {} = await fetchInstagramProfile();
													if (!profile || Object.keys(profile).length === 0 || profile.is_private) {
														const container = document.getElementById('InstaUserError')
														container.innerHTML = "Invalid or Private Instagram ID";
														container.style.display = "block";
													} else {
														const container = document.getElementById('InstaUserError')
														container.innerHTML = "";
														container.style.display = "none";
														// const private = photos.graphql.user["is_private"]
														// if (private === true) {
														// 	container.innerHTML = "Instagram ID is Private";
														// 	container.style.display = "block";
														// }else{
														// 	container.innerHTML = "";
														// 	container.style.display = "none";
														// 	if ($('#frmDetails').hasClass('Followers')){
														// 		var values = {};
														// 		var values = jQuery('#frmDetails').serializeArray();
														// 		$.ajax({
														// 			type: "POST",
														// 			data: values,
														// 			url: '<?php echo admin_url('admin-ajax.php'); ?>',
														// 			success: function(response) {
														// 				if(response == "success")
														// 				{
														// 					window.location.href = "<?php echo wc_get_checkout_url(); ?>";
														// 				}
														// 				else
														// 				{
														// 					window.location.reload(true);

														// 				}
														// 			},
														// 			error: function(response) {
																		
														// 			}
														// 		});
														// 	} else {
                            //                                     const productId = document.getElementById('insta-product-ID').value;
														// 		window.location.replace("<?php echo get_site_url(); ?>/checkout/select-posts/?username=".concat(profileName,"&addCart=").concat(productId));
														// 	}
															
														// }

														container.innerHTML = "";
														container.style.display = "none";
														if ($('#frmDetails').hasClass('Followers')){
															var values = {};
															var values = jQuery('#frmDetails').serializeArray();
															$.ajax({
																type: "POST",
																data: values,
																url: '<?php echo admin_url('admin-ajax.php'); ?>',
																success: function(response) {
																	if(response == "success")
																	{
																		window.location.href = "<?php echo wc_get_checkout_url(); ?>";
																	}
																	else
																	{
																		window.location.reload(true);

																	}
																},
																error: function(response) {
																	
																}
															});
														} else {
															const productId = document.getElementById('insta-product-ID').value;
															window.location.replace("<?php echo get_site_url(); ?>/checkout/select-posts/?username=".concat(profileName,"&addCart=").concat(productId));
														}
													}
												} catch (e) {
													console.error('Fetching Instagram photos failed', e)
												}
											})()
											event.preventDefault();
										});
									});
								</script>
								<div class="body">
									<form id="frmDetails" class="form <?php echo $categ; ?>" method="POST" action="">
										<div class="form-group">
											<img class="input-icon" src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/12/instagram.png">
											<input type="text" id="instaUser" name="username" class="form-control" value="" placeholder="Instagram Username">
											<span id="InstaUserError" class="red" style="display: none;">Username Not valid!</span>
										</div>
										<div class="form-group">
											<img class="input-icon pusht" src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/12/heart.png">
											<div class="selector">
												<div class="changeProduct">
													<button type="button" class="form-control" id="selectedproduct">
														<span class="desc"><span id="ptitle"><?php echo $_product->get_title(); ?></span> <?php echo $categ; ?></span><span class="alt"><?php echo get_woocommerce_currency_symbol() . '<span id="pprice">' . $_product->get_price() . '</span>'; ?></span></button>
													<div class="dropdown-menu" style="display: none;">
														<?php
														foreach ($products as $key => $value) {
															?>
															<a href="#" data-value="<?php echo $value->get_ID(); ?>" data-price="<?php echo $value->get_price(); ?>" data-title="<?php echo $value->get_title(); ?>" class="<?php if($value->get_title() === $_product->get_title()) echo "selected"?>"><span class="desc"><span id="aptitle"><?php echo $value->get_title(); ?></span> <?php echo $categ; ?></span><span class="alt"><?php echo get_woocommerce_currency_symbol(). '<span id="apprice">' . $value->get_price() . '</span>'; ?></span></a>
															<?php
														}
														?>
													</div>
												</div>
											</div>
										</div>
										<div class="text-center">
											<div class="mar-opt checkbox">
												<input id="marCheck" type="checkbox" name="mailopt">
												<label for="marCheck"><span>Send me special promotions and discounts</span></label>
											</div>
										</div>
										<input type="hidden" id="insta-product-ID" name="insta-product-ID" value="<?php echo $ProductID; ?>">
										<input type="hidden" id="useremail" name="useremail" value="<?php echo $user_email; ?>">
										<input type="hidden" id="userID" name="userID" value="<?php echo $userID; ?>">
										<?php wp_nonce_field( 'handle_detail_form', 'nonce_detail_form' ); ?>
										<input type="hidden" name="action" value="wpdetail_form" />
										<div class="button-group">
											<button id="instaSubmit" type="submit" class="btn btn-block" disabled="">Continue</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="eltdf-grid-col-5 eltdf-grid-col-ipad-portrait-12 eltdf-grid-col-phone-portrait-12">
							<div class="enter-details-review">
								<?php echo do_shortcode('[site_reviews display="1"]'); ?>
							</div>
						</div>
						
						<?php
						the_content();
						do_action('mrseo_elated_page_after_content');
						?>
					</div>
				</div>
				<?php if($eltdf_sidebar_layout !== 'no-sidebar') { ?>
					<div <?php echo mrseo_elated_get_sidebar_holder_class(); ?>>
						<?php get_sidebar(); ?>
					</div>
				<?php } ?>
			</div>
		<?php endwhile; endif; ?>
	</div>
	<?php do_action('mrseo_elated_before_container_close'); ?>
</div>
<?php get_footer(); ?>
