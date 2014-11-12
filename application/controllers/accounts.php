<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	public function authorize()
	{
		// Check to see if there is an active authentication going on...
		if( $this->input->get('code') )
		{
			// Set the userdata that we use to make sure the user is logged in
			try {
				$this->session->set_userdata('access_token', $this->appdotnet->getAccessToken( $this->config->item('base_url') ) );
			} catch (Exception $e) {
				show_error($e);
			}
		
			// Get some basic user information
			try {
				$userArray = $this->appdotnet->getUser();
			} catch (Exception $e) {
				show_error($e);
			}

			$this->session->set_userdata('username', $userArray['username']);
			$this->session->set_userdata('user_id', $userArray['id']);
			$this->session->set_userdata('avatar_url', $userArray['avatar_image']['url']);

		}

		redirect('dashboard');
	}

}