<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Command Line Interface Controller
 *
 * This controller will only accept requests from the command line. It will process
 * the leaderboard data.
 *
 * TODO:
 *		- Move SQL statements into models
 */

class Cli extends CI_Controller {

	private $MMMercury_Pushover = 'a1CTrRbhxGaPLQvySGmwDJBaHPikMo';
	private $Pushover_Account = 'd53rl2ah2KR47GGo7uL5lxtec7dQAl';

	private $MMMercury_accounts = array(
			'en' => 'AQAAAAAAB0eAqe6aU_ob9VzscGumczSYRAIpVFb6H0eUS4IVqUhcMCs07h5q-fobX4vi2ag0cJo3myXZuVbdpuFf5C3S6g70PQ',
			'de' => 'AQAAAAAACcDuXROuXOC6m8Hk_BTvoJoU-7LKBLHFAJkU-92ONK2VzZ31_KdlojxwS5CAXBGkV_xNC1ge_TCKP7D3T1cct6efCQ',
			'fr' => 'AQAAAAAACcDwSCaH1viTlbGY6tP3kezxm0SMTlGEiNCm9_3-J4Ow9mUo8ZX_goXwQZ1Ohf9P-0WNOlZ4JDOkaZF6E_48Vij-3Q',
			'ru' => 'AQAAAAAACcDz8ED2pWlK2URIE8TjKKDmvsf2QewbiTSwufQPdQOEM--A20duUesVtC34CDIj-YpaYOD7yCVFoxn39j6-Y2ddLw'
		);

  private $MMMercury_broadcast_channel = '47468';

	function __contruct()
	{
		parent::__construct();

		// Make sure the request is being made through the command line
		if(! is_cli()) redirect('/');
	}

	/**
	 * MMMercury
	 */
	public function queue_posts()
	{
		foreach($this->MMMercury_accounts AS $language => $access_token) {
			// Find the Trending Posts
			$trending_posts_array = $this->trending_posts($language);

			// Add to the queue
			foreach ($trending_posts_array as $trending_post) {
				// Check that post doesn't exist or already published
				$check_status_query = $this->db->get_where('publishing_queue', array('post_id' => $trending_post['id']));

				if ( $check_status_query->num_rows() < 1 ) {
					// no posts exist yet so lets create it
					$insert_data = array(
							'post_id' => $trending_post['id'],
							'created_at' => date('Y-m-d h:m:s'),
							'published_status' => '0',
							'language' => $trending_post['detected_language']
						);
					$this->db->insert('publishing_queue', $insert_data);

					// Try to get the posts data from ADN
					try {
						$post_data = $this->appdotnet->getPost($trending_post['id']);
					} catch(Exception $e) {
						log_message('error', 'Unable to get the post_data from the ADN API while queueing posts!');
					}

					// Notify over pushover
					if ($language == 'en') {
						$this->pushover->setToken( $this->MMMercury_Pushover );
						$this->pushover->setUser( $this->Pushover_Account );

						$this->pushover->setTitle('New MMMercury post from '.$post_data['user']['username']);
						$this->pushover->setMessage( $post_data['text'] );
						$this->pushover->setUrl( site_url('admin/removerepost/'.$post_data['id']) );
						$this->pushover->setUrlTitle('Remove Post');

						$this->pushover->setPriority(0);
						$this->pushover->setTimestamp(time());
						$this->pushover->setSound('bike');

						$pushover_request = $this->pushover->send();
					}
				}
			}
		}
	}

	/**
	 * MMMercury Trending Posts
	 */
	private function trending_posts($language) {
		$date = date('Y-m-d H:i:s', (time()-(60*60*10)) );

		// SQL to get popular posts
		$trending_sql =
			"SELECT
				*
			FROM posts
			WHERE
				created_at > '$date' AND
				reply_to IS NULL AND
				detected_language = '$language' AND
				user_id NOT IN ( SELECT user_id FROM uninteresting_users ) AND
				source_client_id NOT IN ( SELECT client_id FROM uninteresting_clients ) AND
				id NOT IN ( SELECT post_id FROM publishing_queue ) AND
				num_stars > 1 AND num_reposts > 1 AND
				((num_stars*1.5) + (num_reposts*2)) >
						(
							SELECT AVG((num_stars*1.5) + (num_reposts*2)) * 400
							FROM posts
							WHERE created_at > '$date' AND detected_language = '$language'
						)
			ORDER BY ((num_stars*1.5) + (num_reposts*2)) DESC
			LIMIT 4";

		// Run the query
		$query = $this->db->query($trending_sql);

		// Create Array of data
		return $query->result_array();
	}

