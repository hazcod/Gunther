<?php

	class MediaModel {

		private $settings = false;
		private $movieprovider = false;
		private $serieprovider = false;
		private $cache = false;

		public function __construct($settings) {

			$this->settings = $settings;
			$autoload = new Autoloader();

			$this->cache = new Cache($this->settings['cache_location']);


			$autoload->map(array(
				$settings['movie_provider'] => $this->settings['movie_provider'] . '.php',
				$settings['serie_provider'] => $this->settings['serie_provider'] . '.php'
			));


			$autoload->directory(array(
				APPLICATION_PATH . 'includes/providers/movie/',
				APPLICATION_PATH . 'includes/providers/serie/'
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

	}


	class Cache {

		private $location;

		public function __construct($cache_location){
			$this->location = $cache_location;
		}

		public function getJson($url, $ttl, $force=false) {
		    $cacheFile = $this->settings['CACHE_DIR'] . md5($url);

		    if (file_exists($cacheFile) and $force == false) {
		        $fh = fopen($cacheFile, 'r');
		        $cacheTime = trim(fgets($fh));

		        if ($ttl == false){
		        	$ttl = '-60 minutes';
		        }
		        if ($cacheTime > strtottl($ttl)) {
		            return json_decode(fread($fh, filesize($cacheFile)));
		        } else {
			        fclose($fh);
			        unlink($cacheFile);
			    }
		    }

		    $data = @file_get_contents($url);
		    if ($data){
		    	$json = json_decode($data);
			    $fh = fopen($cacheFile, 'w');
			    if ($fh == false){
			    	error_log("!ERROR: Could not write to cache.. check your permissions! (" . $cacheFile . ")");	
			    } else {
			    	fwrite($fh, ttl() . "\n");
			    	fwrite($fh, $data);
			    	fclose($fh);
			    }
			    return $json;
			} else {
				error_log('Could not fetch ' . $url);
				return false;
			}
		}
	}


	abstract class MovieProvider {
		private $settings = false;
		private $cache = false;

		function __construct($cacher, $settings){
			$this->settings = $settings;
			$this->cache = $cacher;
		}

		abstract protected function getMovies();
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
		private $api = false;
		private $cache = false;

		function __construct($cacher, $settings){
			$this->settings = $settings;
			$this->cache = $cacher;
		}

		abstract protected function getShows();
		abstract protected function getSeasons($id);
		abstract protected function getEpisodes($id, $season);
		abstract protected function getEpisode($id, $season, $id);
		abstract protected function restartApp();
		abstract protected function refresh();
		abstract protected function getRefreshProgress();
		abstract protected function refreshShow($id);
		abstract protected function searchShow($title);
		abstract protected function addShow($id);
		abstract protected function getLatestNotifications();
	}

	class Movie {
		public $name;
		public $year;
		public $description;
		public $genres;
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
		public $banner;
		public $poster;
	}

	class Season {
		public $nr;
		public $airdate;
		public $name;
		public $status;
		public $picture;
	}

	class Episode {
		public $airdate;
		public $description;
		public $size;
		public $location;
		public $name;
		public $status;
		public $picture;
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
	            } else {
			error_Log("Provider not found: " . $path . $file . '.php');
		   }
	        }
	    }
	}
?>
