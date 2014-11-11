<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php wp_title("/", "RIGHT"); bloginfo( "name" ); ?></title>
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_stylesheet_directory_uri(); ?>/css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_stylesheet_directory_uri(); ?>/css/foundation.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_stylesheet_uri(); ?>" />
	<?php wp_head(); ?>
</head>

<?php flush(); ?>

<body <?php body_class(); ?>>

	<!--HEADER-->
	<header>
		<a href="/" title="This Was Broken" class="logo left">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" alt="Logotype">
		</a>
		<ul class="nav main-nav">
			<?php wp_nav_menu(array(
				'theme_location'  => 'header-menu',
				'menu'            => 'Header Menu',
				'container'       => 'div',
				'container_class' => 'right',
				'menu_class'      => 'nav main-nav',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'items_wrap'      => '%3$s',
				'depth'           => '1',
			)); ?>
		</ul>
	</header>