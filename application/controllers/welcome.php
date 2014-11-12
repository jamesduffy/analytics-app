<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Welcome
 *
 * This controller will show the welcome page and also authenticate the user
 * with ADN.
 *
 * TODO:
 *	- Where is the 504 error coming from? It is on this page somwhere....
 */

class Welcome extends CI_Controller {

	/**
	 * INDEX
	 *
	 * This function is the homepage for the website. It also will login users if there
	 * is an active OAuth authentication going on.
	 */
	public function index()
	{
		// Redirect logged in users to their dashboard.
		if ( $this->session->userdata('access_token') ) redirect('dashboard');

		// Check to see if there is an active authentication going on...
		if( $this->input->get('code') )
		{
			// Set the userdata that we use to make sure the user is logged in
			try {
				$this->session->set_userdata('access_token', $this->appdotnet->getAccessToken( $this->config->item('base_url') ) );
			} catch (Exception $e) {
				log_message('error', $e);
				show_error($e);
			}

			// Get some basic user information
			try {
				$userArray = $this->appdotnet->getUser();

				$this->session->set_userdata('username', $userArray['username']);
				$this->session->set_userdata('user_id', $userArray['id']);
				$this->session->set_userdata('avatar_url', $userArray['avatar_image']['url']);
			} catch (Exception $e) {
				show_error('There was an error setting your user data during login.');
			}
		}

		// If the user is logged in send them to their profile
		if( $this->session->userdata('access_token') ) redirect('dashboard');

		// Authorize as the Analytics-App so we can get the posts
		try {
			$this->appdotnet->setAccessToken( $this->appdotnet->getAppAccessToken() );
		} catch (Exception $e) {
			show_error('Unable to authenticate the server with App.net');
		}

		// Load the Welcome page.
		$this->load->view('welcome/homepage');
		//$this->load->view('layouts/footer');

	}
}
