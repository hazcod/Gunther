<div class="row">
	<div class="col-md-12">
	<a href="vlc://<?= $_SERVER['SERVER_ADDR']; ?>/watch/sub/<?= $this->file; ?>" />
	<!-- if not using autoplay, add preload='auto' -->
		<video id="video" class="video-js vjs-default-skin vjs-fullscreen video-js-fullscreen vjs-big-play-centered" controls autoplay data-setup='{"techOrder": ["vlc", "html5", "flash"]}' width="auto" height="auto">
		    <source src="/watch/stream/<?= $this->streamstr; ?>" type='video/mp4' codecs="<?= $this->codec; ?>" />  <!-- type='<?= $this->type; ?>' /> -->
			<?php foreach ($this->subs as $sub): ?>
				<track src="/watch/sub/<?= $this->file; ?>/<?= $sub; ?>" srclang="<?= $sub; ?>" label="<?= $sub; ?>"/>
			<?php endforeach; ?>
		    <p class="vjs-no-js"><?= $this->lang['nojs']; ?></p>
		  </video>
	</div>
</div>
