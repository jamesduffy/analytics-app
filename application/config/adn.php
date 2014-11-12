<?php

// ADN Variables
$config['client_id'] = '';
$config['client_secret'] = '';

$config['scopes'] = array(
		'basic', 'write_post', 'stream', 'follow', 'public_messages', 'update_profile', 'files'
	);

$config['authenticate_url'] = 'https://account.app.net/oauth/authenticate?';
	$config['authenticate_url'] .= 'client_id='.$config['client_id'];
	$config['authenticate_url'] .= '&response_type=code';
	$config['authenticate_url'] .= '&redirect_uri=';
	$config['authenticate_url'] .= '&scopes='.'basic+write_post+stream+follow+public_messages+update_profile+files';



$config['admin_email'] = '';