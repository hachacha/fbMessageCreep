<?php
/**
* Copyright 2011 Facebook, Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may
* not use this file except in compliance with the License. You may obtain
* a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
* WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
* License for the specific language governing permissions and limitations
* under the License.
*/

require"facebook.php";

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId' => '445653338868536',
  'secret' => '750aefdb254666835c02d1fb9d9fb9e8',
));

// Get User ID
//$user = $facebook->getUser();
//echo $user;
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.
//echo "<div id='foo'>".$facebook->getLoginUrl()."</div>";

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $params = array(
  'scope' => 'read_mailbox, friends_likes',
  'redirect_uri' => 'http://glugluglugluglug.net/fbCreep/index.php'
);
  $loginUrl = $facebook->getLoginUrl($params);
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');


// echo 'Read Mailbox';
// $fql = "SELECT message_id,source, created_time, body,viewer_id, author_id, thread_id FROM message WHERE thread_id = 376659462449930 order by created_time desc limit 20";

// $response = $facebook->api(array(
// 'method' => 'fql.query',
// 'query' =>$fql,
// ));

// print_r($response);

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>php-sdk</title>
<style>
body {
font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
}
h1 a {
text-decoration: none;
color: #3b5998;
}
h1 a:hover {
text-decoration: underline;
}
</style>
</head>
<body>
<h1>php-sdk</h1>

<?php if ($user): ?>
<a href="<?php echo $logoutUrl; ?>">Logout</a>
<?php else: ?>
<div>
Check the login status using OAuth 2.0 handled by the PHP SDK:
<a href="<?php echo $statusUrl; ?>">Check the login status</a>
</div>
<div>
Login using OAuth 2.0 handled by the PHP SDK:
<a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
</div>
<?php endif ?>

<h3>PHP Session</h3>
<pre><?php print_r($_SESSION); ?></pre>

<h3>You</h3>
<img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

<h3>Your User Object (/me)</h3>
<pre><?php print_r($user_profile); ?></pre>

</body>


</html>