<div class="col-md-6 col-md-offset-3">
	<div class="jumbotron">
        <h1>Synology...</h1>
        <p><?= $this->lang['synology-info']; ?></p>
        <p><a class="btn btn-primary btn-lg" href="https://www.synology.com/en-global/"><?= $this->lang['readmore']; ?></a></p>

        <br>

        <h3><?= $this->lang['synology-howto']; ?></h3>
        <ol>
	        <li>
	        	Click on File Station (the folder icon) in your Synology web interface.
	        	<img sc="<?= URL::base_uri(); ?>img/help/" class="img=scale" alt="Synology File station icon" />
	        </li>
	        <li>
	        	Click on "Tools" and on "Mount Remote Folder".
	        	<img sc="<?= URL::base_uri(); ?>img/help/" class="img=scale" alt="Synology tools menu." />
	        </li>
	        <li>
	        	Add our webdav share as seen on the picture.
	        	<img src="<?= URL::base_uri(); ?>/img/help/synology_webdav.png" class="scale-img" alt="Synology webdav details" />
	        </li>
        </ol>
    </div>
</div>