<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller {

	/**
	 * Do some work for this group of pages.
	 */
	function __construct()
	{
		parent::__construct();

		// Set the access token if the user has an active session
		if ( $this->session->userdata('access_token') ) {
			try {
				$this->appdotnet->setAccessToken( $this->session->userdata('access_token') );
			} catch (Exception $e) {
				show_error('Unable to connect to App.net servers.');
			}
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->load->view('layouts/header', array('page_title' => 'Help'));
		$this->load->view('help/overview');
		$this->load->view('layouts/footer');
	}

}