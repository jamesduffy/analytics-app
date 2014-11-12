
<div id="profile-header" style="background-image: url(<?=$cover_image['url']?>);">

	<div class="row">
		<div class="col-md-12">
			<img id="profile-image" class="img-thumbnail" src="<?=$avatar_image['url']?>" height="175" width="175">
		</div>
	</div>

	<div class="row">

		<div class="info-box col-md-12">
			<h2>
				<?=$username?><br>
				<small class="muted"><?=$name?></small>
			</h2>
		</div>

	</div>

	<div class="row">

		<div class="profile-actions col-md-12">

			<?php if( $this->session->userdata('user_id') !== $id ): ?>
				<?php if (! $you_follow ) $you_follow = 0; ?>
				<a class="follow-btn btn btn-default" data-following="<?=$you_follow?>" data-user-id="<?=$id?>"></a>
			<?php else: ?>
				<!-- Future location of edit profile button -->
			<?php endif; ?>
		</div>

	</div>

</div>
