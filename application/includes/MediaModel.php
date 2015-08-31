<?php

	class MediaModel {

		private $settings = false;
		private $movieprovider = false;
		private $serieprovider = false;
		private $cache = false;

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

		public function flushMovieCache(){
			$this->movieprovider->getMovies('done', false, true);
			$this->movieprovider->getMovies('active', false, true);
		}

		public function flushShowCache(){

		}

		public function findMedia($type, $title){
			$url = "http://www.omdbapi.com/?type=" . urlencode($type) . "&s=" . urlencode($title);
			$json = $this->cache->getJson($url, '1 year');
			if ($json and array_key_exists('Search', $json)){
				return $json->Search;
			} else {
				return array();
			}
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
	        $cmdLine = '/usr/bin/mediainfo --Output=XML ' . $inputFile;
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
		public $id;
		public $location;
		public $actors;
		public $subtitles;
		public $status;
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
