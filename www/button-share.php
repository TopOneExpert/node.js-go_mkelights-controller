<?php
require_once 'db.php';
require_once 'lib/UserAgentParser.php';

use control\lib\UserAgentParser;

session_start();

require_once 'includes/controller.php';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" data-useragent="<?= UserAgentParser::createUserAgentHtmlAttributeString() ?>">
<head>
	<title>Internet Controlled Christmas Lights :: MKELights.com</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<meta name="description" content="Control our Christmas lights every night during the month of December!"/>
	<meta name="keywords" content="control lights, christmas,santa, christmas lights"/>
	<meta name="author" content=""/>
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon/favicon.ico">
 <meta property='og:image' content='http://www.mkelights.com/images/sign.jpg'/>
	<meta name="application-name" content="&nbsp;"/>
	<meta name="msapplication-TileColor" content="#FFFFFF" />
	<link rel="manifest" href="/manifest.json">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" type="text/css" href="css/buttonsOnly.css" media="screen"/>
	<script src="js/jquery.min.js" type="text/javascript"></script>

	<?php include_once 'includes/controlScript.php'; ?>

</head>
<body>

<?php include_once 'includes/controlButtons.php'; ?>

</body>
</html>
