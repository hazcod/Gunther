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

	<?php $this->renderPartial('flashmessage'); ?>

    <br>
    <div class="row">
    	<div class="col-md-3">
    		<img class="imgscale" src="http://thetvdb.com/banners/<?= $this->show->poster; ?>" alt="<?= $this->show->name; ?>" />
    		<hr>
    		<div class="pull-right">
    			<a class="btn btn-info btn-sm" target="_blank" href="http://www.imdb.com/title/<?= $this->show->imdbId; ?>"><i class="fa fa-info-circle"></i> IMDb</a>
    		</div>
    	</div>
    	<div class="col-md-9">
    		<div class="well">
    			<div class="pull-right">
					<?= join(',', $this->show->genres); ?>
    			</div>
    			<h3><?= $this->show->name; ?> <small><?= $this->show->firstAired->format('Y'); ?></small></h3>
    			<div class="pull-right">
    				<?= $this->show->rating; ?>/10
    			</div>
    			<hr>
    			<p>
    				<?= $this->show->overview; ?>
    			</p>
    			<p>
    				<strong><?= $this->lang['status']; ?></strong>: <?= $this->show->status; ?>
    				<br>
    				<ul class="list-inline">
    					<li> <strong><?= $this->lang['seasons']; ?></strong>: </li>
    					<?php foreach ($this->seasons as $nr_s => $season): ?>
    					<li> <a href="#s<?= ($nr_s+1); ?>">   <?= $this->lang['season'] . ' ' . $nr_s; ?></a> </li>
						<?php endforeach; ?>
					</ul>
    			</p>
    		</div>
			<?php if ($this->seasons): ?>
				<?php foreach ($this->seasons as $nr_s => $season): ?>
				<h3 id="s<?= ($nr_s+1); ?>"><i class="fa fa-book"></i> Season <?= $nr_s; ?></h3>
				<hr>
				<?php foreach ($season as $nr_e => $episode): ?>
				<div class="col-sm-2" style="margin-top:10px;">
					<a href="/watch/index/ss<?= $episode->serieId . '-' . $nr_s . '-' . ($nr_e+1); ?>">
					<div class="fix">
						<img class="imgscale" src="http://thetvdb.com/banners/<?= $episode->thumbnail; ?>" alt="<?= $episode->name; ?>" />
						<div class="desc">
							<?= $nr_e . ': ' . $episode->name; ?>
						</div>
					</div>
					</a>
				</div>
				<?php endforeach; ?>
				<div style="clear:both;"></div>
				<br>
				<?php endforeach; ?>
			<?php else: ?>
				<p>
					<?= $this->lang['noseasons']; ?>
				</p>
			<?php endif; ?>
    	</div>
    </div>
</div>
