<div class="col-md-6 col-md-offset-3">
	<br>
	<br>
	<div class="row">
		<div class="col-md-3">
			<img src="<?= $this->info->info->images->poster[0]; ?>" alt="<?= $this->info->info->original_title; ?> poster" class="imgscale" />
			<hr>
    		<div class="pull-right">
				<a class="btn btn-success btn-sm" href="/watch/index/<?= $this->info->info->imdb; ?>"><i class="fa fa-video-camera"></i></a>
				<a class="btn btn-primary btn-sm" target="_blank" href="/watch/getmovie/<?= $this->id; ?>"><i class="fa fa-download"></i></a>
				<a class="btn btn-info btn-sm" target="_blank" href="http://www.imdb.com/title/<?= $this->id; ?>"><i class="fa fa-info-circle"></i></a>
				<a class="btn btn-default btn-sm" target="_blank" href="https://www.youtube.com/results?search_query=<?= urlencode($this->info->info->original_title .' trailer ' . $_SESSION['lang']); ?>"><i class="fa fa-caret-square-o-right"></i></a>
			</div>
		</div>
		<div class="col-md-9">
			<div class="well">
				<div class="pull-right">
					<?= join(',', $this->info->info->genres); ?>
				</div>
				<h3>
					<?= $this->info->info->original_title; ?> <small><?= $this->info->info->year; ?></small>
				</h3>
				<div class="pull-right"><?= $this->info->info->rating->imdb[0]; ?>/10</div>
				<hr>
				<p>
				<?= $this->info->info->plot; ?>
				</p>
			</div>
			<div class="well">
				<h4><?= $this->lang['actors']; ?></h4>
				<hr>
				<div class="slider-movies">
				<? foreach ($this->info->info->images->actors as $actor=>$img): ?>
				<div class="fix">
					<? if (strcmp($img, 'https://image.tmdb.org/t/p/originalNone') != 0): ?>
                	<img class="imgscale" src="<?= $img; ?>" alt="<?= $actor; ?>" />
                	<? else: ?>
                	<img class="imgscale" src="/img/actor.png" alt="<?= $actor; ?>" />
                	<? endif ?>
                	<div class="desc">
                    	<?= $actor; ?>
                	</div>
            	</div>
				<? endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
