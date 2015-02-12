<div class="col-md-8 col-md-offset-2">
        <ul class="breadcrumb">
          <li>Gunther</li>
          <li class="active">Movies</li>
        </ul>
<? foreach ($this->movies as $movie): ?>
        <div class="col-sm-2">
		<a href="/watch/index/?f=<?= str_replace('/media/storage/','', $movie[0]->releases[0]->files->movie[0]); ?>">
		<img src="<?= $movie[0]->info->images->poster[0]; ?>" />
		<?= $movie[0]->info->original_title ?>
		</a>
</div>
	   </div>
<? endforeach; ?>
</div>
