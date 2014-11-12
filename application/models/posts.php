<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends CI_Model {

	function __construct()
	{
		parent::__construct();

		// Load the caching driver
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}

	public function get($post_id)
	{
		if ( ! $post = $this->cache->get('post-'.$post_id))
		{
			$post = $this->appdotnet->getPost($post_id);

			$this->cache->save('post-'.$post_id, $post, 300);
		}

		return $post; 
	}

	// Gets one or multiple mosts with the ability to sort
	public function retrieve($post_ids, $sorted=FALSE) {
		if (! is_array($post_ids) ) {
			// Requesting only one post
			try {
				return $this->appdotnet->getPost($posts_ids, array('include_annotations' => 1));
			} catch (Exception $e) {
				show_error('Unable to retrieve a post from App.net!');
			}
		} else {
			// Requesting multiple posts
			// Turn the array into a string
			$post_ids_string = implode(',', $post_ids);

			// Get the posts from App.net
			try {
				$unsorted_posts = $this->appdotnet->getPosts( array( 'ids' => $post_ids_string, 'include_annotations' => 1 ) );
			} catch (Exception $e) {
				show_error('Unable to retreive posts from App.net!');
			}

			// Sort if need be
			if ( $sorted ) {
				// Sort the top posts returned by adn
				$sorted_posts = array(); // result array
				foreach( $post_ids as $result )
				{
					foreach ($unsorted_posts as $key => $val) {
						if ($val['id'] === $result) {
							$sorted_posts[] = $unsorted_posts[$key]; 
						}
					}
				}

				return $sorted_posts;
			} else {
				return $unsorted_posts;
			}
			
		}
	}
	
	public function total()
	{
		return $this->db->count_all('posts');
	}

	public function active_times_hours($user_id)
	{
		$sql = 'SELECT HOUR( created_at ) AS HOUR, COUNT( id ) 
			FROM posts
			WHERE user_id =  ?
			AND DATE( created_at ) >  ?
			GROUP BY HOUR';

		return $this->db->query($sql, array($user_id, date('Y-m-d', time()-(60*60*24*30)))); 
	}

	public function active_times_days($user_id)
	{
		$sql = 'SELECT DAYOFWEEK( created_at ) AS dayofweek, COUNT( id ) 
			FROM posts
			WHERE user_id =  ?
			AND DATE( created_at ) >  ?
			GROUP BY dayofweek';

		return $this->db->query($sql, array($user_id, date('Y-m-d', time()-(60*60*24*30)))); 
	}


	public function popular($user_id, $limit=10)
	{
		$sql = 
		   'SELECT *, ((num_replies) + (num_stars * 1.5) + (num_reposts * 2)) AS popularity
			FROM posts
			WHERE user_id = ?
			AND created_at > ?
			HAVING popularity > 0
			ORDER BY popularity DESC
			LIMIT ?';

		$query_data = $this->db->query($sql, array( $user_id, date('Y-m-d', time()-(60*60*24*7)), $limit ));
		$query_data = $query_data->result_array();

		return $query_data;
	}

	public function get_user_popular($user_id, $limit=10)
	{
		$sql = "SELECT id, ((num_replies) + (num_stars * 1.5) + (num_reposts * 2)) AS popularity
			FROM posts
			WHERE user_id = ?
			AND created_at > ?
			HAVING popularity > 0
			ORDER BY popularity DESC
			LIMIT ?";
		$query = $this->db->query($sql, array( $user_id, date('Y-m-d', time()-(60*60*24*7)), $limit ));

		if ( $query->num_rows() > 0 ) {
			foreach($query->result() as $post) {
				// Build query string
				//$unsorted_posts = 
			}
		}
	}

	public function popular_global($limit=15)
	{
		$sql = 
		   'SELECT *, ((num_replies) + (num_stars * 1.5) + (num_reposts * 2)) AS popularity
			FROM posts
			WHERE created_at > ?
			ORDER BY popularity DESC
			LIMIT ?';

		return $this->db->query($sql, array( date('Y-m-d', time()-(60*60*24*7)), $limit ));
	}

	public function popular_global_cache($limit=15)
	{
		$sql = 'SELECT * 
			FROM leaderboard_popular_posts
			JOIN posts ON leaderboard_popular_posts.id = posts.id
			ORDER BY leaderboard_popular_posts.popularity DESC
			LIMIT ?';

		return $this->db->query($sql, array($limit));
	}

	public function popular_clients($limit=15)
	{
		$sql = 
			'SELECT COUNT(id) AS rank, source_name, source_link
			FROM posts
			WHERE created_at > ?
			GROUP BY source_client_id
			ORDER BY rank DESC
			LIMIT ?';

		return $this->db->query($sql, array( date('Y-m-d', time()-(60*60*24*7)), $limit ));
	}

	public function posts_by_day($days=14)
	{
		$sql =
		   'SELECT DATE(created_at) AS  day, COUNT( DATE(created_at) ) AS  day_count 
			FROM  posts
			GROUP BY  day 
			ORDER BY  day DESC 
			LIMIT ?';

		return $this->db->query($sql, array( $days ));
	}

	public function posts_by_hour($hours=24)
	{
		$sql = '';

		return $this->db->query($sql, array( $hours ));
	}

}