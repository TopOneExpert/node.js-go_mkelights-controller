<?php
require_once 'db.php';
require_once 'lib/UserAgentParser.php';

use control\lib\UserAgentParser;

session_start();

header("Expires: Tue, 28 Aug 2007 12:34:56 GMT ");

//require_once 'includes/controller.php';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" data-useragent="<?= UserAgentParser::createUserAgentHtmlAttributeString() ?>">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Internet Controlled Christmas Lights :: MKELights.com</title>
	
	<meta name="description" content="Updated for 2019! Control our Christmas lights every night during the month of December!"/>
	<meta name="keywords" content="control, lights, christmas,santa, christmas lights"/>
	<meta name="author" content=""/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon/favicon.ico">
 <meta property='og:image' content='http://www.mkelights.com/images/sign.jpg'/>
	<meta name="application-name" content="&nbsp;"/>
	<meta name="msapplication-TileColor" content="#FFFFFF" />
	<link rel="manifest" href="/manifest.json">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
  <link rel="stylesheet" type="text/css" href="css/custom.css" media="screen"/>
	<script src="js/jquery.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		$( document ).ready(function() {
			$('.force-mobile-link').on('click', (function (e) {
				e.preventDefault();

				var expires = "";
				var name = 'forceDesktop';
				var value = '1';
				var date = new Date();

				date.setTime(date.getTime() + (24*60*60*1000));
				expires = "; expires=" + date.toUTCString();

				document.cookie = name + "=" + value + expires + "; path=/";
				location.reload();
			}));
		});
	</script>

	<?php if (!UserAgentParser::isMobileBrowser()): ?>
		<?php include_once 'includes/controlScript.php'; ?>
		<?php include_once 'includes/messageScript.php'; ?>
	<?php else: ?>
		<?php include_once 'includes/controlScript.php'; ?>
	<?php endif; ?>
 <!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-11765413-9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());

  gtag('config', 'UA-11765413-9');
</script>
<meta property="fb:admins" content="{10201733674216078}"/>
</head>

<body>
    
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=278675556169794&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php if (!UserAgentParser::isMobileBrowser() || isset($_COOKIE['forceDesktop'])): ?>
<div class="wrapper">
	<?php include './includes/header.php'; ?>

	<?php
	if (isset($_GET["page"]))
		include 'pages/' . $_GET["page"] . '.php';
	else
		include 'home.php';
	?>

	<center><?php include './includes/footer.php'; ?></center>
</div>
<?php else: ?>
<div class="mobile-wrapper">
	<?php include 'home-mobile.php'; ?>
</div>
<?php endif; ?>

<script type="text/javascript" language="JavaScript">
   // All credit goes to Alek from komar.org for this script. His website will always be the king of controllable Christmas lights! //
   var speed="300"                   //delay in milliseconds between letters
   var message1=""
   var message2a=" CONTROLLABLE"
   var message2b=" - * - * - * - * - * -"
   var message8=" Christmas"
   var message9=" Lights"
   var i = 0
   var j = 0
   var toggleoff = 1
   var tempmiddletitle=""
   function toggleit(){
      if (j == 9 ) {
         i=0
         tempmiddletitle=""
         setTimeout("titler()",20*speed)
      } else {
         if (toggleoff == 1) {
            document.title=message1+message2b+message8+message9
            toggleoff = 0
         } else {
            document.title=message1+message2a+message8+message9
            toggleoff = 1
         }
         j++;
         setTimeout("toggleit()",3*speed)
      }
   }
   function titler(){
      if (!document.all&&!document.getElementById) return
      tempmiddletitle=tempmiddletitle+message2a.charAt(i)
      document.title=message1+tempmiddletitle+message8+message9
      i++
      if(i==message2a.length) {
         toggleoff = 1
         j = 1
         setTimeout("toggleit()",speed)
      } else {
         setTimeout("titler()",speed)
      }
   }
setTimeout("titler()",15000);
</script>
</body>
offseason
</html>
