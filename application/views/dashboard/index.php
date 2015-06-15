<div class="col-md-6 col-md-offset-3">
        <ul class="breadcrumb">
          <li><?= $this->lang['title']; ?></li>
          <li class="active"><?= $this->lang['dashboard']; ?></li>
        </ul>

        <br>
        <h3><?= $this->lang['recentmovies']; ?></h3><hr>
        <div class="slider-movies">
        <?php if ($this->movies): ?>
            <?php foreach ($this->movies as $movie): ?> 
            <a href="/info/movie/<?= $movie->info->imdb ?>">
	           <img class="imgscale" alt="<?= $movie->info->original_title; ?>" src="<?= $movie->info->images->poster[0]; ?>" />
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>

        <br>
        
        <h3><?= $this->lang['recentepi']; ?></h3><hr>
        <div class="slider-series">
        <?php if ($this->episodes): ?>
            <?php foreach ($this->episodes as $episode): ?>
            <a href="/watch/index/ss<?= $episode->serieId . '-' . $episode->season . '-' . $episode->number; ?>">
            <div class="fix">
                <img class="imgscale" src="http://thetvdb.com/banners/<?= $episode->thumbnail; ?>" alt="<?= $episode->name; ?>" />
                <div class="desc">
                    <?= 'S' . $episode->season . 'E' . $episode->number . ': ' . $episode->name; ?>
                </div>
            </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
</div>