<!DOCTYPE html>
<html lang="<?= $_SESSION['lang']; ?>">
    <head>
        <?php $this->renderPartial('headermeta'); ?>
    </head>
    <body>
        <?php $this->renderPartial('navbar'); ?>

		<div class="page-header" id="banner">
            <div class="row">
                <h2 class="hidden"><?php echo $this->getPagetitle(); ?></h2>
            </div>
		</div>

    	<div class="container-fluid">
            <?php $this->getContent(); ?>
        </div>

        <script type="text/javascript" src="<?= URL::base_uri(); ?>js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= URL::base_uri(); ?>js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= URL::base_uri(); ?>js/owl.carousel.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
              $('.slider-movies, .slider-series').owlCarousel({
    			lazyLoad:true,
    			loop:true,
    			margin:10,
                autoplay:true,
                autoplayTimeout:4000,
                autoplayHoverPause:true,
                responsive:{
            	    250: {
                        items:1,
                    },
                    300: {
            		    items:2,
            	    },
                    500:{
                        items:3,
                    },
                    700:{
                        items:4,
                    },
                    900:{
                        items:5,
                    }
                }
              });
            });
        </script>
        <script type="text/javascript" src="<?= URL::base_uri(); ?>js/video.js"></script>
        <script type="text/javascript">
		_V_.options.flash.swf = "<?= URL::base_uri(); ?>js/video-js.swf";
        </script>
    </body>
</html>

