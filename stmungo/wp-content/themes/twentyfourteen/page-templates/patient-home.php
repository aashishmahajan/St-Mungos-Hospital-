<?php 
/**
 * Template Name: Patient Home
 */
get_header();
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 2) {
 ?>
<div class="options">
	<div class="option one">
		<p><a href="<?php echo get_permalink(get_page_by_title('Make Appointment'));?>">Make an appointment</a></p>
	</div>
	<div class="option two">
		<p><a href="<?php echo get_permalink(get_page_by_title('View Appointment'));?>">View all your appointments</a></p>
	</div>
	<div class="option two">
		<p><a href="<?php echo get_permalink(get_page_by_title('Make Payment'));?>">Make payment</a></p>
	</div>
	<div class="option one">
		<p><a href="<?php echo get_permalink(get_page_by_title('Patient Edit Profile'));?>">Edit profile</a></p>
	</div>
</div>
<?php } 
else {
	header("Location:" . site_url());
}
get_footer();