<div class="col-md-8 col-md-offset-2">
	<div class="jumbotron">
        <h1>Synology...</h1>
        <p><?= $this->lang['synology-info']; ?></p>
        <p><a class="btn btn-primary btn-lg" href="https://www.synology.com/en-global/"><?= $this->lang['readmore']; ?></a></p>
    </div>

    <br>

    <div>
        <h3><?= $this->lang['synology-howto']; ?></h3>
        <ol>
	        <li>
	        	Click on File Station (the folder icon) in your Synology web interface.
	        	<img src="<?= URL::base_uri(); ?>img/help/synology-file-icon.png" class="imgscale" alt="Synology File station icon" />
	        </li>
	        <br>
	        <li>
	        	Click on "Tools" and on "Make Connection".
	        	<img src="<?= URL::base_uri(); ?>img/help/synology-context-icon.png" class="imgscale" alt="Synology tools menu." />
	        </li>
	        <br>
	        <li>
	        	Add our webdav share as seen on the picture. (fill in your username and password)
	        	<img src="<?= URL::base_uri(); ?>img/help/synology_webdav.png" class="imgscale" alt="Synology webdav details" />
	        </li>
        </ol>
    </div>
</div>