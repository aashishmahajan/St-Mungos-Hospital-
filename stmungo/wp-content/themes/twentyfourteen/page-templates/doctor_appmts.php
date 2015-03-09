<?php
/**
 * Template Name: Doctor Appointments
 */
get_header();  

/* Only if a doctor is logged in, the following content will be displayed */
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 3) {
	global $wpdb;
	$tablename = $wpdb->prefix;
	$users = $wpdb->prefix."hpusers";
	$treats = $wpdb->prefix."treats";
	$treatments = $wpdb->prefix."treatments";
	$query = 'SELECT T.patient_id P, U.name N, T2.apt_date A
	FROM ' .$treats .' T, ' .$users .' U, ' .$treatments .' T2
	WHERE T.doctor_id = "' . $_SESSION['userid']. '" AND T.patient_id = U.userid AND T.bill_no = T2.bill_no;';
	$doctorAppointments = $wpdb->get_results($query, ARRAY_A); ?>
	<div class="leftnav"><?php get_sidebar('doctor');?></div>
<div class="main_content">
<h2>Your appointments</h2>
<?php if(count($doctorAppointments) != 0) { ?>
<table>
	<thead>
		<tr class="header_row">
			<th> Patient Name </th>
			<th> Appointment Date </th>
			<th> Patient's Userid </th>
			<th> Further Actions </th>
		</tr>
		<?php foreach ($doctorAppointments as $appointment) { ?>
		<tr>
			<td><?php echo $appointment['N'];?></td>
			<td><?php echo $appointment['A'];?></td>
			<td><?php echo $appointment['P'];?></td>
			<td><a href="<?php echo add_query_arg(
			array('patient_id' => $appointment['P'], 'apt_date' => $appointment['A']),get_permalink(get_page_by_title('Edit Treatment Details')));?>"> Edit Treatment </a></td>
		</tr>
		<?php } ?>
	<thead>
</table>
	<?php } else { ?>
		<div class="infobar">
<?php echo 'You have no appointments.';?>
</div>
</div>
<?php }
}
else {
	header ("Location:" .site_url());
}
get_footer();
?>