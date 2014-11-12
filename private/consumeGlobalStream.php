<?php
/**
 * ---------------------------------------------
 * CONSUME GLOBAL STREAM
 * ---------------------------------------------
 *
 * We need to consume the global stream to get the freshest
 * data and reduce load time.
 *
 * We can also get better stats!
 */

/**
 * ENVIRONMENT
 *
 * Set the environment to allow for debugging and
 * to choice the appropriate database connection.
 *
 * Correct variables are:
 *   testing
 *   production
 */
define('ENVIRONMENT', 'production');

/**
 * SET ERROR REPORTING
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(E_ALL);
	break;

	case 'testing':
		error_reporting(E_ALL);
	break;

	case 'production':
		error_reporting(E_ALL);
	break;

	default:
		exit('The application environment is not set correctly.');
}

require_once('AppDotNet.php');
require_once('Text/LanguageDetect.php');

class ConsumeGlobalStream {

	/**
	 * CONFIGURATION
	 *
	 * Setup and configuration for the database.
	 */
	private $config = array(
		'testing' => array(
			'database' => array(
				'hostname' => 'localhost',
				'username' => 'root',
				'password' => '',
				'database' => 'adn_client'
				)

			),

		'production' => array(	
			'database' => array(
				'hostname' => 'localhost',
				'username' => 'analytics_app_ne',
				'password' => 'yErVxE2BWSu9GZqe',
				'database' => 'analytics_app_ne'
				)

			)

		);

	/**
	 * SQL STATEMENTS
	 */
	private $add_post_sql = 
	 'INSERT INTO posts
	 (
		 id, 
		 user_id, 
		 created_at, 
		 source_name, 
		 source_link, 
		 source_client_id, 
		 reply_to, 
		 canonical_url, 
		 thread_id, 
		 num_replies, 
		 num_stars, 
		 num_reposts,
		 detected_language
	 ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
	private $remove_post_sql = 'DELETE FROM posts WHERE id = ?';
	private $add_star_sql = 'UPDATE posts SET num_stars = num_stars+1 WHERE id = ?';
	private $remove_star_sql = 'UPDATE posts SET num_stars = num_stars-1 WHERE id = ?';
	private $add_repost_sql = 'UPDATE posts SET num_reposts = num_reposts+1 WHERE id = ?';
	private $remove_repost_sql = 'UPDATE posts SET num_reposts = num_reposts-1 WHERE id = ?';
	private $add_reply_sql = 'UPDATE posts SET num_replies = num_replies+1 WHERE id = ?';
	private $remove_reply_sql = 'UPDATE posts SET num_replies = num_replies-1 WHERE id = ?';
	private $add_follow_sql = 'INSERT INTO follows (id, user_id, follows_user_id) VALUES (?,?,?)';
	private $add_unfollow_sql = 'INSERT INTO follows (id, user_id, follows_user_id, is_deleted) VALUES (?,?,?,1)';
	private $add_authorize_sql = 'INSERT INTO tokens (id, user_id, app_link, app_name, app_id) VALUES (?,?,?,?,?)';
	private $add_deauthorize_sql = 'UPDATE tokens SET is_deleted = 1 WHERE id = ?';

	private $add_star_sql_2 = 'INSERT INTO stars (user_id, post_id) VALUES (?, ?)';
	private $remove_star_sql_2 = 'DELETE FROM stars WHERE user_id = ? AND post_id = ?';
	private $add_repost_sql_2 = 'INSERT INTO reposts (id, user_id, post_id) VALUES (?, ?, ?)';
	private $remove_repost_sql_2 = 'DELETE FROM reposts WHERE id = ?';

	// MySQL Statements
	private $add_post_statement;
	private $remove_post_statement;
	private $add_star_statement;
	private $remove_star_statement;
	private $add_repost_statement;
	private $remove_repost_statement;
	private $add_reply_statement;
	private $remove_reply_statement;
	private $add_follow_statement;
	private $add_unfollow_statement;
	private $add_authorize_statement;
	private $add_deauthorize_statement;
		private $add_star_statement_2;
		private $remove_star_statement_2;
		private $add_repost_statement_2;
		private $remove_repost_statement_2;

	// ADN Library
	private $appdotnet;

	// Langauge detection library
	private $language_detective;

	// MySQLi Connection
	private $connection;

	// ADN App Token
	private $appToken;

	// ADN Stream
	private $stream = array (
			'id' => '2595',
			'endpoint' => 'https://stream-channel.app.net/channel/1/t3qQQMZTGAmPNVN1jqRdUuhdtSTmEhlrDiF8Nqbwp3bT-HsspIb9ThaFiu1I74FIBbbbGAaBp6aj09FapOCsnD6jo3VoxkpdNp1ElZF6lVuO2KJ4zICGk3Nf431Fa38C'
		);

	function __construct()
	{
		/**
		 * LOAD ADN LIBRARY
		 *
		 * Consuming the global stream requires AppDotNet.php
		 * This should be stored in the same directory as this file.
		 */
		$this->appdotnet = new AppDotNet(
			// ADN keys 
			'', 
			'' 
			);

		/**
		 * Load the Language detection library
		 */
		$this->language_detective = new Text_LanguageDetect();
		$this->language_detective->setNameMode(2);

		/**
		 * APPDOTNET ACCESS TOKEN
		 */
		$this->appToken = $this->appdotnet->getAppAccessToken();

		// Register the stream function
		$this->appdotnet->registerStreamFunction(array($this, 'eventHandler'));

		// Open the stream for reading
		$this->appdotnet->openStream($this->stream['endpoint']);

		// Open connection to database
		if ( ENVIRONMENT != 'development') $this->db_connect();
	}

	function __destruct()
	{
		// Close the database connection
		if ( ENVIRONMENT != 'development') $this->db_dissconnect();

		// Close the stream
		$this->closeStream();
	}

	private function db_connect() {
		// Open a connection to the database
		$this->connection = new MySQLi(
			$this->config[ENVIRONMENT]['database']['hostname'],
			$this->config[ENVIRONMENT]['database']['username'],
			$this->config[ENVIRONMENT]['database']['password'],
			$this->config[ENVIRONMENT]['database']['database']			
			);

		// Prepare the MySQL Statements
		if (!($this->add_post_statement = $this->connection->prepare($this->add_post_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->remove_post_statement = $this->connection->prepare($this->remove_post_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->add_star_statement = $this->connection->prepare($this->add_star_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->remove_star_statement = $this->connection->prepare($this->remove_star_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->add_repost_statement = $this->connection->prepare($this->add_repost_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->remove_repost_statement = $this->connection->prepare($this->remove_repost_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->add_reply_statement = $this->connection->prepare($this->add_reply_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->remove_reply_statement = $this->connection->prepare($this->remove_reply_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->add_follow_statement = $this->connection->prepare($this->add_follow_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->add_unfollow_statement = $this->connection->prepare($this->add_unfollow_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->add_authorize_statement = $this->connection->prepare($this->add_authorize_sql))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}

		// Prepare the version 2 statements
		if (!($this->add_star_statement_2 = $this->connection->prepare($this->add_star_sql_2))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->remove_star_statement_2 = $this->connection->prepare($this->remove_star_sql_2))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->add_repost_statement_2 = $this->connection->prepare($this->add_repost_sql_2))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
		if (!($this->remove_repost_statement_2 = $this->connection->prepare($this->remove_repost_sql_2))) {
		   echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
		}
	}

	private function db_disconnect()
	{
		// Close the database connection
		$this->connection->close();	
	}

	private function db_reconnect()
	{
		$this->db_disconnect();
        $this->db_connect();
	}

	public function run()
	{
		/**
		 * PROCESS THE STREAM
		 *
		 * Begin processing the stream and doing the appropriate actions
		 * on the events.
		 */
		switch (ENVIRONMENT)
		{
			case 'development':
				$this->appdotnet->processStream((30*1000000));
			break;

			case 'testing':
				// We should not process the stream forever in a testing environment
				$this->appdotnet->processStream((500*1000000));
			break;

			case 'production':
				// We shall begin processing the stream forever!
				$this->appdotnet->processStreamForever();
			break;

			default:
				exit('The application environment is not set correctly.');
		}
	}

	public function eventHandler($event)
	{
		if (isset($event['meta']['is_deleted']))
		{
			// We need to delete some stuff
			switch ($event['meta']['type']) {
				case 'post':
					// Check for banned users
					if (in_array($event['data']['user']['id'], $this->banned_user_ids)) return true;

					// Check if is a repost
					if(isset($event['data']['repost_of']))
					{
						// This is a repost
						$this->remove_repost($event);
					}
					else
					{
						// Check if it was a reply
						if(isset($event['data']['reply_to']))
						{
							$this->remove_reply($event);
						}
						// This is a post
						$this->remove_post($event);
					}
					break;

				case 'star':
					// Check for banned users
					if (in_array($event['data']['user']['id'], $this->banned_user_ids)) return true;

					// Remove the star
					$this->remove_star($event);
					break;

				case 'user_follow':
					// Check for banned users
					if (in_array($event['data']['user']['id'], $this->banned_user_ids)) return true;

					// Add an unfollow event
					$this->add_unfollow($event);
				break;
				
				default:
					# code...
					break;
			}
		}
		else
		{
			// We need to add some stuff
			switch ($event['meta']['type']) {
				case 'post':
					// Check for banned users
					if (in_array($event['data']['user']['id'], $this->banned_user_ids)) return true;

					// Check if is a repost
					if(isset($event['data']['repost_of']))
					{
						// This is a repost
						$this->add_repost($event);
					}
					else
					{
						// Check if it was a reply
						if(isset($event['data']['reply_to']))
						{
							$this->add_reply($event);
						}
						// This is a post
						$this->add_post($event);
					}
					break;

				case 'star':
					// Check for banned users
					if (in_array($event['data']['user']['id'], $this->banned_user_ids)) return true;

					// Add the star
					$this->add_star($event);
					break;

				case 'user_follow':
					// Check for banned users
					if (in_array($event['data']['user']['id'], $this->banned_user_ids)) return true;

					// Add a follow event
					$this->add_follow($event);
				break;

				case 'token':
					// Check for banned users
					if (in_array($event['data']['user']['id'], $this->banned_user_ids)) return true;
					
					// Update the authorization as deleted
					$this->add_authorize($event);
				break;
				
				default:
					# code...
					break;
			}
		}
	}

	private function add_post($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Add Post</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
				print $this->language_detective->detectSimple($event['data']['text']);
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->add_post_statement->bind_param(
					'iissssisiiiis',
					$event['data']['id'], 
					$event['data']['user']['id'], 
					date('Y-m-d H:i:s', strtotime($event['data']['created_at'])),
					$event['data']['source']['name'],
					$event['data']['source']['link'],
					$event['data']['source']['client_id'],
					$event['data']['reply_to'],
					$event['data']['canonical_url'],
					$event['data']['thread_id'],
					$event['data']['num_replies'],
					$event['data']['num_stars'],
					$event['data']['num_reposts'],
					$this->language_detective->detectSimple($event['data']['text']));

				// Execute
				if(!$this->add_post_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
			break;
		}
	}

	private function remove_post($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Remove Post</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

		        /**
		         * Remove post from posts table
		         */
				$this->remove_post_statement->bind_param('i', $event['meta']['id']);
				// Execute
				if(!$this->remove_post_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}

				/**
		         * Remove post from reposts table
		         */
				$this->remove_repost_statement_2->bind_param('i', $event['meta']['id']);
				// Execute
				if(!$this->remove_repost_statement_2->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
			break;
		}
	}

	private function add_star($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Add Star</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->add_star_statement->bind_param('i', $event['data']['post']['id']);

				// Execute
				if(!$this->add_star_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}

				// Version 2
				$this->add_star_statement_2->bind_param('ii', $event['data']['user']['id'], $event['data']['post']['id']);

				if(!$this->add_star_statement_2->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
				// End version 2		
			break;
		}
	}

	private function remove_star($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Remove Star</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->remove_star_statement->bind_param('i', $event['data']['post']['id']);

				// Execute
				if(!$this->remove_star_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}

				// Version 2
				$this->remove_star_statement_2->bind_param('ii', $event['data']['user']['id'], $event['data']['post']['id']);

				if(!$this->remove_star_statement_2->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
				// End version 2
			break;
		}
	}

	private function add_repost($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Add Repost</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->add_repost_statement->bind_param('i', $event['data']['repost_of']['id']);

				// Execute
				if(!$this->add_repost_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}

				// Version 2
				$this->add_repost_statement_2->bind_param('iii', $event['data']['id'], $event['data']['user']['id'], $event['data']['repost_of']['id']);

				if(!$this->add_repost_statement_2->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
				// End version 2		
			break;
		}
	}

	private function remove_repost($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Remove Repost</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->remove_repost_statement->bind_param('i', $event['data']['repost_of']['id']);

				// Execute
				if(!$this->remove_post_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}		
			break;
		}
	}

	private function add_reply($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Add Reply</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->add_reply_statement->bind_param('i', $event['data']['reply_to']);

				// Execute
				if(!$this->add_reply_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
			break;
		}
	}

	private function remove_reply($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Remove Reply</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->remove_reply_statement->bind_param('i', $event['data']['reply_to']);

				// Execute
				if(!$this->remove_reply_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
			break;
		}
	}

	private function add_follow($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Add Follow</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->add_follow_statement->bind_param('iii', 
					$event['meta']['id'], 
					$event['data']['user']['id'],
					$event['data']['follows_user']['id']);

				// Execute
				if(!$this->add_follow_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}		
			break;
		}
	}

	private function add_unfollow($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Add Unfollow</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->add_unfollow_statement->bind_param('iii', 
					$event['meta']['id'], 
					$event['data']['user']['id'],
					$event['data']['follows_user']['id']);

				// Execute
				if(!$this->add_unfollow_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}	
			break;
		}
	}

	private function add_authorize($event)
	{
		switch (ENVIRONMENT) {
			case 'development':
				print '<hr><h2>Add Authorize</h2>';
				print '<pre>';
				print_r($event);
				print '</pre>';
			break;
			
			default:
				//auto reconnect if MySQL server has gone away
		        if (!$this->connection->ping()) $this->reconnect();

				$this->add_authorize_statement->bind_param('iisss', 
					$event['meta']['id'], 
					$event['data']['user']['id'],
					$event['data']['app']['link'],
					$event['data']['app']['name'],
					$event['data']['app']['client_id']);

				// Execute
				if(!$this->add_authorize_statement->execute()) {
					echo "Execute failed: (" . $this->connection->errno . ") " . $this->connection->error;
				}
			break;
		}
	}

}

class ConsumeGlobalStreamException extends Exception {

	private $_adminEmail = 'jamesduffy0@gmail.com';

}


/**
 * RUN THE PROCESS
 *
 * This will begin the processing of the stream.
 */
$ConsumeGlobalStream = new ConsumeGlobalStream();
$ConsumeGlobalStream->run();

/**
 * PRINT DEBUGGING INFORMATION
 *
 * If we are running in the testing environment print out the memory usage
 * of the script when it completes.
 *
 * TODO:
 *  - Add CPU usage as output
 *  - Send email with this information when in a production environment
 */
if (ENVIRONMENT == 'testing')
	echo memory_get_usage() . "\n";

?>
