<?php

	include __DIR__ . '/TvDb/Http/HttpClient.php';
	include __DIR__ . '/TvDb/Http/CurlClient.php';
	include __DIR__ . '/TvDb/CurlException.php';
	include __DIR__ . '/TvDb/Client.php';
	include __DIR__ . '/TvDb/Serie.php';
	include __DIR__ . '/TvDb/Banner.php';
	include __DIR__ . '/TvDb/Episode.php';
	include __DIR__ . '/TvDb/Http/Cache/Cache.php';
	include __DIR__ . '/TvDb/Http/Cache/FilesystemCache.php';
	include __DIR__ . '/TvDb/Http/CacheClient.php';
	include(APPLICATION_PATH . 'includes/Cacher.php');
	
	use Moinax\TvDb\Http\Cache\FilesystemCache;
	use Moinax\TvDb\Http\CacheClient;
	use Moinax\TvDb\Client;

	class MediaModel
	{
		public function __construct($settings)
		{
			$this->settings = $settings;

			if (strcmp($settings['TVDB_API'], '<API>') == 0){
				error_log('ERROR: Set the TheTVDB API in application/config.php !');
				echo "Please set the TheTVDB API key in " . APPLICATION_PATH . "/application/config.php\n";
			}

			if (strcmp($settings['CP_API'], 'http://localhost:5050/api/<API>/') == 0){
				error_log('ERROR: Set the CouchPotato API in application/config.php !');
				echo "Please set the CouchPotato API key in " . APPLICATION_PATH . "/application/config.php\n";
			}

			if (strcmp($settings['SB_API'], 'http://localhost:8081/api/<API>/?cmd=') == 0){
				error_log('ERROR: Set the Sickbeard API in application/config.php !');
				echo "Please set the Sickbeard/SickRage API key in " . APPLICATION_PATH . "/application/config.php\n";
			}

	        $this->tvdb = new Client($settings['TVDB_URL'], $settings['TVDB_API']);
	        $cache = new FilesystemCache($this->settings['CACHE_DIR']);
	        $httpClient = new CacheClient($cache, (int) $this->settings['CACHE_TTL']);
	        $this->tvdb->setHttpClient($httpClient);

	        $this->cacher = new Cacher();
	        $this->cacher->base_dir = $settings['CACHE_DIR'];
	        $this->cacher->base_url = '/cache/';
	        $this->cacher->expire_time = $settings['CACHE_TTL'];
		}

		#Fill cache function
		public function fillCache() {
			$this->flushMovieCache();
			$this->flushShowCache();
		}

		#Main caching function
		private function getJson($url, $force=false) {
			/**
		    $cacheFile = $this->settings['CACHE_DIR'] . md5($url);

		    if (file_exists($cacheFile) and $force == false) {
		        $fh = fopen($cacheFile, 'r');
		        $cacheTime = trim(fgets($fh));

		        if ($cacheTime > strtotime('-60 minutes')) {
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
			    	error_log("!ERROR: Could write to cache.. check your permissions! (" . $cacheFile . ")");	
			    } else {
			    	fwrite($fh, time() . "\n");
			    	fwrite($fh, $data);
			    	fclose($fh);
			    }
			    return $json;
			} else {
				error_log('Could not fetch ' . $url);
				return false;
			}
		    **/
		    return $this->cacher->get($url, true, $force);
		}

		private function getImage($url, $force=false) {
			return $this->cacher->get($url, false, $force);
		}


		public function flushMovieCache(){
			$this->getAllMovies(true);
			$this->getDoneMovies(true);
			$this->getBusyMovies(true);
		}

		public function flushShowCache(){
			$this->getAllShows(true);
		}


		##- DASHBOARD
		public function getLastMovies($limit=10){
        	$json = json_decode(@file_get_contents($this->settings['CP_API'] . 'media.list?status=done&offset=' . urlencode($limit)));
        	if ($json){
        		return $json->movies;
        	} else {
        		error_log('Could not get last movies');
        		return false;
        	}
    	}

    	public function scanmovies(){
    		$r = @file_get_contents($this->settings['CP_API'] . 'manage.update?full=true');
    		if ($r){
    			return true;
    		} else {
    			error_log('Could not scan movies');
    			return false;
    		}
    	}

    	public function scanshows(){
    		$shows = $this->getAllShows();
    		foreach ($shows as $show){
    			$r = @file_get_contents($this->settings['SB_API'] . 'show.refresh&tvdbid=' . $show->id);
    			$r2= @file_get_contents($this->settings['SB_API'] . 'show.update&tvdbid=' . $show->id);
    			if ($r == false){
    				error_log('Could not scan show ' . $show->id);
    			}
    			if ($r2 == false){
    				error_log('Could not update show ' . $show->id);
    			}
    		}
    		return true;
    	}

    	public function restartCouch(){
    		$r = @file_get_contents($this->settings['CP_API'] . 'app.restart');
    		if ($r){
    			return true;
    		} else {
    			error_log('Could not restart CouchPotato.');
    			return false;
    		}
    	}

    	public function restartSick(){
    		$result = false;
    		$r = json_decode(@file_get_contents($this->settings['SB_API'] .'sb.restart'));
    		if ($r){
    			$result = (strcmp($r->result, 'success') == 0);
    		}
    		return $result;
    	}

    	##- MOVIES
	 	public function getAllMovies($flushcache=false){
	        $json = $this->getJson($this->settings['CP_API'] . 'media.list', $flushcache);
	        if ($json){
	        	return $json->movies;
	        } else {
	        	return false;
	        }
	    }

	    public function getDoneMovies($flushcache=false){
	        $json = $this->getJson($this->settings['CP_API'] . 'media.list?status=done', $flushcache);
	        if ($json){
	        	return $json->movies;
	        } else {
	        	return false;
	        }
	    }

	    public function getBusyMovies($flushcache=false){
	        $json = $this->getJson($this->settings['CP_API'] . 'media.list?status=active', $flushcache);
	        if ($json){
	        	return $json->movies;
	        } else {
	        	return false;
	        }
	    }

		public function getMovie($id=false){
			$json = $this->getJson($this->settings['CP_API'] . 'media.get?id=' . $id);
			if ($json){
				return $json->media;
			} else {
				return false;
			}
		}

	    public function findExistingMovie($title){
	    	$json = $this->getJson($this->settings['CP_API'] . 'media.list?search=' . urlencode($title));
	    	if ($json){
	    		return $json->movies;
	    	} else {
	    		return false;
	    	}
	    }

	    public function findMovies($title){
	        return $this->getJson($this->settings['CP_API'] . 'movie.add?title=' . urlencode($title));
	    }

	    public function getMediaInfo($type, $title){
	        $url = "http://www.omdbapi.com/?type=" . urlencode($type) . "&s=" . urlencode($title);
	        $json = $this->getJson($url);
	        if ($json and array_key_exists('Search', $json)){
	        	return $json->Search;
	        } else {
	        	return false;
	        }
	    }

	    public function addMovie($id){
	        $json = json_decode(@file_get_contents($this->settings['CP_API'] . 'movie.add?identifier=' . urlencode($id)));
	        if ($json){
	        	return $json->success;
	        } else {
	        	return false;
	        }
	    }


    	##- SHOWS
	    public function getAllShows($flushcache=false){
	        $result = array();
	        $data = $this->getJson($this->settings['SB_API'] . 'shows', $flushcache);
	        if ($data){
		        foreach ($data->data as $id_=>$id) {
		            array_push($result, $this->tvdb->getSerie($id_));
		        }
		    }
	        return $result;
	    }

	    public function getLatestEpisodes($limit=10){
	    	$result = array();
	    	$data = json_decode(@file_get_contents($this->settings['SB_API'] . 'history&limit=' . urlencode($limit)));
	    	if ($data){
		    	foreach ($data->data as $log){
		    		array_push($result, $this->tvdb->getEpisode($log->tvdbid, $log->season, $log->episode));
		    	}
		    }
	    	return $result;
	    }

	    public function getShowsWith($part){
	        $result = array();
	        $all = $this->getAllShows();
	        if (strcmp($part, '') == 0){
	            $result = $all;
	        } else {
	            foreach ($all as $show){
	                if (stripos($show->name, $part) > -1){
	                    array_push($result, $show);
	                }
	            }
	        }
	        return $result;
	    }

	    public function addSeries($id){
	        $url = $this->settings['SB_API'] . 'show.addnew&tvdbid=' . urlencode($id) . '&status=wanted';
	        return (json_decode(@file_get_contents($url)) != false);
	    }


	    public function getEpisode($serie_id, $season_id, $episode_id){
	        $json = $this->getJson($this->settings['SB_API'] . 'episode&tvdbid=' . urlencode($serie_id) . '&season=' . $season_id . '&episode=' . $episode_id . '&full_path=1');
	        if ($json){
	        	return $json->data;
	        } else {
	        	return false;
	        }
	    }


	    ##- General media stuff
	    public function getCodecInfo($inputFile)
	    {
	        $cmdLine = '/usr/bin/mediainfo --Output=XML ' . escapeshellarg($inputFile);

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

	    public function getMimeType($inputFile){
	    	$result = false;
	    	if (file_exists($inputFile)){
		        $finfo = finfo_open(FILEINFO_MIME_TYPE);
		        $result = finfo_file($finfo, $inputFile);
		        finfo_close($finfo);
		    }
	        return $result;
	    }


	}

?>
