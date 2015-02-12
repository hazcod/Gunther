
<div class="row">
<div class="videoWrapper">
<video id="video" class="video-js vjs-default-skin" controls preload="none" data-setup="{}">
    <source src="/watch/stream/?f=<?= $this->file; ?>" type='video/webm' codecs="<?= $this->codec; ?>" />
	<? foreach ($this->subs as $sub): ?>
	<track src="$sub" srclang="" label=""/>
	<? endforeach; ?>
    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
  </video>
</div>
</div>