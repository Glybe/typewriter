<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html <?= get_language_attributes() ?>>
<head>
	<meta http-equiv="content-type" content="<?= get_bloginfo('html_type'); ?>; charset=UTF-8"/>
	<meta name="description" content="<?= esc_attr(get_bloginfo('description')) ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php
	wp_head();
	?>
</head>
<body class="<?= implode(' ', get_body_class()) ?>">
