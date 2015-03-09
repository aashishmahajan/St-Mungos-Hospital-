<?php
/**
 * Template Name: Patient Edit Profile
 */
get_header();
/* Only if a patient is logged in, the following content will be displayed */
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 2) {
	$profile = new UpdateDatabaseOptions('hpusers');
	$patients = new UpdateDatabaseOptions('patients');
	if(isset($_POST['psubmit'])) {
		$error = 0;
		$name = mysql_real_escape_string($_POST['pname']);
		$contact = mysql_real_escape_string($_POST['contact']);
		$address = mysql_real_escape_string($_POST['address']);
		$email = mysql_real_escape_string($_POST['email']);
		$age = mysql_real_escape_string($_POST['age']);
		$gender = mysql_real_escape_string($_POST['gender']);
		$newPassword = mysql_real_escape_string($_POST['pnpwd']);
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
			if(!$patients->updateRow(
			array('age' => $age,
									'gender' => $gender),
			array('userid' => $_SESSION['userid']),
			array('%d', '%d'),
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
	$patientDetails = $patients->selectValue(array('age', 'gender'), array('userid' => $_SESSION['userid'])); ?>
<div class="leftnav">
<?php get_sidebar('patient');?>
</div>
<div class="main_content">
<?php if($message != '') {?>
	<div class="infobar">
	<?php echo $message;?>
	</div>
	<?php }?>
	<h2>Edit Profile</h2>
	<form name="patientEditProfile" action="" method="post">
		<p>
			<label for="pname">Name</label> <input type="text" name="pname"
				id="pname" value="<?php echo $profileDetails[0]['name'];?>" />
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
			<label for="age">Age</label> <input type="text" name="age" id="age"
				value="<?php echo $patientDetails[0]['age'];?>" />
		</p>
		<p>
			<label for="gender">Gender</label>
			<?php
			$fselected = '';
			$mselected = '';
			$checked = 'checked="checked"';
			if($patientDetails[0]['gender'] == 0) $mselected = $checked;
			else if($patientDetails[0]['gender'] == 1) $fselected = $checked;
			?>
			<input type="radio" name="gender" value="1" id="female"
			<?php echo $fselected; ?> /> Female <input type="radio" name="gender"
				value="0" id="male" <?php echo $mselected; ?> /> Male
		</p>
		<p>
			<strong>Change Password?</strong>
		</p>
		<p>
			<label for="pnpwd">New Password</label> <input type="password"
				name="pnpwd" id="pnpwd" />
		</p>
		<p>
			<label for="pcnpwd">Confirm New Password</label> <input
				type="password" name="pcnpwd" id="pcnpwd" />
		</p>
		<p>
			<input type="submit" name="psubmit" id="psubmit" value=""
				class="btm submit_btm" onclick="return patientProfileValidate();"/>
		</p>
	</form>
	<?php }
	else {
		header("Location:" . site_url());
	}
	get_footer();