	/**
	 * MMMercury Publish Posts
	 */
	public function publish_post() {
		foreach($this->MMMercury_accounts AS $language => $access_token) {
			$sql = "SELECT *
				FROM publishing_queue
				WHERE
					published_status = '0' AND
					language = '$language'
				ORDER BY created_at DESC, id
				LIMIT 1";

			$publish_queue = $this->db->query($sql);

			foreach( $publish_queue->result() as $post ) {
				// Authorize with ADN
				try {
					$this->appdotnet->setAccessToken($access_token);
				} catch(Exception $e) {
					log_message('error', 'Problem authorizing with ADN from the CLI');
				}

				// Repost the post
				try {
					$this->appdotnet->repost($post->post_id);
				} catch(Exception $e) {
					log_message('error', 'Problem reposting with ADN from the CLI');
				}

				// Brodcast the post if it is english
				if( $post->language = 'en' ) {
				  // Get the post from ADN
				  try {
				    $post_data = $this->appdotnet->getPost($post->post_id);
				  } catch(Exception $e) {
				    log_message('error', 'Problem retreiving post from ADN from the CLI');
				  }

				  // Post the broadcast to the ADN API
				    // Headline
				    $broadcast_data["annotations"][] = array(
				        "type" => "net.app.core.broadcast.message.metadata",
				        "value" => array(
				            "subject" => 'Trending posts from '.$post_data['user']['username'],
				        )
				    );

				    // Image if it exsits
				    // Needs to be updated to include this

				    // Text
				    $broadcast_data['text'] = $post_data['text'];

				    // Read More Link
				    $broadcast_data["annotations"][] = array(
				        "type" => "net.app.core.crosspost",
				        "value" => array(
				            "canonical_url" => $post_data['canonical_url'],
				        )
				    );

				  $this->appdotnet->createMessage($this->MMMercury_broadcast_channel, $broadcast_data);
				}

				// Mark the post as published
				$this->db->where('post_id', $post->post_id);
				$this->db->update('publishing_queue', array('published_status' => '1', 'published_at' => date('Y-m-d H:i:s')));
			}
		}
	}

	/**
	 * Process Leaderboard
	 */
	public function process_leaderboard()
	{
		$today = date('Y-m-d');

		$this->leaderboard_overview($today);
		$this->leaderboard_popular_posts($today);
		$this->leaderboard_top_followed($today);
		$this->leaderboard_top_clients($today);
	}

	/**
	 * Leaderboard Overview Data
	 */
	private function leaderboard_overview($today)
	{
		// Total Posts
		$total_posts_sql =
			'SELECT
			  COUNT(id) AS total_posts
			FROM
			  posts
			WHERE
			  created_at BETWEEN \''.$today.' 00:00:00\' AND \''.$today.' 23:59:59\'
			LIMIT 1';
		$total_posts = $this->db->query($total_posts_sql);
		$total_posts = $total_posts->row();

		// Total Unique Users
		$total_unique_users_sql =
			'SELECT
			  COUNT(DISTINCT user_id) AS total_users
			FROM
			  posts
			WHERE
			  created_at BETWEEN \''.$today.' 00:00:00\' AND \''.$today.' 23:59:59\'';
		$total_unique_users = $this->db->query($total_unique_users_sql);
		$total_unique_users = $total_unique_users->row();

		// Total Unique Clients
		$total_unique_clients_sql =
			'SELECT
			  COUNT(DISTINCT source_client_id) AS total_clients
			FROM
			  posts
			WHERE
			  created_at BETWEEN \''.$today.' 00:00:00\' AND \''.$today.' 23:59:59\'';
		$total_unique_clients = $this->db->query($total_unique_clients_sql);
		$total_unique_clients = $total_unique_clients->row();

		// Post Volume Per Hour
		$post_volume_sql =
			'SELECT
			  HOUR(created_at) AS hour,
			  count(id) AS total_posts
			FROM
			  posts
			WHERE
			  created_at BETWEEN \''.$today.' 00:00:00\' AND \''.$today.' 23:59:59\'
			GROUP BY
			  HOUR(created_at)';
		$post_volume = $this->db->query($post_volume_sql);
		$post_volume_data = $post_volume->result_array();

		$data = array(
				'total_posts' => $total_posts->total_posts,
				'total_users' => $total_unique_users->total_users,
				'total_clients' => $total_unique_clients->total_clients,
				'post_volume' => $post_volume_data
			);
		$data = json_encode($data);

		// Insert into the table
		$insert_sql =
			'INSERT INTO leaderboard_overview (day, data)
			VALUES(?, ?) ON DUPLICATE KEY UPDATE data=values(data)';
		$query2 = $this->db->query($insert_sql, array( $today, $data ));
	}

