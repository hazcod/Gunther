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
    		<img class="imgscale" src="<?= $this->show->images['poster']; ?>" alt="<?= $this->show->name; ?>" />
    		<hr>
    		<div class="pull-right">
    			<a class="btn btn-info btn-sm" target="_blank" href="http://imdb.com/title/<?= $this->show->imdbid; ?>"><i class="fa fa-info-circle"></i> IMDb</a>
    		</div>
    	</div>
    	<div class="col-md-9">
    		<div class="well">
    			<div class="pull-right">
					<?= join(',', $this->show->genres); ?>
    			</div>
    			<h3><?= $this->show->name; ?> <small><?php if ($this->show->status != 'Ended'){ echo $this->show->air_by_date; } else { echo 'Ended'; } ?></small></h3>
    			<div class="pull-right">
    				<?= $this->show->rating; ?>/10
    			</div>
    			<hr>
    			<p>
    				<?= $this->show->description; ?>
    			</p>
    			<p>
    				<ul class="list-inline">
    					<li> <strong><?= $this->lang['seasons']; ?></strong>: </li>
    					<?php foreach ($this->show->seasons as $nr_s => $season): ?>
    					<li> <span class="badge"><a href="#s<?= $nr_s; ?>">   <?= $nr_s+1; ?></a></span> </li>
						<?php endforeach; ?>
					</ul>
    			</p>
    		</div>
			<?php if ($this->show->seasons): ?>
				<?php foreach ($this->show->seasons as $nr_s => $season): ?>
				<h3 id="s<?= $nr_s; ?>"><i class="fa fa-book"></i> Season <?= $nr_s +1; ?></h3>
				<?php foreach ($season as $epi_nr => $episode): ?>
				<div class="col-sm-2" style="margin-top:10px;">
					<?php if ($episode->status == 'Downloaded'): ?>
					<a href="/watch/index/ss<?= $this->show->id . '-' . ($nr_s+1) . '-' . ($epi_nr+1); ?>">
					<div class="fix">
						<img class="imgscale" src="<?= $episode->images['thumbnail']; ?>" alt="<?= $episode->name; ?>" />
						<div class="desc">
							<?= ($epi_nr+1) . ': ' . $episode->name; ?>
						</div>
					</div>
					</a>
					<?php else: ?>
					<div class="fix">
						<img class="imgscale grey-inactive" src="<?= $episode->images['thumbnail']; ?>" alt="<?= $episode->name; ?>" />
						<div class="desc">
							<?= ($epi_nr+1) . ': ' . $episode->name; ?>
						</div>
					</div>
					<?php endif; ?>
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
