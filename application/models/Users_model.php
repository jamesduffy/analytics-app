<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	private $cache_time = (60*60*24*5);

	function __construct()
	{
		parent::__construct();

		// Load the caching driver
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
	
	public function get_user($user_id) {
		if( $user = $this->cache->get('user'.$user_id) )
		{
			return $user;
		} else {
			try {
				$user = $this->appdotnet->getUser($user_id);

				// Save the user to the cache
				$this->cache->save('user'.$user_id, $user, $this->cache_time);

				return $user;
			} catch (Exception $e) {
				show_error('Could not connect to App.net to look for a user.');
			}
		}
	}

	public function get_avatar($user_id) {
		$user = $this->get_user($user_id);

		return $user['avatar_image']['url'];
	}

}