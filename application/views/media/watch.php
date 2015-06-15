<div class="row">
	<div class="col-md-12">
		<video id="video" class="video-js vjs-default-skin vjs-fullscreen video-js-fullscreen vjs-big-play-centered" controls preload="none" data-setup='{"techOrder": ["html5", "flash", "vlc"]}' width="auto" height="auto">
		    <source src="/watch/stream/<?= $this->streamstr; ?>" type='video/mp4' codecs="<?= $this->codec; ?>" /> <!-- type='<?= $this->type; ?>' codecs="<?= $this->codec; ?>" /> -->

			<?php foreach ($this->subs as $sub): ?>
				<track src="/watch/sub/<?= $this->file; ?>/<?= $sub; ?>" srclang="<?= $sub; ?>" label="<?= $sub; ?>"/>
			<?php endforeach; ?>

		    <p class="vjs-no-js"><?= $this->lang['nojs']; ?></p>
		  </video>
	</div>
</div>
