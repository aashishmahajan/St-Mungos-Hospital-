<?php
/**
 * Template Name: Nurse Edit Profile
 */
get_header();
/* Only if a nurse is logged in, the following content will be displayed */
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
	$profile = new UpdateDatabaseOptions('hpusers');
	$message = '';
	if(isset($_POST['nsubmit'])) {
		$error = 0;
		$name = mysql_real_escape_string($_POST['nname']);
		$contact = mysql_real_escape_string($_POST['contact']);
		$address = mysql_real_escape_string($_POST['address']);
		$email = mysql_real_escape_string($_POST['email']);
		$newPassword = mysql_real_escape_string($_POST['npwd']);
		if($name != '' && $contact != '' && $address != '' && $email != '' && $gender != '' && $age != '') {
			if(!$profile->updateRow(
			array('name' => $name,
									'contact' => $contact, 
									'addr' => $address, 
									'emailid' => $email), 
			array('userid' => $_SESSION['userid']),
			array('%s', '%d', '%s', '%s'),
			array('%s')))
			$error++;
		}
		if($newPassword != '') {
			if(!$profile->updateRow(
			array('password' => $newPassword),
			array('userid' => $_SESSION['userid']),
			array('%s'),
			array('%s')))
			$error++;
		}
		if($error != 0) {
			$message = 'Sorry! Please try again.';
		}
		else {
			$message = 'Saved successfully';
		}
	}
	$profileDetails = $profile->selectValue(array('name', 'contact', 'addr', 'emailid'), array('userid' => $_SESSION['userid']));
	//$patientDetails = $patients->selectValue(array('age', 'gender'), array('userid' => $_SESSION['userid']));
	?>
<div class="leftnav">
<?php get_sidebar('nurse');?>
</div>
<div class="main_content">
<?php if($message != '') {?>
	<div class="infobar">
	<?php echo $message;?>
	</div>
	<?php }?>
	<h2>Edit Profile</h2>
	<form name="nurseEditProfile" action="" method="post">
		<p>
			<label for="nname">Name</label> <input type="text" name="nname"
				id="nname" value="<?php echo $profileDetails[0]['name'];?>" />
		</p>
		<p>
			<label for="contact">Contact Number</label> <input type="text"
				name="contact" id="contact"
				value="<?php echo $profileDetails[0]['contact'];?>" />
		</p>
		<p>
			<label for="address">Address</label> <input type="text"
				name="address" id="address"
				value="<?php echo $profileDetails[0]['addr'];?>" />
		</p>
		<p>
			<label for="email">Email ID</label> <input type="text" name="email"
				id="email" value="<?php echo $profileDetails[0]['emailid'];?>" />
		</p>
		<p>
			<strong>Change Password?</strong>
		</p>
		<p>
			<label for="npwd">New Password</label> <input type="password"
				name="npwd" id="npwd" />
		</p>
		<p>
			<label for="ncnpwd">Confirm New Password</label> <input
				type="password" name="ncnpwd" id="ncnpwd" />
		</p>
		<p>
			<input type="submit" name="nsubmit" id="nsubmit" value="" class="btm submit_btm" onclick="return nurseProfileValidate();" />
		</p>
	</form>
</div>
	<?php } else {
		header("Location:" . site_url());
	}
	get_footer();