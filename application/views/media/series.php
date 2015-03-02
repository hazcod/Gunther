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

	<? $this->renderPartial('flashmessage'); ?>

	<form method="post" action="/series/index">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="<?= $this->lang['searchshow']; ?>" name="search" value="<? if ($this->searchterm){ echo $this->searchterm; } ?>">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><i class="fa fa-search fa-lg"></i></button>
        <a href="/series/index" class="btn btn-default" type="button"><i class="fa fa-list fa-lg"></i></a>
      </span>
    </div>
	</form>

    <br>
	
	<? if ($this->shows): ?>
	<? foreach ($this->shows as $show): ?>
	<div class="col-sm-2" style="margin-top:10px;">
		<a href="/series/episodes/<?= $show->id; ?>">
			<img class="imgscale" alt="<?= $show->name; ?>" data-src="http://thetvdb.com/banners/<?= $show->poster; ?>" />
		</a>
	</div>
	<? endforeach; ?>
	<? else: ?>
	<p><?= $this->lang['noshows']; ?></p>
	<? endif; ?>
</div>