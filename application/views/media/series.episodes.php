<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li><?= $this->lang['title']; ?></li>
	  <li><?= $this->lang['series']; ?></li>
	  <li class="active"><?= $this->show->name; ?></li>
	  <div class="pull-right">
		<a href="/series/add">
			<i class="fa fa-plus-square-o fa-lg"></i>
          </a>
        </div>
	</ul>

	<? $this->renderPartial('flashmessage'); ?>

    <br>

<? if ($this->seasons): ?>
    <strong><?= $this->lang['seasons']; ?>:
    <ul class="list-inline">
    <? foreach ($this->seasons as $nr_s => $season): ?>
    	<li>
    		<a href="#s<?= ($nr_s+1); ?>">  #<?= ($nr_s+1); ?></a>
    	</li>
	<? endforeach; ?>
	</ul>

    <br>


	<? foreach ($this->seasons as $nr_s => $season): ?>
	<h3><a href="#s<?= ($nr_s+1); ?>"><i class="fa fa-book"></i></a> Season <?= ($nr_s+1); ?></h3>
	<hr>
	<? foreach ($season as $nr_e => $episode): ?>
	<div class="col-sm-2" style="margin-top:10px;">
		<a href="/watch/index/ss<?= $episode->serieId . '-' . $nr_s . '-' . ($nr_e+1); ?>">
		<div class="fix">
			<img class="imgscale" src="http://thetvdb.com/banners/<?= $episode->thumbnail; ?>" alt="<?= $episode->name; ?>" />
			<div class="desc">
				<?= ($nr_e+1) . ': ' . $episode->name; ?>
			</div>
		</div>
		</a>
	</div>
	<? endforeach; ?>
	<div style="clear:both;"></div>
	<br>
	<? endforeach; ?>
<? endif; ?>

</div>
