<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Settings
 *
 * Will one day hold profile settings as well as a way to opt out of tracking.
 *
 * This is also where we destroy sessions.
 */

class Settings extends CI_Controller {

	/**
	 * INDEX
	 *
	 */
	public function index()
	{
		$this->load->view('layouts/header', array('page_title' => 'Settings'));
		$this->load->view('settings/general');
		$this->load->view('layouts/footer');
	}

	/**
	 * LOGOUT
	 *
	 * Destroy all session data and redirect back to the homepage.
	 */
	public function logout()
	{
		$this->session->sess_destroy();

		redirect('/');
	}
}