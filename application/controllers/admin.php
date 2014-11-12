<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();

		error_reporting(-1);
		ini_set('display_errors', 1);

		// Redirect if not an admin
		//if($this->session->userdata('user_id') != '32231') redirect('/');
	}

	public function index()
	{
		// Redirect if not an admin
		if($this->session->userdata('user_id') != '32231') show_404();

		$this->load->view('layouts/header', array('page_title' => 'Admin Area'));
		$this->load->view('admin/index');
		$this->load->view('layouts/footer');
	}

	public function removerepost()
	{
		$post_id = $this->uri->segment('3');

		print $post_id.'<br>';

		// Log into ADN as MMMercury and remove repost
		try {
			$this->appdotnet->setAccessToken('xxx');
		} catch(Exception $e) {
			show_error('error', 'Problem authorizing with ADN.');
		}

		// Delte Repost
		try {
			$this->appdotnet->deleteRepost($post_id);
			print 'Post removed from stream.<br>';
		} catch(Exception $e) {
			show_error('error', 'Problem deleting repost with ADN.');
		}

		// Mark the post as published and far in the past
		$this->db->where('post_id', $post_id);
		$this->db->update('publishing_queue', array('published_status' => '1', 'published_at' => '1970-01-01 00:00:00' )); 
	
		print 'Post updated in queue.<br>';
	}

	/*public function view_queue()
	{
		$sql = "SELECT *
			FROM analytics_app_ne.publishing_queue
			JOIN analytics_app_ne.posts ON publishing_queue.post_id = posts.id";
		$data['queue'] = $this->db->query($sql);

		$this->load->view('layouts/header', array('page_title' => 'Admin Area'));
		$this->load->view('admin/queue', $data);
		$this->load->view('layouts/footer');
	}*/

	public function uninteresting_users()
	{
		// Redirect if not an admin
		if($this->session->userdata('user_id') != '32231') redirect('/');

		// Get all the uninteresting users
		$query = $this->db->get('uninteresting_users');
		$data['uninteresting_users'] = $query->result_array();

		$this->load->view('layouts/header', array('page_title' => 'Admin Area'));
		$this->load->view('admin/uninteresting_users', $data);
		$this->load->view('layouts/footer');	
	}

			public function create_uninteresting_user()
			{
				// Load the form validation library
				$this->load->library('form_validation');

				// Set some rules
				$this->form_validation->set_rules('new_uninteresting_user', 'New uninteresting user', 'required');

				if ( $this->form_validation->run() ) {
					$new_uninteresting_user = $this->input->post('new_uninteresting_user');

					// Get the user's informaiton
					try {
						$user_data = $this->appdotnet->getUser($new_uninteresting_user);
					} catch(Exception $e) {
						show_error($e);
					}

					$insert_data = array(
							'user_id' => $user_data['id'],
							'username' => $user_data['username']
						);

					$this->db->insert('uninteresting_users', $insert_data);
				}

				redirect('admin/uninteresting_users');
			}

			public function delete_uninteresting_user()
			{
				// Get the uninteresting user id
				if( $interesting_user = $this->uri->segment(3) ) {

					$this->db->delete('uninteresting_users', array('user_id' => $interesting_user));

					redirect('admin/uninteresting_users');
				} else {
					show_error('You need to specify a user to unblock as spammy.');
				}
			}

	public function uninteresting_clients()
	{
		// Redirect if not an admin
		if($this->session->userdata('user_id') != '32231') redirect('/');

		// Get all the uninteresting users
		$query = $this->db->get('uninteresting_clients');
		$data['uninteresting_clients'] = $query->result_array();

		$this->load->view('layouts/header', array('page_title' => 'Admin Area'));
		$this->load->view('admin/uninteresting_clients', $data);
		$this->load->view('layouts/footer');	
	}

			public function create_uninteresting_client()
			{
				// Load the form validation library
				$this->load->library('form_validation');

				// Set some rules
				$this->form_validation->set_rules('new_uninteresting_client', 'New uninteresting client', 'required');

				if ( $this->form_validation->run() ) {
					$new_uninteresting_client = $this->input->post('new_uninteresting_client');

					$client_query = $this->db->get_where('posts', array('source_client_name' => $new_uninteresting_client), 1);

					// Make sure there is a row returned
					foreach( $client_query->result_array() as $client ) {
						$client_query = $client_query->row();

						$insert_data = array(
							'client_id' => $client['source_client_id'],
							'client_name' => $client['source_client_name']
						);

						$this->db->insert('uninteresting_clients', $insert_data);

						redirect('admin/uninteresting_clients');
					}
				}
			}

			public function delete_uninteresting_client()
			{
				// Get the uninteresting user id
				if( $interesting_client = $this->uri->segment(3) ) {

					$this->db->delete('uninteresting_client', array('client_id' => $interesting_client));

					redirect('admin/uninteresting_clients');
				} else {
					show_error('You need to specify a user to unblock as spammy.');
				}
			}

}