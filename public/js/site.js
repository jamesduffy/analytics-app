$(document).ready(function() {
	var base_url = 'http://analytics-app.net/';

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
			alert('There was an error making the follow/unfollow request.');
		});
	});

});