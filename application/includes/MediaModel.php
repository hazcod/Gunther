<?php

	class MediaModel {

		private $settings = false;
		private $movieprovider = false;
		private $serieprovider = false;
		public $cache = false;

		public function __construct($settings) {

			$this->settings = $settings;
			$autoload = new Autoloader();

			include_once 'Cache.php';
			$this->cache = new Cache($this->settings['cache_location']);

			$autoload->map(array(
				$settings['movie_provider'] => $this->settings['movie_provider'] . '.php',
				$settings['serie_provider'] => $this->settings['serie_provider'] . '.php'
			));


			$autoload->directory(array(
				APPLICATION_PATH . 'includes/providers/movie/',
				APPLICATION_PATH . 'includes/providers/show/'
			));
			$autoload->register();

			if ($this->settings['movie_provider'] != ''){
				$this->movieprovider = new $settings['movie_provider']($this->cache, $this->settings['movie_settings']);
				if (is_a($this->movieprovider, 'MovieProvider') == false){
					error_log($this->settings['movie_provider']  . ' is not a valid MovieProvider!');
					$this->movieprovider = false;
				}
			}

			if ($this->settings['serie_provider'] != ''){
				$this->serieprovider = new $settings['serie_provider']($this->cache, $this->settings['serie_settings']);
				if (is_a($this->serieprovider, 'SerieProvider') == false){
					error_log($this->settings['serie_provider']  . ' is not a valid SerieProvider!');
					$this->serieprovider = false;
				}
			}
		}

		public function movieProvider() {
			return $this->movieprovider;
		}

		public function showProvider() {
			return $this->serieprovider;
		}

		public function flushMovieCache(){
			$this->movieprovider->getMovies('done', false, true);
			$this->movieprovider->getMovies('active', false, true);
		}

		public function flushShowCache(){
			$this->showprovider()->getShows(true);

<<<<<<< HEAD
		private function useMovieCachedImages($input){
			if (is_array($input)){
       			foreach ($input as $i => $movie){
        			if (sizeof($input[$i]->info->images->poster) > 0){
					$input[$i]->info->images->poster[0] = $this->getImageURL($input[$i]->info->images->poster[0]);
        			} else {
        				//error_log('No poster given for ' . $input[$i]->info->original_title);
        				$input[$i]->info->images->poster[0] = '/img/notfound.png';
        			}
        		}
        	} else {
        		$input->info->images->poster = array();
    			if (sizeof($input->info->images->poster) > 0){
					$input->info->images->poster[0] = $this->getImageURL($input[$i]->info->images->poster[0]);
    			} else {
    				//error_log('No poster given for ' . $input[$i]->info->original_title);
    				$input->info->images->poster[0] = '/img/notfound.png';
    			}
        		if (array_key_exists('actors', $input->info->images)){
        			foreach ($input->info->images->actors as $name => $picture){
        				$input->info->images->actors->{$name} = $this->getImageURL($input->info->images->actors->{$name});
        			}
        		}
        	}
        	return $input;
=======
>>>>>>> providers
		}

		public function findMediaByTitle($type, $title){
			//https://www.omdbapi.com/?t=spongebob&type=series&y=&plot=full&r=json
			$url = "https://www.omdbapi.com/?plot=full&r=json&type=" . urlencode($type) . "&t=" . urlencode($title);
			return $this->cache->getJson($url, '-1 month');
		}

		public function findMediaByID($type, $imdbid){
			$url = "https://www.omdbapi.com/?plot=full&r=json&type=" . urlencode($type) . "&i=" . urlencode($imdbid);
			return $this->cache->getJson($url, '-1 month');
		}

		public function getMimeType($inputFile){
			$result = false;
			if (file_exists($inputFile)){
		        $finfo = finfo_open(FILEINFO_MIME_TYPE);
		        $result = finfo_file($finfo, $inputFile);
		        finfo_close($finfo);
		    }
		    return $result;
		}

	    public function getCodecInfo($inputFile)
	    {
<<<<<<< HEAD
	        $cmdLine = '/usr/bin/mediainfo --Output=XML ' . $inputFile;

=======
	        $cmdLine = '/usr/bin/mediainfo --Output=XML "' . $inputFile . '"';
>>>>>>> providers
	        exec($cmdLine, $output, $retcode);
	        try
	        {
	            $xml = new SimpleXMLElement(join("\n",$output));
	            $videoCodec = $xml->xpath('//track[@type="Video"]/Format');
	            $audioCodec = $xml->xpath('//track[@type="Audio"]/Format');
	        }
	        catch(Exception $e)
	        {
	            return null;
	        }
	        return array(
	            'videoCodec' => (string)$videoCodec[0],
	            'audioCodec' => (string)$audioCodec[0],
	        );
	    }

	}

<<<<<<< HEAD
	    public function getRelease($movie, $printall=false){
	    	$result = false;

	    	if ($movie && array_key_exists('releases', $movie)){
	    		foreach ($movie->releases as $release){
	    			if (($result == false) && array_key_exists('files', $release) && (count($release->files) >0)
	    						&& array_key_exists('movie', $release->files) && (count($release->files->movie) > 0)
         						&& file_exists($release->files->movie[0])){
						
							if ($printall == true){
								$result = $release;	
							} else {
								$result = $release->files->movie[0];
							}
						/*if (count($release->files->movie) > 1){
							// how would we ever check the quality of multiple video files?
							error_log("Notice: multiple video files were available, but we can only take the first. (" . $movie->info->original_title . ") dump: " . var_dump($release->files->movie));
						}*/
	    			}
	    		}
	    	}

	    	return $result;
	    }
=======
>>>>>>> providers

	abstract class MovieProvider {
		protected $settings = false;
		protected $cache = false;

		function __construct($cacher, $settings){
			$this->settings = $settings;
			$this->cache = $cacher;
		}

		abstract protected function getMovies($status='done', $num=false);
		abstract protected function getMovie($id);
		abstract protected function restartApp();
		abstract protected function refresh();
		abstract protected function getRefreshProgress();
		abstract protected function refreshMovie($id);
		abstract protected function searchMovie($title);
		abstract protected function addMovie($id);
		abstract protected function getLatestNotifications();
	}

	abstract class SerieProvider {
		protected $api = false;
		protected $cache = false;

		function __construct($cacher, $settings){
			$this->settings = $settings;
			$this->cache = $cacher;
		}

		abstract protected function getShows();
		abstract protected function getShow($id);
		//abstract protected function getSeasons($id);
		//abstract protected function getEpisodes($id, $season);
		abstract protected function getEpisode($id, $season, $episode);
		abstract protected function restartApp();
		abstract protected function refresh();
		abstract protected function getRefreshProgress();
		abstract protected function refreshShow($id);
		abstract protected function searchShow($title);
		abstract protected function addShow($id);
		abstract protected function getLatestNotifications();
		abstract protected function getLatestEpisodes($type="downloaded", $limit=10);
	}

	class Movie {
		public $name;
		public $year;
		public $description;
		public $genres;
		public $id;
		public $location;
		public $actors;
		public $subtitles;
		public $status;
		public $images;
		public $rating; //todo
	}

	class Show {
		public $air_by_date;
		public $description;
		public $airs;
		public $genres;
		public $language;
		public $network;
		public $quality;
		public $seasons;
		public $name;
		public $active = false;
		public $id;
		public $images;
		public $rating; //todo
	}

	class Season {
		public $name;
		public $nr;
		public $status;
		public $airdate;
		public $images;
	}

	class Episode {
		public $airdate;
		public $description;
		public $size;
		public $location;
		public $name;
		public $status;
		public $images;
	}

	class AutoLoader {
	    private $map = array();
	    private $directories = array();

	    public function register() {
	        spl_autoload_register(array($this, 'load'));
	    }
	    public function unregister() {
	        spl_autoload_unregister(array($this, 'load'));
	    }
	    public function map($files) {
	        $this->map = array_merge($this->map, $files);
	        $this->load($this->map);
	    }
	    public function directory($folder) {
	        $this->directories = array_merge($this->directories, $folder);
	    }
	    public function load($class) {
	        if ($file = $this->find($class)) {
	            require $file;
	        }
	    }
	    public function find($file) {
	        foreach ($this->directories as $path) {
	            if (file_exists($path . $file . '.php')) {
	                return $path . $file . '.php';
	            }/* else {
					error_Log("Provider not found: " . $path . $file . '.php');
		   		}*/
	        }
	    }
	}
?>
