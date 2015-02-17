<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li>Gunther</li>
	  <li class="active"><?= $this->show->name; ?></li>
	  <div class="pull-right">
		<a href="/series/add">
			<i class="fa fa-plus-square-o fa-lg"></i>
          </a>
		&nbsp;&nbsp;
          <a href="/series/busy">
			<i class="fa fa-clock-o fa-lg"></i>
		</a>
        </div>
	</ul>

	<? $this->renderPartial('flashmessage'); ?>

    <br>

	<? foreach ($this->seasons as $nr_s => $season): ?>
	<h3>Season <?= ($nr_s+1); ?></h3>
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

</div>