	/**
	 * Popular Posts Leaderboard Data
	 */
	private function leaderboard_popular_posts($today)
	{
		// SQL to get popular posts
		$popular_sql =
			'SELECT
		   		id AS post_id,
		   		created_at AS popular_on,
		   		((num_replies) + (num_stars * 1.5) + (num_reposts * 2)) AS popularity
			FROM posts
			WHERE
				created_at BETWEEN \''.$today.' 00:00:00\' AND \''.$today.' 23:59:59\' AND
				((num_replies) + (num_stars * 1.5) + (num_reposts * 2)) > 0
			ORDER BY popularity DESC
			LIMIT 25';

		// Run the query
		$query = $this->db->query($popular_sql);

		// Create Array of data
		$data_array = $query->result_array();

		// Convert to JSON
		$data_json = json_encode($data_array);

		// Insert or update the data into the database.
		$insert_sql =
			'INSERT INTO leaderboard_popular_posts (day, data)
			VALUES(?, ?) ON DUPLICATE KEY UPDATE data=values(data)';
		$query2 = $this->db->query($insert_sql, array( $today, $data_json ));
	}

	/**
	 * Top Clients Leaderboard Data
	 */
	private function leaderboard_top_clients($today)
	{
		// SQL to get popular clients
		$clients_sql =
			'SELECT
			  source_client_id,
			  source_name,
			  source_link,
			  COUNT(id) as total_posts
			FROM posts
			WHERE
			  created_at BETWEEN \''.$today.' 00:00:00\' AND \''.$today.' 23:59:59\'
			GROUP BY
			  source_client_id
			ORDER BY
			  total_posts DESC
			LIMIT 25';

		// Run the query
		$query = $this->db->query($clients_sql);

		// Create Array of data
		$data_array = $query->result_array();

		// Convert to JSON
		$data_json = json_encode($data_array);

		// Insert or update the data into the database.
		$insert_sql =
			'INSERT INTO leaderboard_top_clients (day, data)
			VALUES(?, ?) ON DUPLICATE KEY UPDATE data=values(data)';
		$query2 = $this->db->query($insert_sql, array( $today, $data_json ));
	}

	/**
	 * Top Followed Leaderboard Data
	 */
	private function leaderboard_top_followed($today)
	{
		// SQL to get popular clients
		$top_followed_sql =
			'SELECT
				follows_user_id,
				SUM(
					CASE WHEN is_deleted IS NULL
					THEN 1
					ELSE 0
					END
				) AS follows
			FROM follows
			WHERE created_at BETWEEN \''.$today.' 00:00:00\' AND \''.$today.' 23:59:59\' AND
			user_id NOT IN (
				SELECT user_id FROM uninteresting_users
			)
			GROUP BY 1
			ORDER BY follows DESC
			LIMIT 25';

		// Run the query
		$query = $this->db->query($top_followed_sql);

		// Create Array of data
		$data_array = $query->result_array();

		// Convert to JSON
		$data_json = json_encode($data_array);

		// Insert or update the data into the database.
		$insert_sql =
			'INSERT INTO leaderboard_top_followed (day, data)
			VALUES(?, ?) ON DUPLICATE KEY UPDATE data=values(data)';
		$query2 = $this->db->query($insert_sql, array( $today, $data_json ));
	}

	/**
	 * Remove old posts from the database
	 */
	public function database_cleanup()
	{
		$delete_older_than = date('Y-m-d', time()-(60*60*24*40));

		$tables = array(
				'follows',
				'posts',
				'reposts',
				'stars'
			);

		foreach ($tables as $table) {
			$sql = 'DELETE FROM '.$table.' WHERE created_at < "'.$delete_older_than.' 00:00:00"';

			// Run the query
			$query = $this->db->query($sql);
		}
	}

}
