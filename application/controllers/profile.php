<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profile
 *
 * This allows a user to view information about themselves or any other user.
 *
 * TODO:
 *	- Follower history will have days missing if there is no activity.
 *	- Follower history should have a graph
 */

class Profile extends CI_Controller {

	private $user;

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
			$this->session->set_flashdata('error', 'You must be logged in to view the profiles');
			redirect('/');
		}

		// Make sure a username was supplied 
		if (! $this->uri->segment(2)) {
			// No username was supplied, try to send to their own profile
			if ( $username = $this->session->userdata('username') ) {
				redirect('profile/'.$username);
			} else {
				// Redirect the unknown user to the welcome page.
				redirect('/');
			}
		}

		// Get the user
		try {
			$this->user = $this->appdotnet->getUser('@'.$this->uri->segment(2));
		} catch (Exception $e) {
			show_error('The user does not appear to exist.');
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function stats()
	{
		// Set the current page
		$data['current_page'] = 'stats';
		$data['user'] = $this->user;

		// Calculate the user's stats.
		if (!$this->user['counts']['following']) {
			$data['follower_to_following'] = $this->user['counts']['followers'];
		} else {
			$data['follower_to_following'] = round($this->user['counts']['followers'] / $this->user['counts']['following'], 2);
		}
		$data['total_posts'] = $this->user['counts']['posts'];
		$data['follower_trend'] = $this->people->get_follower_trend($this->user['id']);
		

		$data['most_popular_post'] = $this->posts->popular($this->user['id'], 1);

		// Load the page
		$this->load->view('layouts/header', array('page_title' => $this->user['username']));
		$this->load->view('profiles/stats', $data);
		$this->load->view('layouts/footer');
	}

	/**
	 * Posts Page
	 *
	 * Shows the most recent posts by the user that are stored in the local stats database.
	 */
	public function recent_posts()
	{
		// Set the current page
		$data['current_page'] = 'recent_posts';
		$data['user'] = $this->user;

		// Load the popular posts for the user
		$this->db->order_by('created_at', 'DESC');
		$data['recent_posts'] = $this->appdotnet->getUserPosts($this->user['id'], array('include_annotations' => 1) );

		// Load the page
		$this->load->view('layouts/header', array('page_title' => $this->user['username']));
		$this->load->view('profiles/recent_posts', $data);
		$this->load->view('layouts/footer');
	}

	/**
	 * Active Hours for a user.
	 */
	public function active_hour()
	{
		// Set the current page
		$data['current_page'] = 'active';
		$data['user'] = $this->user;

		// Load the active hours for the user.
		$data['active_hours'] = $this->posts->active_times_hours($this->user['id']);

		// Load the page
		$this->load->view('layouts/header', array('page_title' => $this->user['username']));
		$this->load->view('profiles/active_hour', $data);
		$this->load->view('layouts/footer');
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function popular()
	{
		// Set the current page
		$data['current_page'] = 'popular';
		$data['user'] = $this->user;

		// Load the popular posts for the user
		$data['popular_posts'] = $this->posts->popular($this->user['id'], 5);

		// Load the page
		$this->load->view('layouts/header', array('page_title' => $this->user['username']));
		$this->load->view('profiles/popular', $data);
		$this->load->view('layouts/footer');
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function followers()
	{
		// Set the current page
		$data['current_page'] = 'followers';
		$data['user'] = $this->user;

		// Get follower history
		$data['follower_history'] = $this->people->get_follower_history($this->user['id']);

		// Load the page
		$this->load->view('layouts/header', array('page_title' => $this->user['username']));
		$this->load->view('profiles/followers', $data);
		$this->load->view('layouts/footer');
	}

}