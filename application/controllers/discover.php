<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Discover
 *
 * The idea behind this page is basically a copy of Twitter's Discover page.
 * It will show the logged in user what the people they follow are up to including
 * replies, stars, reposts and follows (maybe unfollows).
 *
 * Only available in the development environment.
 *
 * TODO:
 *		- Data is being saved to the database right now
 *		- A lot more work needs to be done here as well...
 */

class Discover extends CI_Controller {

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
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		try {
			$following_ids_array = $this->appdotnet->getFollowingIds( $this->session->userdata('user_id') );	
		} catch(Exception $e) {
			show_error('Unable to get your followers from ADN.');
		}

		$following_ids = implode(',', $following_ids_array);

		// Get the discover stream
		$sql =
			"SELECT 
				*,
				'repost' AS event_type
			FROM reposts
			WHERE
				user_id IN (".$following_ids.")

			UNION ALL

			SELECT
				*,
				'star' AS event_type
			FROM stars
			WHERE
				user_id IN (".$following_ids.")

			ORDER BY created_at DESC
			LIMIT 15";
		$query = $this->db->query($sql);

		// Get the posts that had actions on them
		$post_ids = array();
		foreach ($query->result() as $event) {
			$post_ids[] = $event->post_id;
		}
		$posts = $this->posts->retrieve($post_ids, TRUE);

		// Attach the events onto the posts
		$data['discover_stream'] = array();
		foreach ($query->result() as $event) {
			foreach( $posts as $post ) {
				if ( $post['id'] == $event->post_id ) {
					$data['discover_stream'][] = array(
						'user_id' => $event->user_id,
						'event_type' => $event->event_type,
						'post' => $post
					);
				}
			}
		}

		// Load the page 
		$this->load->view('layouts/header', array('page_title' => 'Discover'));
		$this->load->view('discover/index', $data);
		$this->load->view('layouts/footer');
	}

}