<?php
/**
 * Template Name: Login Page
 */
get_header();
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
	header('Location:' . get_permalink(27)); }
else if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 2) {
	header('Location:' . get_permalink(9)); }
else if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 3) {
	header('Location:' . get_permalink(19)); }
if(isset($_POST['log'])) {
	$username = $_POST['uname'];
	$password = $_POST['pwd'];
	$error = '';
	if($username != '' && $password != '') {
		$loginCheck = new UpdateDatabaseOptions('hpusers');
		$validateLogin = $loginCheck->selectValue(array('userid', 'name', 'type'), array('userid' => $username, 'password' => $password));
		if(count($validateLogin) != 0) {
			foreach($validateLogin as $login) {
				if($login['userid'] != '' && $login['userid'] == $username) {
					session_regenerate_id(true);
					$_SESSION['userid'] = $login['userid'];
					$_SESSION['usertype'] = $login['type'];
					echo $login['name'];
					$_SESSION['name'] = $login['name'];
					if($login['type'] == 1) {
						header('Location:' . get_permalink(27));
					}
					else if($login['type'] == 2) {
						header('Location:' . get_permalink(9));
					}
					else if($login['type'] == 3) {
						header('Location:' . get_permalink(19));
					}
					else {
						$error = 'Can\'t redirect as of now';
					}
				}
				else {
					$error = 'Username (or) Password is incorrect';
				}
			}
		}
		else {
			$error = 'Username (or) Password is incorrect';
		}
	}
}
?>
<div class="login_page">
<?php if($error != '') {?>
	<div class="infobar">
	<?php echo $error;?>
	</div>
	<?php }?>
	<p>The name of this hospital has been taken from the famous fictional novel series, "Harry Potter"</p>
	<p><strong>Please login or register to continue</strong></p>
	<form name="login" action="" method="post">
		<p>
			<label for="uname">Username</label> <input type="text" id="uname"
				name="uname" />
		</p>
		<p>
			<label for="password">Password</label> <input type="password"
				id="pwd" name="pwd" />
		</p>
		<p><input type="submit" value="" name="log" id="log"  class="btm login_btm" onclick="return loginValidate();"/></p>
	</form>
	<p>If you are a patient and do not already have an account, please <a href="<?php echo get_permalink(get_page_by_title('Register'));?>">sign up</a> here</p>
	<p>If you are a doctor or a nurse working in this hospital, please contact your Hospital Manager for access to your account</p>
</div>
<?php get_footer();?>
