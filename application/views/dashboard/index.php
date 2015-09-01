<div class="col-md-6 col-md-offset-3">
    <ul class="breadcrumb">
      <li><?= $this->lang['title']; ?></li>
      <li class="active"><?= $this->lang['dashboard']; ?></li>
    </ul>

    <?php $this->renderPartial('flashmessage'); ?>

    <br>
    <?php if ($this->movies): ?>
    <h3><?= $this->lang['recentmovies']; ?></h3><hr>
    <div class="slider-movies">
        <?php foreach ($this->movies as $movie): ?> 
        <a href="/info/movie/<?= $movie->id ?>">
           <img class="imgscale" alt="<?= $movie->name; ?>" src="<?= $movie->images['poster']; ?>" />
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <br>

    <?php if ($this->episodes): ?>
    <h3><?= $this->lang['recentepi']; ?></h3><hr>
    <div class="slider-series">
        <?php foreach ($this->episodes as $episode): ?>
        <a href="/watch/index/ss<?= $episode->serieId . '-' . $episode->season . '-' . $episode->number; ?>">
        <div class="fix">
            <img class="imgscale" src="<?= $episode->thumbnail; ?>" alt="<?= $episode->name; ?>" />
            <div class="desc">
                <?= 'S' . $episode->season . 'E' . $episode->number . ': ' . $episode->name; ?>
            </div>
        </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>