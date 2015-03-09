<?php
/**
 * Template Name: Doctor Edit Profile
 */
get_header();
/* Only if a doctor is logged in, the following content will be displayed */
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 3) {
	$profile = new UpdateDatabaseOptions('hpusers');
	$doctors = new UpdateDatabaseOptions('doctors');
	$message = '';
	if(isset($_POST['dsubmit'])) {
		$error = 0;
		$name = mysql_real_escape_string($_POST['dname']);
		$contact = mysql_real_escape_string($_POST['contact']);
		$address = mysql_real_escape_string($_POST['address']);
		$email = mysql_real_escape_string($_POST['email']);
		$qualification = mysql_real_escape_string($_POST['qualification']);
		$experience = mysql_real_escape_string($_POST['experience']);
		$newPassword = mysql_real_escape_string($_POST['dnpwd']);
		if($name != '' && $contact != '' && $address != '' && $email != '' && $qualification != '' && $experience != '') {
			if(!$profile->updateRow(array('name' => $name, 'contact' => $contact, 'addr' => $address, 'emailid' => $email), 
								array('userid' => $_SESSION['userid']),
								array('%s', '%d', '%s', '%s'),
								array('%s')))
				$error++;
			if(!$doctors->updateRow(array('qualification' => $qualification, 'experience' => $experience),
							array('userid' => $_SESSION['userid']),
							array('%s', '%f'),
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
			$message = 'Saved successfully.';
		}
	}
	$profileDetails = $profile->selectValue(array('name', 'contact', 'addr', 'emailid'), array('userid' => $_SESSION['userid']));
	$doctorDetails = $doctors->selectValue(array('qualification', 'experience'), array('userid' => $_SESSION['userid']));
	?>
<div class="leftnav">
<?php get_sidebar('doctor');?>
</div>
<div class="main_content">
<?php if($message != '') {?>
	<div class="infobar">
	<?php echo $message;?>
	</div>
	<?php }?>
	<h2>Edit Profile</h2>
	<form name="doctorEditProfile" action="" method="post">
		<p>
			<label for="dname">Name</label> <input type="text" name="dname"
				id="dname" value="<?php echo $profileDetails[0]['name'];?>" />
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
			<label for="qualification">Qualification</label> <input type="text"
				name="qualification" id="qualification"
				value="<?php echo $doctorDetails[0]['qualification'];?>" />
		</p>
		<p>
			<label for="experience">Experience</label> <input type="text"
				name="experience" id="experience"
				value="<?php echo $doctorDetails[0]['experience'];?>" />
		</p>
		<p><strong>Change Password?</strong></p>
		<p>
			<label for="dnpwd">New Password</label> <input type="password"
				name="dnpwd" id="dnpwd" />
		</p>
		<p>
			<label for="dcnpwd">Confirm New Password</label> <input
				type="password" name="dcnpwd" id="dcnpwd" />
		</p>
		<p>
			<input type="submit" name="dsubmit" id="dsubmit" value=""
				class="btm submit_btm" onclick="return doctorProfileValidate();" />
		</p>
	</form>
</div>
<?php }
else {
		header("Location:" . site_url());
	}
get_footer();