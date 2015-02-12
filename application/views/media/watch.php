<div class="row">
	<div class="videoWrapper">
		<video id="video" class="video-js vjs-default-skin" controls preload="none" data-setup="{}">
		    <source src="/watch/stream/?f=<?= $this->file; ?>" type='video/webm' codecs="<?= $this->codec; ?>" />

			<? foreach ($this->subs as $sub): ?>
				<track src="<?= $sub['file'] ?>" srclang="<?= $sub['lang']; ?>" label="<?= $sub['label']; ?>"/>
			<? endforeach; ?>

		    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video..</p>
		  </video>
	</div>
</div>