<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}	

	public function get_overview($day)
	{
		// Benchmarking
		$this->benchmark->mark('Leaderboard_model.get_overview.start');

		// Get the leaderboard overview
		$leaderboard_overview_query = $this->db->get_where('leaderboard_overview', array('day' => $day), 1);

		// Check that there was a result
		if( $leaderboard_overview_query->num_rows() ) {
			// Take the data and decode the JSON
			return json_decode($leaderboard_overview_query->row()->data, TRUE);
		} else {
			// No result from the database, return false
			return FALSE;
		}

		// Benchmarking
		$this->benchmark->mark('Leaderboard_model.get_overview.end');
		$this->timekeeper_model->new_record(
			'Leaderboard_model.get_overview',
			$this->benchmark->elapsed_time('Leaderboard_model.get_overview.start', 'Leaderboard_model.get_overview.end'));
	}

	public function get_top_posts($day)
	{
		// Get the top posts
		$top_posts_query = $this->db->get_where('leaderboard_popular_posts', array('day' => $day), 1);

		// Check that there was a result
		if ( $top_posts_query->num_rows() ) {
			// Get the data from the row
			$top_posts_data = $top_posts_query->row();
			$top_posts_data = json_decode($top_posts_data->data, TRUE);

			// Create top_posts string
			$top_posts_id = '';
			foreach($top_posts_data as $result)
			{
				$top_posts_id .= $result['post_id'].',';
			}
			$top_posts_id = rtrim($top_posts_id, ",");

			// Get the top posts from ADN
			try {
				$top_posts_unsorted = $this->appdotnet->getPosts( array('ids' => $top_posts_id, 'include_annotations' => 1) );
			} catch (Exception $e) {
				show_error($e->getMessage());
			}

			// Sort the top posts returned by adn
			$top_posts = array(); // result array
			foreach( $top_posts_data as $result )
			{
				foreach ($top_posts_unsorted as $key => $val) {
					if ($val['id'] === $result['post_id']) {
						$top_posts[] = $top_posts_unsorted[$key]; 
					}
				}
			}

			return $top_posts;
		} else {
			// No result from the database, return false
			return FALSE;
		}
	}

	public function get_top_followed($day)
	{
		// Get the top followed users
		$top_followed_query = $this->db->get_where('leaderboard_top_followed', array('day' => $day), 1);

		// check that there was a result
		if ( $top_followed_query->num_rows() ) {
			$top_followed_json = $top_followed_query->row()->data;
			
			return json_decode($top_followed_json, TRUE);
		} else {
			// No result from the database, return false
			return FALSE;
		}
	}

	public function get_top_clients($day)
	{
		// Get the top clients
		$top_clients_query = $this->db->get_where('leaderboard_top_clients', array('day' => $day), 1);

		// Check that there was a result
		if ( $top_clients_query->num_rows() ) {
			return json_decode($top_clients_query->row()->data, TRUE);
		} else {
			// No result from the database, return false
			return FALSE;
		}
	}

	public function count_discovered_users()
	{
		if( $unique_user_ids = $this->cache->get('unique_user_ids') ) {
			return $unique_user_ids;
		} else {
			$sql = 'SELECT COUNT(DISTINCT user_id) AS unique_user_ids FROM posts';
			$query = $this->db->query($sql)->row();

			$unique_user_ids = $query->unique_user_ids;

			$this->cache->save('unique_user_ids', $unique_user_ids, 60*60*24);

			return $unique_user_ids;
		}
	}
	
	public function count_discovered_clients()
	{
		if( $unique_client_ids = $this->cache->get('unique_client_ids') ) {
			return $unique_client_ids;
		} else {
			$sql = 'SELECT COUNT(DISTINCT source_client_id) AS unique_client_ids FROM posts';
			$query = $this->db->query($sql)->row();

			$unique_client_ids = $query->unique_client_ids;

			$this->cache->save('unique_client_ids', $unique_client_ids, 60*60*24);

			return $unique_client_ids;
		}
	}

}