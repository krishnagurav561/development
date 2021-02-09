<?php
/*
Template Name: Select Posts Page
*/
?>
<?php
$eltdf_sidebar_layout  = mrseo_elated_sidebar_layout();

get_header();
mrseo_elated_get_title();
get_template_part('slider');
$ProductID = $_GET["addCart"];
$UserName = $_GET['username'];
$_product = wc_get_product( $ProductID );
$terms = get_the_terms ( $ProductID, 'product_cat' );
foreach ( $terms as $term ) {
	if ($term->slug == "buy-instagram-likes" || $term->slug == "premium-likes" || $term->slug == "high-quality-likes") {
		$categ = "Likes";
	}elseif ($term->slug == "buy-instagram-followers" || $term->slug == "high-quality-followers" || $term->slug == "premium-followers") {
		$categ = "Followers";
	}elseif ($term->slug == "buy-instagram-views") {
		$categ = "Views";
	}else{
		$categ = "";
	}
}

if ( is_user_logged_in() ) {
	$userID = get_current_user_id();
	$user_info = get_userdata($userID);
	$user_email = $user_info->user_email;
} else {
    
}
?>
<div id="post_page" class="eltdf-container eltdf-default-page-template">
	<?php do_action('mrseo_elated_after_container_open'); ?>
	<div class="eltdf-container-inner clearfix">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="eltdf-grid-row">
				<div <?php echo mrseo_elated_get_content_sidebar_class(); ?>>
					<div class="eltdf-content-sidebar-upgrade"></div>
					<div class="eltdf-page-content-holder-inner">
						<form id="referal_form2" name="referal_form2" method="POST" action="">
							<div class="eltdf-grid-col-7 eltdf-grid-col-ipad-portrait-12 eltdf-grid-col-phone-portrait-12">
								<div class="select-posts-box">
									<div class="heading">
										<div class="title">Select posts</div>
									</div>
									<script type="text/javascript">
										let allPosts = [];
										function getUrlVars()
										{
											var vars = [], hash;
											var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
											for(var i = 0; i < hashes.length; i++)
											{
												hash = hashes[i].split('=');
												vars.push(hash[0]);
												vars[hash[0]] = hash[1];
											}
											return vars;
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

										const getInstagramProfilePic = async (profileName) => {
											const baseUrl = "https://instafo.ew.r.appspot.com";
											const profileUrl = `${baseUrl}/instagram-user?username=${profileName}`;
											// const jsonDataUrl = `${profileUrl}/?__a=1`;

											const response = await fetch(profileUrl);
											const jsonData = await response.json();
											if (response.ok) {
												return jsonData.profile_pic_url;
											}
										}

										const getInstagramCountPic = async (profileName) => {
											const baseUrl = "https://www.instagram.com";
											const profileUrl = `${baseUrl}/${profileName}`;
											const jsonDataUrl = `${profileUrl}/?__a=1`;

											const response = await fetch(jsonDataUrl);
											const jsonData = await response.json();

											if (response.ok) {
												return jsonData.graphql.user.edge_owner_to_timeline_media.count
											}
										}

										const fetchInstagramPhotos = async () => {
											const profileName = getUrlVars()["username"];
											const baseUrl = "https://instafo.ew.r.appspot.com";
											const profileUrl = `${baseUrl}/instagram-user-posts?username=${profileName}`;
											// const jsonDataUrl = `${profileUrl}/?__a=1`;

											const response = await fetch(profileUrl);
											const jsonData = await response.json();
											const edges = jsonData.user.edge_owner_to_timeline_media.edges.splice(0, 12);
											const photos =  edges.map(({ node }) => ({
												url: `https://www.instagram.com/p/${node.shortcode}/`,
												thumbnailUrl: node.thumbnail_src,
												displayUrl: node.display_url,
												caption: node.edge_media_to_caption.edges[0].node.text,
												shortcode: node.shortcode,
												is_video: node.is_video,
												liked: node.edge_media_preview_like.count
											}));
											return {
												photos,
												pageInfo: jsonData.user.edge_owner_to_timeline_media.page_info,
											}
										}
										const fetchInstagramNextPhotos = async (end_cursor) => {
											const profileName = getUrlVars()["username"];
											// var varr = encodeURIComponent(JSON.stringify({
											// 	id: owner_id,
											// 	first: 12,
											// 	after: end_cursor
											// }));										
											// const jsonDataUrl = "https://www.instagram.com/graphql/query/?query_hash=".concat("f2405b236d85e8296cf30347c9f08c2a", "&variables=").concat(varr);
											const jsonDataUrl = `https://instafo.ew.r.appspot.com/instagram-user-posts?username=${profileName}&after=${end_cursor}`;

											const response = await fetch(jsonDataUrl);
											const jsonData = await response.json();
											const edges = jsonData.user.edge_owner_to_timeline_media.edges.splice(0, 12);
											const info = jsonData.user.edge_owner_to_timeline_media.page_info;
											const data = edges.map(({ node }) => ({
												url: `https://www.instagram.com/p/${node.shortcode}/`,
												thumbnailUrl: node.thumbnail_src,
												displayUrl: node.display_url,
												caption: node.edge_media_to_caption.edges[0].node.text,
												shortcode: node.shortcode,
												is_video: node.is_video,
												liked: node.edge_media_preview_like.count
											}));
											return { data, info };
										}

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

										function calculatePost(){
											const checked = document.getElementsByName("post[]");
											let count = 0;
											checked.forEach((radio) => {
												if(radio.checked) count++;
											});
											document.getElementById('postCount').value = count;
											const totalCount = parseInt(document.getElementById('totalCount').value);
											const moduler = Math.round(totalCount/(count || 1));
										
											const maxCount = moduler < 25;
											if(count > 10) {								
												alert('You may select maximum of 10 for the current Plan');	
												return true;
											}
											if(maxCount) {
												alert("Yoy may not select less than 25 likes per post");
												return true;
											}
												document.getElementById('imgCount').innerHTML = count;
												document.getElementById('imgLikes').innerHTML = moduler;
											return false
										}
										
										function callCheck(){
											const checkbox = document.querySelectorAll("input.img-check");

											checkbox.forEach((el,i) => {
												el.addEventListener('click', function(e) {
													if(calculatePost()) {
														//e.target.disabled = true;
														e.target.checked = false;
														return;
													}
												})
												el.addEventListener('change', function(e) {
													// calculatePost();
													if (this.checked) {
														this.setAttribute('checked', true);
													} else {
														this.removeAttribute('checked');
													}	
												});
											});
										}

										(async () => {
											try {
												document.getElementsByTagName('body')[0].style.display="none";
												const userName = getUrlVars()["username"];
												let {photos, pageInfo} = await fetchInstagramPhotos();
												const container = document.getElementById('instagram-photos');
												const ProfilePic = await getInstagramProfilePic(userName);
												const profile = document.getElementById('insta-profile');
												// const InstagramAll = await getInstagramAll(userName);
												// let pageInfo = InstagramAll.graphql.user.edge_owner_to_timeline_media.page_info;
												// const owner_id = InstagramAll.graphql.user.id
												const end_cursor = pageInfo.end_cursor
												const has_next_page = pageInfo.has_next_page;
												allPosts = [...photos];
												profile.setAttribute('src', ProfilePic)
												const { value } = document.getElementById('category_type');

												document.getElementsByTagName('body')[0].style.display="block";

												photos.forEach((el,i) => {
													let newHtml = container.innerHTML + '<div class="post-cell"><div class="post"><img src="'+ el.thumbnailUrl +'"><label for="img_'+el.shortcode+'"> ';	
													if(value ==="Likes" || (value === 'Views' && el.is_video)) {
														newHtml = newHtml + '<input id="img_'+el.shortcode+'" class="img-check" type="checkbox" data-code="'+ el.shortcode +'" data-type="IMAGE" data-start="'+ el.liked +'" name="post[]" value="'+ el.shortcode +'">';
													}
													newHtml = newHtml + '<span></span></label><div class="img-info" style="display: none;"><span class="info-cnt">+100</span> likes</div></div></div>';		

													container.innerHTML = newHtml
												});
												callCheck();
												document.getElementById('loadMore').onclick = async function()
												{
													const { data: InstaNextPhotos, info } = await fetchInstagramNextPhotos(pageInfo.end_cursor);
													pageInfo=info;
													allPosts = [...allPosts, ...InstaNextPhotos];
													InstaNextPhotos.forEach((el,i) => {

														container.innerHTML = container.innerHTML + '<div class="post-cell"><div class="post"><img src="'+ el.thumbnailUrl +'"><label for="img_'+el.shortcode+'"><input id="img_'+el.shortcode+'" class="img-check" type="checkbox" data-code="'+ el.shortcode +'" data-type="IMAGE" data-start="'+ el.liked +'" name="post[]" value="'+ el.shortcode +'"><span></span></label><div class="img-info" style="display: none;"><span class="info-cnt">+100</span> likes</div></div></div>'
												})
													document.getElementById('loadMore').disabled = !pageInfo.has_next_page;
													callCheck();
												}

											} catch (e) {
												console.error('Fetching Instagram photos failed', e)
											}
										})()
										async function getInstagramPictures (profileName) {
											const baseUrl = "https://www.instagram.com";
											const profileUrl = `${baseUrl}/${profileName}`;
											const jsonDataUrl = `${profileUrl}/?__a=1`;

											const response = await fetch(jsonDataUrl);
											const jsonData = await response.json();
											const pictures = jsonData.graphql.user.edge_owner_to_timeline_media.edges;

											if (response.ok) {
												return pictures;
											} else {
												throw new Error(pictures);
											}
										}

										async function getInstagramValidation (profileName) {
											var baseUrl = "https://www.instagram.com";
											var profileUrl = `${baseUrl}/${profileName}`;
											var jsonDataUrl = `${profileUrl}/?__a=1`;

											var response = await fetch(jsonDataUrl);

											if (response.ok) {
												return true;
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
										console.log( "ready!" );								
									</script>

									<div class="body">
										<div class="panel-top">
											<div  id="product_header" class="panel">
												<p><b><span id='imgCount' class='imgCount'>0</span> posts</b> selected / <b><span id="imgLikes" class='imgLikes'><?php echo $_product->get_title() ?></span><?php echo "  ".$categ; ?></b> per post</p>
											</div>
										</div>
										<div id="instagram-photos" class="instagram-photos"></div>
										<div class="buttons">
											<button type="button" id="loadMore" class="btn btn-outline-orange btn-block loadMore">Load more posts</button>
										</div>
									</div>
								</div>
							</div>
							<div class="eltdf-grid-col-5 eltdf-grid-col-ipad-portrait-12 eltdf-grid-col-phone-portrait-12">
								<div class="order-review-page">
									<div class="body">
										<div class="r-user">
											<img id="insta-profile" class="user-image" src="">
											<div class="info">
												<div class="name">@<?php echo $UserName; ?></div>
												<div class="change"><a href="<?php echo get_site_url(); ?>/checkout/enter-details/?addCart=<?php echo $ProductID; ?>">Change username</a></div>
											</div>
										</div>
										<hr/>
										<div class="r-plan">
											<div class="r-item">
												<div class="icon"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/12/heart.png"></div>
												<div class="info"><span><strong><?php echo $_product->get_title(); ?></strong> <?php echo $categ; ?></span>
													<!-- <a href="/order/enter-details" class="change">Change</a> -->
												</div>
												<div class="price"><?php echo get_woocommerce_currency_symbol(); ?> <?php echo $_product->get_price(); ?></div>
											</div>
										</div>
										<input type="hidden" id="insta-product-ID" name="insta-product-ID" value="<?php echo $ProductID; ?>">
										<input type="hidden" id="insta-username" name="insta-username" value="<?php echo $UserName; ?>">
										<input type="hidden" id="useremail" name="useremail" value="<?php echo $user_email; ?>">
										<input type="hidden" id="userID" name="userID" value="<?php echo $userID; ?>">
										<input type="hidden" id="postCount" name="postCount" value="0">

										<input type="hidden" id="totalCount" name="totalCount" 
										value="<?php echo $_product->get_title();?>">
										<input type="hidden" id="category_type" name="category_type" value=<?php echo $categ;?> />
										<?php wp_nonce_field( 'handle_custom_form', 'nonce_custom_form' ); ?>
										<input type="hidden" name="action" value="wpreferral_form" />
										<hr>
										<button type="submit" class="btn btn-block btn-primary">Continue to checkout</button>
									
									</div>
								</div>
							</div>

						</form>
						
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
	<script type="text/javascript">

		jQuery(function($) {
			jQuery('#referal_form2').submit(function( event ) {
				event.preventDefault();
				var values = {};
				const posts = document.getElementsByName('post[]');
				const selected = [];
				posts.forEach(post => {
					if (post.checked) {
						selected.push(post.value);
					}
				});
				// const mappedPosts = allPosts.map(post => post.node);
				const filteredPosts = allPosts.filter(post => selected.includes(post.shortcode));
				localStorage.setItem('filterPost', JSON.stringify(filteredPosts));
				// var values = jQuery('#referal_form2').serializeArray();
				var values = {};
				var values = jQuery('#referal_form2').serializeArray();	
				// window.location.href = "<?php // echo wc_get_checkout_url(); ?>";
				jQuery.ajax({
					type: "POST",
					data: values,
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					beforeSend: function() { 
						jQuery("#referal_form2 .submit-loading").css('display', 'block');
					},	 
					success: function(response) {
						if(response == "success")
						{
							window.location.href = "<?php echo wc_get_checkout_url(); ?>";
						}
						else
						{
							window.location.reload(true);

						}
						jQuery("#referal_form2 .submit-loading").css('display', 'none');
					},
					error: function(response) {
						jQuery("#referal_form2 .submit-loading").css('display', 'none');
					}
				});
			});
		});

	</script>
</div>
<?php get_footer();?>
