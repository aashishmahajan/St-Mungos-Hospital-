<?php
/**
 * The Sidebar for patient
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<div class="menu two">
	<p>
		<a
			href="<?php echo get_permalink(get_page_by_title('Doctor Home'));?>">Home</a>
	</p>
</div>
<div class="menu two">
	<p>
		<a href="<?php echo get_permalink(get_page_by_title('View all appointments'));?>">View your appointments</a>
	</p>
</div>
<div class="menu two">
	<p>
		<a href="<?php echo get_permalink(get_page_by_title('Doctor Edit Profile'));?>">Edit profile</a>
	</p>
</div>

