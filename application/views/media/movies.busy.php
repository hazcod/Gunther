<div class="col-md-8 col-md-offset-2">
	<ul class="breadcrumb">
	  <li><?= $this->lang['title']; ?></li>
	  <li class="active"><?= $this->lang['busymovies']; ?></li>
	  <div class="pull-right">
		<a href="/movies/add">
			<i class="fa fa-plus-square-o fa-lg"></i>
          </a>
		&nbsp;&nbsp;
          <a href="/movies/index">
			<i class="fa fa-th-list fa-lg"></i>
		</a>
        </div>
	</ul>
	
	<?php if ($this->movies): ?>
	<?php foreach ($this->movies as $movie): ?>
	<div class="col-sm-2" style="margin-top:10px;">
		<img class="grey-inactive imgscale" height="250" alt="<?= $movie->name; ?>" src="<?php if ($movie->images['poster'] != false){ echo $movie->images['poster']; } else { echo 'http://www.clipartbest.com/cliparts/9T4/ep4/9T4ep4xac.png'; } ?>" />
	</div>
	<?php endforeach; ?>
	<?php endif; ?>
</div>

	<br>
	<br>
	<br>
