<?php
	if(isset($user_id))
	{
		$user = $this->people->getUser($user_id);
	} else {
		$user = $user;
	}
?>
<li class="media box">
	<a class="pull-left" href="<?=site_url('profile/'.$user['username'])?>">
		<img class="media-object" src="<?=$user['avatar_image']['url']?>" height="50" width="50">
	</a>

	<div class="media-body">
		<div class="media-heading">
			<h4 class="pull-left">
				<?=anchor('profile/'.$user['username'], $user['username'])?>
				<small class="muted"><?=$user['name']?></small>
			</h4>

			<small class="pull-right permalink">
				<?=anchor('post/'.$id, ago($created_at), array('class' => 'muted', 'title' => $created_at))?>
			</small>

			<div class="clearfix"></div>
		</div>

		<?=$html?>

	</div>
	
</li>
