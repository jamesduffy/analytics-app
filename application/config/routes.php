<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['profile/:any/popular'] = 'profile/popular/$1';
$route['profile/:any/followers'] = 'profile/followers/$1';
$route['profile/:any/recent'] = 'profile/recent_posts/$1';
$route['profile/:any'] = 'profile/stats/$1';

$route['post/:any/reposts'] = 'post/reposts/$1';
$route['post/:any/stars'] = 'post/stars/$1';
$route['post/:any'] = 'post/index/$1';

$route['leaderboard/:any/top-clients'] = 'leaderboard/top_clients';
$route['leaderboard/:any/top-followed'] = 'leaderboard/top_followed';
$route['leaderboard/:any/posts'] = 'leaderboard/posts';
$route['leaderboard/:any'] = 'leaderboard/index';

// Not a real blog, but I should eventually make one
// The next version of CI has better support for "static pages"
$route['blog/year-in-review-2013'] = 'blog/year_in_review_2013';

$route['default_controller'] = "welcome";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */