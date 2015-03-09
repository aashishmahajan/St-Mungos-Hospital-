<?php
ob_start();
session_set_cookie_params(0);
session_start();
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="stylesheet"
	href="<?php bloginfo('template_url')?>/js/jquery-ui-1.11.2.custom/jquery-ui.min.css">
<link rel="stylesheet"
	href="<?php bloginfo('template_url')?>/js/jquery-ui-1.11.2.custom/jquery-ui.structure.min.css">
<link rel="stylesheet"
	href="<?php bloginfo('template_url')?>/js/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css">
<script src="//use.edgefonts.net/snippet;philosopher.js"></script>
<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
<?php wp_head(); ?>
<script
	src="<?php bloginfo('template_url')?>/js/jquery-ui-1.11.2.custom/jquery-ui.min.js"
	type="text/javascript"></script>
<script>
jQuery(function() {
	jQuery( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" , minDate: 0 });
  });
  </script>
</head>

<body <?php body_class(); ?>>
	<div class="outer_main">
		<div class="header">
			<h1>St. Mungo's Hospital</h1>
			<p>for Magical Maladies and Injuries</p>
		</div>
		<div class="infobar top">
			<?php if(isset($_SESSION['userid'])) { ?>
			<p>
			<?php echo 'Welcome <strong>' . $_SESSION['name'] . '</strong>!'; ?>
			<a href="<?php bloginfo(template_url);?>/Logout.php">Log out</a>
			</p>
			<?php }?>
		</div>
		<div class="page_main">