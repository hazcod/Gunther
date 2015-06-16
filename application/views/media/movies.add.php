<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li><?= $this->lang['title']; ?></li>
	  <li class="active"><?= $this->lang['addmovie']; ?></li>
	  <div class="pull-right">
          <a href="/movies/index">
			<i class="fa fa-th-list fa-lg"></i>
		</a>
		&nbsp;&nbsp;
          <a href="/movies/busy">
			<i class="fa fa-clock-o fa-lg"></i>
		</a>
        </div>
	</ul>

	<?php $this->renderPartial('flashmessage'); ?>
	
	<div class="well">
		<form action="/movies/search" method="POST">
			<div class="input-group input-group-lg">
			  <span class="input-group-addon" id="sizing-addon1"><?= $this->lang['movtitle']; ?></span>
			  <input id="movieinput" name="title" type="text" class="form-control" placeholder="Titanic" aria-describedby="sizing-addon1" autofocus value="<?php if ($this->searchterm){ echo $this->searchterm; } ?>">
			</div>
		</form>
	</div>
	<?php if (count($this->results) > 0): ?>
	<br>
	<div class="well">
		<div class="list-group">
		<?php foreach ($this->results as $movie): ?>
		  <a href="/movies/add/<?= $movie->imdbID; ?>" class="list-group-item">
		    <h4 class="list-group-item-heading"><?= $movie->Title . ' (' . $movie->Year . ')'; ?></h4>
		  </a>
		<?php endforeach; ?>
		</div>
	</div>
	<?php elseif ($this->results): ?>
	<div class='well'>
		<p>
		<strong><?= $this->lang['nomovies']; ?></strong>
		</p>
	</div>
	<?php endif; ?>
</div>

