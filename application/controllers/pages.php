<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pages Controller
 *
 * Better static page support is coming in the next version of CI.
 * This will be updated when that version becomes more stable.
 */

class Pages extends CI_Controller {

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
	public function about()
	{
		$this->load->view('layouts/header', array('page_title' => 'About'));
		$this->load->view('pages/about');
		$this->load->view('layouts/footer');
	}

	/**
	 * Donate page
	 */
	public function donate()
	{
		$data['current_donation_amount'] = 50+10;

		$this->load->view('layouts/header', array('page_title' => 'Donate'));
		$this->load->view('pages/donate');
		$this->load->view('layouts/footer');
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function privacy()
	{
		$this->load->view('layouts/header', array('page_title' => 'Privacy Statement'));
		$this->load->view('pages/privacy');
		$this->load->view('layouts/footer');
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function terms()
	{
		$this->load->view('layouts/header', array('page_title' => 'Terms of Service'));
		$this->load->view('pages/terms');
		$this->load->view('layouts/footer');
	}

}