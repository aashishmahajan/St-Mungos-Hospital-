<?php
/**
 * Template Name: Registration
 */
get_header();
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
	header('Location:' . get_permalink(27)); }
else if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 2) {
	header('Location:' . get_permalink(9)); }
else if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 3) {
	header('Location:' . get_permalink(19)); }
echo 'Welcome  <strong> Guest! </strong> If you have an account with us, please '; ?>
<a href="<?php echo site_url();?>">Log In</a>
<?php 
$profile = new UpdateDatabaseOptions('hpusers');
$patients = new UpdateDatabaseOptions('patients');
$message = '';
if(isset($_POST['rpsubmit'])) {
	$error = 0;
	$username = mysql_real_escape_string($_POST['runame']);
	$name = mysql_real_escape_string($_POST['rpname']);
	$contact = mysql_real_escape_string($_POST['rcontact']);
	$address = mysql_real_escape_string($_POST['raddress']);
	$email = mysql_real_escape_string($_POST['remail']);
	$age = mysql_real_escape_string($_POST['rage']);
	$gender = mysql_real_escape_string($_POST['rgender']);
	$password = mysql_real_escape_string($_POST['rpnpwd']);
	if($name != '' && $contact != '' && $address != '' && $email != '' && $gender != '' && $age != '') {
		if(!$profile->insertRow(
			array('userid' => $username, 'password' => $password, 'name' => $name,
									'contact' => $contact, 
									'addr' => $address, 
									'emailid' => $email, 'type' => 2),
			array('%s', '%s', '%s', '%d', '%s', '%s', '%d')))
			$error++;
		if(!$patients->updateRow(
			array('age' => $age, 'gender' => $gender),
			array('userid' => $username),
			array('%d', '%d'),
			array('%s')))
			$error++;
	}
	else {
		$message = "'Name', 'Contact Number', 'Address', 'Email ID', 'Age' and 'Gender' cannot be left blank";
	}
	if($error != 0) {
		$message = 'Sorry! Please try again.';
	}
	else {
		$login = site_url();
		$message = "Registration done!";
	}
}
?>
	<?php if($message != '') {?>
	<div class="infobar">
	<?php echo $message;?>
	</div>
	<?php }?>
<h2>Register here</h2>
<form name="patientRegister" action="" method="post">

	<p>
		<label for="runame">User Name <span class="red">*</span></label> <input type="text" name="runame"
			id="runame" />
	</p>
	<p><strong>Enter Password:</strong></p>
	<p>
		<label for="rpnpwd">Password <span class="red">*</span></label> <input type="password"
			name="rpnpwd" id="rpnpwd" />
	</p>
	<p>
		<label for="rpcnpwd">Confirm Password <span class="red">*</span></label> <input type="password"
			name="rpcnpwd" id="rpcnpwd" />
	</p>
	<p><strong>Details:</strong></p>
	<p>
		<label for="rpname">Name <span class="red">*</span></label> <input type="text" name="rpname"
			id="rpname" />
	</p>
	<p>
		<label for="rcontact">Contact Number <span class="red">*</span></label> <input type="text"
			name="rcontact" id="rcontact" />
	</p>
	<p>
		<label for="raddress">Address <span class="red">*</span></label> <input type="text"
			name="raddress" id="raddress" />
	</p>
	<p>
		<label for="remail">Email ID <span class="red">*</span></label> <input type="text" name="remail" id="remail" />
	</p>
	<p>
		<label for="rage">Age <span class="red">*</span></label> <input type="text" name="rage" id="rage" />
	</p>
	<p>
		<label for="rgender">Gender <span class="red">*</span></label> <input type="radio" name="rgender"
			value="1" id="rfemale" checked="checked"/> Female <input type="radio" name="rgender"
			value="0" id="rmale" /> Male
	</p>
	<p>
		<input type="submit" name="rpsubmit" id="rpsubmit" value="" class="btm submit_btm" onclick="return registerValidate();"/>
	</p>
</form>
<?php get_footer();