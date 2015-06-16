<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li><?= $this->lang['title']; ?></li>
	  <li class="active"><?= $this->lang['addshow']; ?></li>
	  <div class="pull-right">
          <a href="/movies/index">
			<i class="fa fa-th-list fa-lg"></i>
		</a>
        </div>
	</ul>

	<?php $this->renderPartial('flashmessage'); ?>
	
	<div class="well">
		<form action="/series/search" method="POST">
			<div class="input-group input-group-lg">
			  <span class="input-group-addon" id="sizing-addon1"><?= $this->lang['movtitle']; ?></span>
			  <input id="movieinput" name="title" type="text" class="form-control" placeholder="Game of Thrones" aria-describedby="sizing-addon1" autofocus value="<?php if ($this->searchterm){ echo $this->searchterm; } ?>">
			</div>
		</form>
	</div>
	<?php if (count($this->results) > 0): ?>
	<br>
	<div class="well">
		<div class="list-group">
		<?php foreach ($this->results as $show): ?>
		<?php if ($show->name != false): ?>
		  <a href="/series/add/<?= $show->id; ?>" class="list-group-item">
		    <h4 class="list-group-item-heading"><?= $show->name; ?></h4>
		  </a>
		<?php endif; ?>
		<?php endforeach; ?>
		</div>
	</div>
	<?php else: ?>
	<div class="well">
		<p>
		<strong><?= $this->lang['noshows']; ?></strong>
		</p>
	</div>
	<?php endif; ?>
</div>

