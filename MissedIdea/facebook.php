<?php

define('YOUR_APP_ID', '320148841392206');

//uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
require 'fb-php-sdk/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => YOUR_APP_ID,
  'secret' => '43b5848708482a32ac45cc16cb901d19',
));

$userId = $facebook->getUser();

?>

 <html>
    <head>
      <title>MissedIdea FB Login</title>
    </head>
    <body>
      <div id="fb-root"></div>
		<?php if ($userId) { 
		  $userInfo = $facebook->api('/' + $userId); ?>
		  Welcome <?= $userInfo['name'] ?>
		<?php } else { ?>
		<fb:login-button></fb:login-button>
		<?php } ?>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '320148841392206',
            status     : true, 
            cookie     : true,
            xfbml      : true,
            oauth      : true,
          });
        };
        (function(d){
           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           d.getElementsByTagName('head')[0].appendChild(js);
         }(document));
      </script>
      <div class="fb-login-button">Login with Facebook</div>
    </body>
 </html>
 