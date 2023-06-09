<?php require("controllers/_constants.php"); ?>
<?php require("controllers/_auth.php"); ?>
<?php require("controllers/_functions.php"); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
		<title><?= $c_title; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="<?= $c_description; ?>" />
		<meta name="keywords" content="<?= $c_keywords; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="application" />
		<meta property="og:title" content="<?= $c_description; ?>" />
		<meta property="og:url" content="<?= $dnelix_website; ?>" />
		<meta property="og:site_name" content="<?= $c_tagline; ?>" />
		<link rel="canonical" href="<?= $dnelix_website; ?>" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		
		<!--begin::Page Vendor Stylesheets(used by this page)-->
		<!--placed in the relevant component-->

		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head> 
