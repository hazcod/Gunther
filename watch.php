<div class="row">
	<div class="col-md-12">
		<video id="video" class="video-js vjs-default-skin vjs-fullscreen video-js-fullscreen vjs-big-play-centered" controls preload="none" data-setup="{}" width="auto" height="auto">
		    <source src="/watch/stream/<?= $this->streamstr; ?>" type="video/webm"/><!-- type='<?= $this->type; ?>' codecs="<?= $this->codec; ?>" /> -->

			<? foreach ($this->subs as $sub): ?>
				<track src="/watch/sub/<?= $this->file; ?>/<?= $sub['lang'] ?>" srclang="<?= $sub['lang']; ?>" label="<?= $sub['label']; ?>"/>
			<? endforeach; ?>

		    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video..</p>
		  </video>
	</div>
</div>