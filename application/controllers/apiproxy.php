<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API Proxy
 *
 * This controller will take requests from javascript and process them.
 *
 * TODO:
 * 		- Better security
 */

class ApiProxy extends CI_Controller {

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
				$this->output->set_status_header('500', 'Internal Server Error');
			}
		} else {
			$this->output->set_status_header('401', 'Unauthorized');
		}
	}

	/**
	 * Post
	 */
	public function post()
	{
		// Retrieve and test for the user id in the URI
		if ( ! $this->input->post('post') )
		{
			// The user id or following state is not present
			$this->output->set_status_header('400', 'Bad Request');
			die('Invalid request made!');
		}

		// Prepare the text
		$text = $this->input->post('post');

		try {
			$this->appdotnet->createPost($text);
		} catch (Exception $e) {
			die('There was an error trying to make your post.');
		}
	}

	/**
	 * Follow
	 */
	public function follow()
	{
		// Retrieve and test for the user id in the URI
		if ( ! $user_id = $this->uri->segment(3) OR ! isset($_POST['followingState']) )
		{
			// The user id or following state is not present
			$this->output->set_status_header('400', 'Bad Request');
			die('Invalid request made!');
		}

		$followingState = $this->input->post('followingState');

		// Make sure not trying to follow yourself
		if ($this->session->userdata('id') == $user_id) {
			$this->output->set_status_header('400', 'Bad Request');
			die('You cannot follow yourself. Are you narcissistic?');
		}

		switch ($followingState) {
			case '0':
				try {
					$this->appdotnet->followUser($user_id);
				} catch (Exception $e) {
					$this->output->set_status_header('500', 'Internal Server Error');
					die('App.net servers could not be reached!');
				}
				break;

			case '1':
				try {
					$this->appdotnet->unfollowUser($user_id);
				} catch (Exception $e) {
					$this->output->set_status_header('500', 'Internal Server Error');
					die('App.net servers could not be reached!');
				}
				break;
			
			default:
				$this->output->set_status_header('400', 'Bad Request');
				die('We could not determine your current following state!');
				break;
		}

	}

	/**
	 * Star / Unstar
	 */
	public function star()
	{
		// Make sure the post_id (in the URI) and the you_starred item are included
		if ( ! $this->uri->segment(3) OR  ! isset($this->input->post('you_starred')) ) {
			// The user id or following state is not present
			$this->output->set_status_header('400', 'Bad Request');
			die('Invalid request made!');
		}

		$post_id = $this->uri->segment(3);
		$you_starred = $this->input->post('you_starred');

		if ($you_starred) {
			print 'Trying to unstar '.$post_id;
		} else {
			print 'Trying to star '.post_id; 
		}
	}

}