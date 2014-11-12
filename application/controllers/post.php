<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Post
 *
 * This controller lets you view posts.
 *
 * TODO:
 *	- Make the comments look better
 *	- There is no pagination 
 */

class Post extends CI_Controller {

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
			$this->session->set_flashdata('error', 'You must be logged in to view that page.');
			redirect('/');
		}
	}

	/**
	 * View a post and direct comments
	 */
	public function index()
	{
		// Get the post id from the uri
		if (! $postId = $this->uri->segment(2)) redirect('/');

		// Try to get the post
		try {
			$data['posts'] = $this->appdotnet->getPost($postId);
		} catch (Exception $e) {
			show_error('The post does not seem to exist. Maybe it has been deleted?');
		}

		// Try to get the post's replies
		try {
			$data['replies'] = $this->appdotnet->getPostReplies($postId, array('count' => '200'));
		} catch (Exception $e) {
			show_error('There was a problem getting the post\'s replies.');
		} 

		// Pagination Library
		$this->load->library('pagination');

		// Pagination configuration
		$pagination_config['base_url'] = '';
		$pagination_config['total_rows'] = $data['posts']['num_replies'];
		$pagination_config['per_page'] = '200';
		$this->pagination->initialize($pagination_config);

		// Get the owner of the post
		$data['user'] = $data['posts']['user'];

		$this->load->view('layouts/header', array('page_title' => $data['user']['username']));
		$this->load->view('post/view', $data);
		$this->load->view('layouts/footer');
	}

	public function reposts()
	{
		// Get the post id from the uri
		if (! $postId = $this->uri->segment(2)) redirect('/');

		// Try to get the post
		try {
			$data['posts'] = $this->appdotnet->getPost($postId);
		} catch (Exception $e) {
			show_error('The post does not seem to exist. Maybe it has been deleted?');
		}

		// Try to get the post's reposters
		try {
			$data['reposts'] = $this->appdotnet->getReposters($postId, array('count' => '200')	);
		} catch (Exception $e) {
			show_error('There was a problem getting the post\'s reposters.');
		} 

		// Get the owner of the post
		$data['user'] = $data['posts']['user'];

		$this->load->view('layouts/header', array('page_title' => $data['user']['username']));
		$this->load->view('post/reposts', $data);
		$this->load->view('layouts/footer');
	}

	public function stars()
	{
		// Get the post id from the uri
		if (! $postId = $this->uri->segment(2)) redirect('/');

		// Try to get the post
		try {
			$data['posts'] = $this->appdotnet->getPost($postId);
		} catch (Exception $e) {
			show_error('The post does not seem to exist. Maybe it has been deleted?');
		}

		// Try to get the post's reposters
		try {
			$data['stars'] = $this->appdotnet->getStars($postId, array('count' => '200'));
		} catch (Exception $e) {
			show_error('There was a problem getting the post\'s stars.');
		} 

		// Get the owner of the post
		$data['user'] = $data['posts']['user'];

		$this->load->view('layouts/header', array('page_title' => $data['user']['username']));
		$this->load->view('post/stars', $data);
		$this->load->view('layouts/footer');
	}

}