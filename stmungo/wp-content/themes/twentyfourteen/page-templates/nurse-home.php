<?php
/**
 * Template Name: Nurse Home
 */
get_header();
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
	?>
<div class="options">
	<div class="option one">
		<p>
			<a
				href="<?php echo get_permalink(get_page_by_title('Assigned Room'));?>">View
				the room you are assigned to</a>
		</p>
	</div>
	<div class="option two">
		<p>
			<a
				href="<?php echo get_permalink(get_page_by_title('Nurse Edit Profile'));?>">Edit
				profile</a>
		</p>
	</div>
</div>
	<?php } else {
		header("Location:" . site_url());
	}
	get_footer();