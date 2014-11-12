$(document).ready(function() {
	var base_url = '/analytics-app.net/public/index.php';

	// Make the post form work the ajax way
	$('#compose-post #submit-form').click(function(){
		alert('Form submitted!');
	});

	// Actions on posts
		// Favorite
		$('.favorite-link').click(function(){
			var button = $(this);
			var post_id = button.data('post-id');
			var you_starred = button.data('you-starred');

			// Update the button
			if (you_starred) {
				// Delete the star on the post
				button.removeClass('')
			} else {
				// Add the star on the post

			}

			$.post(base_url + 'apiproxy/star/' + post_id, { 'you_starred':  }).fail(function() {

			});

			alert('Trying to favorite post '+you_starred);
		});

	// Setup follow/unfollow button depending on data attribute
	$('.follow-btn').each(function(){
		var button = $(this);
		var followingState = button.data('following');

		if (followingState) {
			// Following
			button.addClass('btn-primary').text('Following');
		} else {
			// Not Following
			button.text('Follow');
		}
	});

	// Make the follow/unfollow buttons work
	$('.follow-btn').click(function() {
		var button = $(this);
		var followingState = button.data('following');

		// Update the button
		if (followingState) {
			// Following to Unfollow
			button.removeClass('btn-primary').text('Follow').data('following', 0);
		} else {
			// Unfollow to Follow
			button.addClass('btn-primary').text('Following').data('following', 1);
		}
		
		// Make the request to the server
		var userid = button.data('user-id');

		$.post(base_url + 'apiproxy/follow/' + userid, { 'followingState': followingState }).fail(function() {
			// Follow or Unfollow did not work... revert to previous state
			if (followingState) {
				// Following to Unfollow
				button.removeClass('btn-primary').text('Follow').data('following', 0);
			} else {
				// Unfollow to Follow
				button.addClass('btn-primary').text('Following').data('following', 1);
			}

			// inform user action failed.
			alert('There was an error making the follow or unfollow request please refresh the page.');
		});
	});

});