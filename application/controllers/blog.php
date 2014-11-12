<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {

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
			try {
				$this->appdotnet->setAccessToken( $this->appdotnet->getAppAccessToken() );
			} catch (Exception $e) {
				show_error('Unable to authenticate the server with App.net');
			}
		}
	}

	public function year_in_review_2013()
	{
		$top_users = array(
				'39167','4669','6184','4977','1','10346','440','24114','11917','17330','28144','18',
				'48131','1472','2921','1178','1675','2','136','851','3954','91',
				'22814','7848','466','34810','8324','24942','27452','30601','5941','3060','9367',
				'11904','1268','7951','1445','117836','16479','2357','21344','249','1110','30506',
				'30027','3982','19071','24262','3','143661','24832','30185','1638','99492','20810',
				'8','2227','6482','17220','16038','27607','687','31373','21332','749','19556','120',
				'25104','7833','22469','4663','3152','21489','28830','14614','37710','44662','102920',
				'28910','48581','450','32353','5463','256','4002','23758','3945','3393','4184','46178',
				'5700','14','2111','27098','65675','2959','4041','5952','144064','52043'
			);
		$data['top_users_unsorted'] = $this->appdotnet->getUsers($top_users);

		// Sort Top Users
		$data['top_users_sorted'] = array();
		foreach ($top_users as $sorted_user) {
			foreach($data['top_users_unsorted'] as $key => $val)
			{
				if ($val['id'] === $sorted_user) {
					$data['top_users_sorted'][] = $data['top_users_unsorted'][$key];
				}
			}
		}

		$top_posts_ids = array(
				'11392691','14272584','18143822','9022868','9395170','8797787','14769249','8756309',
				'7932763','8787122','8787246','8755746','10943476','7150794','8365828','6971323','9492674',
				'9115033','8782396','15493490','8188300','8799087','8751579','8757775','13601204','8751213',
				'7472872','10236075','8577340','7622262','13006860','8750241','6917579','15549153','8755498',
				'9288922','8955716','8787204','9844757','8716429','6723780','14855123','8761764','8380268',
				'7223965','7908090','9375524','6776634','8998447','9069352'
			);

		// Create top_posts string
		$top_posts_string = '11392691,14272584,18143822,9022868,9395170,8797787,14769249,8756309,7932763,8787122,8787246,8755746,10943476,7150794,8365828,6971323,9492674,9115033,8782396,15493490,8188300,8799087,8751579,8757775,13601204,8751213,7472872,10236075,8577340,7622262,13006860,8750241,6917579,15549153,8755498,9288922,8955716,8787204,9844757,8716429,6723780,14855123,8761764,8380268,7223965,7908090,9375524,6776634,8998447,9069352';

		// Get the top posts from ADN
		try {
			$data['top_posts_unsorted'] = $this->appdotnet->getPosts( array('ids' => $top_posts_string, 'include_annotations' => 1) );
		} catch (Exception $e) {
			show_error($e->getMessage());
		}

		// Sort the top posts returned by adn
		$data['top_posts'] = array(); // result array
		foreach( $top_posts_ids as $top_post )
		{
			foreach ($data['top_posts_unsorted'] as $key => $val) {
				if ($val['id'] === $top_post) {
					$data['top_posts'][] = $data['top_posts_unsorted'][$key]; 
				}
			}
		}

		$data['top_clients'] = array (
					array(
							'source_name' => 'IFTTT',
							'source_link' => 'http://ifttt.com',
							'total_posts' => '2159599'
						),
					array(
							'source_name' => 'PourOver',
							'source_link' => 'https://adn-pourover.appspot.com/',
							'total_posts' => '2033460'
						),
					array(
							'source_name' => 'twitterfeed',
							'source_link' => 'http://twitterfeed.com',
							'total_posts' => '1191576'
						),
					array(
							'source_name' => 'dlvrit',
							'source_link' => 'http://dlvr.it',
							'total_posts' => '919549'
						),
					array(
							'source_name' => 'Buffer',
							'source_link' => 'http://bufferapp.com',
							'total_posts' => '559267'
						),
					array(
							'source_name' => 'Felix',
							'source_link' => 'http://tigerbears.com/felix',
							'total_posts' => '548648'
						),
					array(
							'source_name' => 'Alpha',
							'source_link' => 'https://alpha.app.net',
							'total_posts' => '365218'
						),
					array(
							'source_name' => 'Netbot for iOS',
							'source_link' => 'http://tapbots.com/software/netbot',
							'total_posts' => '315860'
						),
					array(
							'source_name' => 'Riposte',
							'source_link' => 'http://riposteapp.net',
							'total_posts' => '311745'
						),
					array(
							'source_name' => 'Kiwi',
							'source_link' => 'http://kiwi-app.net/',
							'total_posts' => '287576'
						),
					array(
							'source_name' => 'Wedge',
							'source_link' => 'http://wedge.natestedman.com',
							'total_posts' => '208462'
						),
					array(
							'source_name' => 'Robin',
							'source_link' => 'http://robinapp.net',
							'total_posts' => '136483'
						),
					array(
							'source_name' => 'Scoop.it',
							'source_link' => 'http://www.scoop.it',
							'total_posts' => '86888'
						),
					array(
							'source_name' => 'Dash',
							'source_link' => 'http://themodernink.com/portfolio/dash-for-app-net/',
							'total_posts' => '62257'
						),
					array(
							'source_name' => 'hAppy',
							'source_link' => 'http://dasdom.de/Dominik_Hauser_Development/hAppy.html',
							'total_posts' => '59822'
						),
					array(
							'source_name' => 'Cauldron',
							'source_link' => 'https://cauldron-app.herokuapp.com',
							'total_posts' => '59436'
						),
					array(
							'source_name' => 'Texapp',
							'source_link' => 'http://www.floodgap.com/software/texapp/',
							'total_posts' => '34222'
						),
					array(
							'source_name' => 'Zapier',
							'source_link' => 'https://zapier.com/',
							'total_posts' => '29124'
						),
					array(
							'source_name' => 'Posts to ADN',
							'source_link' => 'http://wordpress.org/extend/plugins/posts-to-adn',
							'total_posts' => '20105'
						),
					array(
							'source_name' => 'Asterisk',
							'source_link' => 'http://shootingstar.fm/asterisk/',
							'total_posts' => '19332'
						),
					array(
							'source_name' => 'Amz BF',
							'source_link' => 'http://localhost',
							'total_posts' => '17850'
						),
					array(
							'source_name' => 'adn2',
							'source_link' => 'http://localhost:8080',
							'total_posts' => '17793'
						),
					array(
							'source_name' => 'adn3',
							'source_link' => 'http://quora.com',
							'total_posts' => '17761'
						),
					array(
							'source_name' => 'Post By Mail',
							'source_link' => 'http://tomllint.com',
							'total_posts' => '16496'
						),
					array(
							'source_name' => 'NoodleApp',
							'source_link' => 'http://app.noodletalk.org',
							'total_posts' => '15756'
						),
					array(
							'source_name' => 'Black Friday',
							'source_link' => 'http://www.facebook.com',
							'total_posts' => '15283'
						),
					array(
							'source_name' => 'LinksAlpha',
							'source_link' => 'http://www.linksalpha.com',
							'total_posts' => '14797'
						),
					array(
							'source_name' => 'Dev Lite',
							'source_link' => 'http://www.jonathonduerig.com/dev-lite',
							'total_posts' => '14740'
						),
					array(
							'source_name' => 'Jewelry Info',
							'source_link' => 'http://jewelryonline.info',
							'total_posts' => '14449'
						),
					array(
							'source_name' => 'Drafts',
							'source_link' => 'http://agiletortoise.com/drafts',
							'total_posts' => '13632'
						),
					array(
							'source_name' => 'MAGI',
							'source_link' => 'http://magi.unnerv.net/',
							'total_posts' => '13599'
						),
					array(
							'source_name' => 'NewsCenterd SNAP App',
							'source_link' => 'http://newscenterd.com',
							'total_posts' => '12183'
						),
					array(
							'source_name' => 'top10gamesonline.com',
							'source_link' => 'http://top10gamesonline.com',
							'total_posts' => '11358'
						),
					array(
							'source_name' => 'URI.LV Publisher',
							'source_link' => 'http://uri.lv',
							'total_posts' => '10742'
						),
					array(
							'source_name' => 'ï½±ï½µï½²ï¾„ï¾˜(bluebird)',
							'source_link' => 'https://play.google.com/store/apps/details?id=com.matsumo.bb',
							'total_posts' => '8893'
						),
					array(
							'source_name' => 'SocialOomph.com',
							'source_link' => 'https://www.socialoomph.com',
							'total_posts' => '8850'
						),
					array(
							'source_name' => 'Drift',
							'source_link' => 'http://ineedtojet.com/drift/',
							'total_posts' => '8177'
						),
					array(
							'source_name' => 'DotDot',
							'source_link' => 'http://www.windowsphone.com/en-us/store/app/dotdot/dd4e94db-0e2a',
							'total_posts' => '7954'
						),
					array(
							'source_name' => 'lilikoi',
							'source_link' => 'http://en.wikipedia.org/wiki/Passiflora_edulis',
							'total_posts' => '7832'
						),
					array(
							'source_name' => 'Chimp',
							'source_link' => 'http://chimp.li/',
							'total_posts' => '7748'
						),
					array(
							'source_name' => 'TreeView',
							'source_link' => 'http://treeview.us/',
							'total_posts' => '7600'
						),
					array(
							'source_name' => '#PAN',
							'source_link' => 'http://hashpan.com',
							'total_posts' => '7550'
						),
					array(
							'source_name' => 'Zephyr',
							'source_link' => 'http://getzephyrapp.com',
							'total_posts' => '7332'
						),
					array(
							'source_name' => 'uploaded',
							'source_link' => 'http://www.uploaded-free-download.net',
							'total_posts' => '6865'
						),
					array(
							'source_name' => 'Dekoration',
							'source_link' => 'http://wohnideenn.de',
							'total_posts' => '6615'
						),
					array(
							'source_name' => 'Appnetizens',
							'source_link' => 'http://appnetizens.com',
							'total_posts' => '6598'
						),
					array(
							'source_name' => 'Techmeme',
							'source_link' => 'http://www.instapaper.com/',
							'total_posts' => '5577'
						),
					array(
							'source_name' => 'RlsArchive',
							'source_link' => 'http://www.rlsarchive.com',
							'total_posts' => '5564'
						),
					array(
							'source_name' => 'greatdiscountmarket',
							'source_link' => 'http://www.greatdiscountmarket.com',
							'total_posts' => '5311'
						),
					
				);
		
		$this->load->view('layouts/header', array('page_title' => 'Year In Review 2013'));
		$this->load->view('blog/year-in-review-2013', $data);
		$this->load->view('layouts/footer');
	}

}