<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dashboard
 *
 * I still really want to make a web client for ADN, but it doesn't work yet.
 * Right now it just shows the user their unifed stream, but this page will
 * redirect to the logged in user's profile if the environment is not set to 
 * development.
 *
 * TODO:
 *		- Wayyy tooo much
 */

class Dashboard extends CI_Controller {

	/**
	 * Do some work for this group of pages.
	 */
	function __construct()
	{
		parent::__construct();
		
		// We are only testing the dashboard
		if(ENVIRONMENT != 'development')
			redirect('profile/'.$this->session->userdata('username'));

		// Set the access token if the user has an active session
		if ( $this->session->userdata('access_token') ) {
			try {
				$this->appdotnet->setAccessToken( $this->session->userdata('access_token') );
			} catch (Exception $e) {
				show_error('Unable to connect to App.net servers.');
			}
		} else {
			$this->session->set_flashdata('error', 'You must be logged in to view the search page');
			redirect('/');
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		// Get the user's unified stream
		try {
			$data['stream'] = $this->appdotnet->getUserUnifiedStream( array('include_annotations' => 1) );
		} catch (Exception $e) {
			show_error($e);
		}

		$this->load->view('layouts/header', array('page_title' => 'Dashboard'));
		$this->load->view('dashboard/main', $data);
		$this->load->view('layouts/footer');
	}

	/**
	 * Mentions
	 */
	public function mentions()
	{
		// Get the user's mentions
		try {
			$data['mentions'] = $this->appdotnet->getUserMentions('me', array('include_annotations' => 1) );
		} catch (Exception $e) {
			show_error($e);
		}

		$this->load->view('layouts/header', array('page_title' => 'Metions'));
		$this->load->view('dashboard/mentions', $data);
		$this->load->view('layouts/footer');
	}
}