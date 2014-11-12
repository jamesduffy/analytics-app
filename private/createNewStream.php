<?php

require_once 'AppDotNet.php';

$appdotnet = new AppDotNet(
	// ADN keys
	'',
	'' 
	);

$token = $appdotnet->getAppAccessToken();

$stream = $appdotnet->createStream(array('post','star','user_follow','stream_marker','message','channel','channel_subscription','mute','token','file'));

print $stream['id'] . PHP_EOL;
print $stream['endpoint'] . PHP_EOL;

?>