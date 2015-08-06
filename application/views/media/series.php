<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li><?= $this->lang['title']; ?></li>
	  <li class="active"><?= $this->lang['series']; ?></li>
	  <div class="pull-right">
		<a href="/series/add">
			<i class="fa fa-plus-square-o fa-lg"></i>
          </a>
		</a>
        </div>
	</ul>

	<?php $this->renderPartial('flashmessage'); ?>

	<form method="post" action="/series/index">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="<?= $this->lang['searchshow']; ?>" name="search" value="<?php if ($this->searchterm){ echo $this->searchterm; } ?>">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><i class="fa fa-search fa-lg"></i></button>
        <a href="/series/index" class="btn btn-default" type="button"><i class="fa fa-list fa-lg"></i></a>
      </span>
    </div>
	</form>

    <br>
	
	<?php if ($this->shows): ?>
	<?php foreach ($this->shows as $show): ?>
	<div class="col-sm-2" style="margin-top:10px;">
		<a href="/series/episodes/<?= $show->id; ?>">
			<img class="imgscale" alt="<?= $show->name; ?>" data-src="<?= $show->poster; ?>" />
		</a>
	</div>
	<?php endforeach; ?>
	<?php else: ?>
	<p><?= $this->lang['noshows']; ?></p>
	<?php endif; ?>
</div>