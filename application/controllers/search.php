<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Search
 *
 * Needs to be totally rewrittten. This has not been updated to work with the
 * new ADN search capabilities. 
 */

class Search extends CI_Controller {

	/**
	 * Do some work for this group of pages.
	 */
	function __construct()
	{
		parent::__construct();

		// Enable the profilier if in the development environment
		//if(ENVIRONMENT == 'development') $this->output->enable_profiler(TRUE);

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
	 * Search page
	 *
	 * This includes the search form and search results.
	 */
	public function index()
	{
		// Load the Form Validation class
		$this->load->library('form_validation');

		// Set some rules for the search form
		$this->form_validation->set_rules('query', 'Search', 'trim|required');

		// Load the page header and search form
		$this->load->view('layouts/header', array('page_title' => 'Search'));

		// Run the validation
		if( $this->form_validation->run() )
		{
			$query = $this->form_validation->set_value('query');
			$data['search_results'] = $this->appdotnet->searchUsers($query);

			$this->load->view('search/results', $data);
		} 

		// Load the page footer
		$this->load->view('layouts/footer');
	}

}