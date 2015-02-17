<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li>Gunther</li>
	  <li class="active">Add TV Show</li>
	  <div class="pull-right">
          <a href="/movies/index">
			<i class="fa fa-th-list fa-lg"></i>
		</a>
        </div>
	</ul>

	<? $this->renderPartial('flashmessage'); ?>
	
	<div class="well">
		<form action="/series/search" method="POST">
			<div class="input-group input-group-lg">
			  <span class="input-group-addon" id="sizing-addon1">title</span>
			  <input id="movieinput" name="title" type="text" class="form-control" placeholder="Game of Thrones" aria-describedby="sizing-addon1" autofocus value="<? if ($this->searchterm){ echo $this->searchterm; } ?>">
			</div>
		</form>
	</div>
	<? if ($this->results): ?>
	<br>
	<div class="well">
		<div class="list-group">
		<? foreach ($this->results as $show): ?>
		<? if ($show->name != false): ?>
		  <a href="/series/add/<?= $show->id; ?>" class="list-group-item">
		    <h4 class="list-group-item-heading"><?= $show->name; ?></h4>
		  </a>
		<? endif; ?>
		<? endforeach; ?>
		</div>
	</div>
	<? endif; ?>
</div>

