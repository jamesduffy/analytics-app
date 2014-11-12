<?php
	if(isset($user_id))
	{
		$user = $this->people->getUser($user_id);
	} else {
		$user = $user;
	}
?>
<li class="media" data-post-id="<?=$id?>">
	<a class="pull-left no-border" href="<?=site_url('profile/'.$user['username'])?>">
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

		<?php
		// Replace @mentions, #hashtags and links with proper links
		$entity_list = array();

		foreach ($entities['mentions'] as $mention)
		{
			$entity_list[$mention['pos']] = array(
					'type' => 'mention',
					'name' => $mention['name'],
					'pos'  => $mention['pos'],
					'len'  => $mention['len']
				);
		}

		foreach ($entities['hashtags'] as $hashtag)
		{
			$entity_list[$hashtag['pos']] = array(
					'type' => 'hashtag',
					'name' => $hashtag['name'],
					'pos'  => $hashtag['pos'],
					'len'  => $hashtag['len']
				);
		}

		foreach ($entities['links'] as $link)
		{
			$entity_list[$link['pos']] = array(
					'type' => 'link',
					'url'  => $link['url'],
					'text' => $link['text'],
					'pos'  => $link['pos'],
					'len'  => $link['len']
				);
		}

		// Sort Array in reverse by the key
		krsort($entity_list);

		// Insert the entity into the post text
		foreach ($entity_list as $entity) {
			switch ($entity['type']) {
				case 'mention':
					$text = utf8_substr_replace(
						$text, 
						anchor('profile/'.$entity['name'], '@'.$entity['name']),
						$entity['pos'],
						$entity['len']);
					break;

				case 'hashtag':
					$text = utf8_substr_replace(
						$text, 
						anchor('discover/hashtag/'.$entity['name'], '#'.$entity['name']),
						$entity['pos'],
						$entity['len']);
					break;

				case 'link':
					$text = utf8_substr_replace(
						$text,
						anchor($entity['url'], $entity['text'], array('target' => '_blank')),
						$entity['pos'],
						$entity['len']);
					break;

			}
		}
		?>

		<?=nl2br($text)?>

		<?php foreach ($annotations as $annotation): ?>
			
			<?php
			switch ($annotation['type']) {
				case 'net.app.core.oembed':
					switch ($annotation['value']['type']) {
						case 'photo':
							if(isset($annotation['value']['embeddable_url'])) {
								$url = $annotation['value']['embeddable_url'];
							} else {
								$url = $annotation['value']['url'];
							}
							print anchor($url, '<img src="'.$annotation['value']['url'].'" alt="">', array('class' => 'thumbnail' ));
							break;
						case 'video':
							print $annotation['value']['html'];
							break;
						case 'html5video':
							break;
						case 'rich':
							print $annotation['value']['html'];
					}

					break;
			}
			?>
			
		<?php endforeach; ?>

		<div class="media-counts">
			<?php if(isset($source)): ?>
				<p class="pull-left">
					<?=anchor($source['link'], 'via '. $source['name'], array('target' => '_blank' ))?>
				</p>
			<?php endif; ?>

			<p class="pull-right">
				<?=anchor('post/'.$id, '<span class="glyphicon glyphicon-share-alt"></span> '.$num_replies)?>
				<?=anchor('post/'.$id.'/reposts', '<span class="glyphicon glyphicon-retweet"></span> '.$num_reposts)?>
				<?=anchor('post/'.$id.'/stars', '<span class="glyphicon glyphicon-star-empty"></span> '.$num_stars)?>
			</p>
		</div>

	</div>
	
</li>
