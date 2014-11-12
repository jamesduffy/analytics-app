<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class People extends CI_Model {

	function __construct()
	{
		parent::__construct();

		// Load the caching driver
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
	}

	public function getUser($user_id)
	{
		if( $user = $this->cache->get('user'.$user_id) )
		{
			return $user;
		} else {
			try {
				$user = $this->appdotnet->getUser($user_id);

				// Save the user to the cache
				$this->cache->save('user'.$user_id, $user, 60*60*24*7);

				return $user;
			} catch (Exception $e) {
				show_error('Could not connect to App.net to look for a user.');
			}
		}
	}

	public function avatar($user_id)
	{
		$user = $this->getUser($user_id);

		return $user['avatar_image']['url'];
	}

	public function get_follower_trend($user_id)
	{
		$sql = 'SELECT
			SUM( 
				CASE WHEN is_deleted IS NULL 
				THEN 1 
				ELSE 0 
				END
			) -
			SUM( 
				CASE WHEN is_deleted IS NOT NULL 
				THEN 1 
				ELSE 0 
				END
			) AS trend
			FROM follows 
			WHERE  
				follows_user_id = ?
				AND DATE( created_at ) > ?';

		$trend_query = $this->db->query($sql, array($user_id, date('Y-m-d', time()-(60*60*24*14))));

		$trend_result = $trend_query->row();

		return $trend_result->trend;
	}
	
	public function get_follower_history($user_id)
	{
		$sql = 'SELECT
			DATE( created_at ) AS day,
			SUM( 
				CASE WHEN is_deleted IS NULL 
				THEN 1 
				ELSE 0 
				END
			) AS follows,
			SUM( 
				CASE WHEN is_deleted IS NOT NULL 
				THEN 1 
				ELSE 0 
				END
			) AS unfollows
			FROM 
				follows 
			WHERE 
				follows_user_id =  ?
			GROUP BY 1 
			ORDER BY day DESC
			LIMIT 14';
		return $this->db->query($sql, array($user_id));
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