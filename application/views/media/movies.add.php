<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li>Gunther</li>
	  <li class="active">Add Movie</li>
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

	<? $this->renderPartial('flashmessage'); ?>
	
	<div class="well">
		<form action="/movies/search" method="POST">
			<div class="input-group input-group-lg">
			  <span class="input-group-addon" id="sizing-addon1">title</span>
			  <input id="movieinput" name="title" type="text" class="form-control" placeholder="Titanic" aria-describedby="sizing-addon1" autofocus value="<? if ($this->searchterm){ echo $this->searchterm; } ?>">
			</div>
		</form>
	</div>
	<? if ($this->results): ?>
	<br>
	<div class="well">
		<div class="list-group">
		<? foreach ($this->results as $movie): ?>
		<?= var_dump($movie); ?>
		  <a href="#" class="list-group-item">
		    <h4 class="list-group-item-heading">Movie 1</h4>
		    <p class="list-group-item-text">...</p>
		  </a>
		</div>
		<? endforeach; ?>
	</div>
	<? endif; ?>
</div>

