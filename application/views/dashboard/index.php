<div class="col-md-6 col-md-offset-3">
        <ul class="breadcrumb">
          <li><?= $this->lang['title']; ?></li>
          <li class="active"><?= $this->lang['dashboard']; ?></li>
        </ul>

        <br>

        <h3><?= $this->lang['recentmovies']; ?></h3><hr>
        <div class="slider-movies">
            <? foreach ($this->movies as $movie): ?>
            <a href="/watch/index/<?= $movie->info->imdb; ?>">
	           <img class="owl-lazy" alt="<?= $movie->info->original_title; ?>" data-src="<?= $movie->info->images->poster[0]; ?>" />
            </a>
            <? endforeach; ?>
        </div>

        <br>
        
        <h3><?= $this->lang['recentepi']; ?></h3><hr>
        <div class="slider-series">
            <? foreach ($this->episodes as $episode): ?>
            <a href="/watch/index/ss<?= $episode->serieId . '-' . $episode->season . '-' . $episode->number; ?>">
            <div class="fix">
                <img class="imgscale owl-lazy" data-src="http://thetvdb.com/banners/<?= $episode->thumbnail; ?>" alt="<?= $episode->name; ?>" />
                <div class="desc">
                    <?= 'S' . $episode->season . 'E' . $episode->number . ': ' . $episode->name; ?>
                </div>
            </div>
            </a>
            <? endforeach; ?>
        </div>
</div>