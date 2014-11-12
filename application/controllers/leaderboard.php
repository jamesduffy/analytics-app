<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard extends CI_Controller {

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
		} else {
			$this->session->set_flashdata('error', 'You must be logged in to view that page.');
			redirect('/');
		}

		// Redriect leaderboard without a date to today's date
		if (! $this->uri->segment(2)) redirect('leaderboard/'.date('Y-m-d'));
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$data['sidebar']['current_page'] = 'overview';

		// Get the days stored in the leaderboard cache
		$days_sql = 
			'SELECT
				DATE(day) as date
			FROM leaderboard_overview
			ORDER BY day DESC';
		$data['sidebar']['days_query'] = $this->db->query($days_sql);

		// What is today?
		$today = $this->uri->segment(2);

		// Check that a result was returned
		if( $data['leaderboard_data'] = $this->leaderboard_model->get_overview($today) )
		{
			$data['discovered_users'] = $this->people->count_discovered_users();
			$data['discovered_clients'] = $this->people->count_discovered_clients();
			
			// Load the page
			$this->load->view('layouts/header', array('page_title' => 'Leaderboard'));
			$this->load->view('leaderboard/overview', $data);
			$this->load->view('layouts/footer');
		} else {
			show_error('There is no leaderboard information for '.$today.'. It may still be processing and will be available shortly.');
		}
	}

	/**
	 * Top posts for the day
	 */
	public function posts()
	{
		// Set the current page
		$data['sidebar']['current_page'] = 'top_posts';

		// Get the days stored in the leaderboard cache
		$days_sql = 
			'SELECT
				DATE(day) as date
			FROM leaderboard_overview
			ORDER BY day DESC';
		$data['sidebar']['days_query'] = $this->db->query($days_sql);

		// What is today?
		$today = $this->uri->segment(2);

		// Check that a result was returned
		if( $data['top_posts'] = $this->leaderboard_model->get_top_posts($today) )
		{
			// Load the page
			$this->load->view('layouts/header', array('page_title' => 'Leaderboard'));
			$this->load->view('leaderboard/top_posts', $data);
			$this->load->view('layouts/footer');
		} else {
			show_error('There is no leaderboard information for '.$today.'. It may still be processing and will be available shortly.');
		}
	}

	/**
	 * Most Followed / Day
	 */
	public function top_followed()
	{
		// Set the current page
		$data['sidebar']['current_page'] = 'top_followed';

		// Get the days stored in the leaderboard cache
		$days_sql = 
			'SELECT
				DATE(day) as date
			FROM leaderboard_overview
			ORDER BY day DESC';
		$data['sidebar']['days_query'] = $this->db->query($days_sql);

		// What is today?
		$today = $this->uri->segment(2);

		if ( $data['top_followed'] = $this->leaderboard_model->get_top_followed($today) ) {
			// Load the page
			$this->load->view('layouts/header', array('page_title' => 'Leaderboard Followed'));
			$this->load->view('leaderboard/top_followed', $data);
			$this->load->view('layouts/footer');	
		} else {
			show_error('There is no leaderboard information for '.$today.'. It may still be processing and will be available shortly.');
		}
	}

	/**
	 * Top Clients / Day
	 */
	public function top_clients()
	{
		// Set the current page
		$data['sidebar']['current_page'] = 'top_clients';

		// Get the days stored in the leaderboard cache
		$days_sql = 
			'SELECT
				DATE(day) as date
			FROM leaderboard_overview
			ORDER BY day DESC';
		$data['sidebar']['days_query'] = $this->db->query($days_sql);

		// What is today?
		$today = $this->uri->segment(2);

		if ( $data['top_clients'] = $this->leaderboard_model->get_top_clients($today) )
		{
			// Load the page
			$this->load->view('layouts/header', array('page_title' => 'Leaderboard Clients'));
			$this->load->view('leaderboard/top_clients', $data);
			$this->load->view('layouts/footer');
		} else {
			show_error('There is no leaderboard information for '.$today.'. It may still be processing and will be available shortly.');			
		}	
	}

}