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
	
	use Moinax\TvDb\Http\Cache\FilesystemCache;
	use Moinax\TvDb\Http\CacheClient;
	use Moinax\TvDb\Client;

	class MediaModel
	{
		public function __construct($settings)
		{
			$this->settings = $settings;
	        $this->tvdb = new Client($settings['TVDB_URL'], $settings['TVDB_API']);
	        $cache = new FilesystemCache($this->settings['CACHE_DIR']);
	        $httpClient = new CacheClient($cache, (int) $this->settings['CACHE_TTL']);
	        $this->tvdb->setHttpClient($httpClient);
		}

		#Fill cache function
		public function fillCache() {
			$this->getAllMovies();
			$this->getAllShows();
			$this->getBusyMovies();
			$this->getLastMovies();
			$this->getLatestEpisodes();
		}

		#Main caching function
		private function getJson($url) {
		    $cacheFile = $this->settings['CACHE_DIR'] . md5($url);

		    if (file_exists($cacheFile)) {
		        $fh = fopen($cacheFile, 'r');
		        $cacheTime = trim(fgets($fh));

		        if ($cacheTime > strtotime('-60 minutes')) {
		            return fread($fh, filesize($cacheFile));
		        } else {
			        fclose($fh);
			        unlink($cacheFile);
			    }
		    }

		    $json = json_decode(file_get_contents($url));
		    if ($json){
			    $fh = fopen($cacheFile, 'w');
			    fwrite($fh, time() . "\n");
			    fwrite($fh, $json);
			    fclose($fh);
			    return $json;
			} else {
				return false;
			}
		    
		}


		##- DASHBOARD
		public function getLastMovies($limit=10){
        	$json = json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?status=done&offset=' . urlencode($limit)));
        	if ($json){
        		return $json->movies;
        	} else {
        		return false;
        	}
    	}

    	##- MOVIES
	 	public function getAllMovies(){
	        $json = $this->getJson($this->settings['CP_API'] . 'media.list');
	        if ($json){
	        	return $json->movies;
	        } else {
	        	return false;
	        }
	    }

	    public function getDoneMovies(){
	        $json = $this->getJson($this->settings['CP_API'] . 'media.list?status=done');
	        if ($json){
	        	return $json->movies;
	        } else {
	        	return false;
	        }
	    }

	    public function getBusyMovies(){
	        $json = $this->getJson($this->settings['CP_API'] . 'media.list?status=active');
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
	        if ($json){
	        	return $json->Search;
	        } else {
	        	return false;
	        }
	    }

	    public function addMovie($id){
	        $json = json_decode(file_get_contents($this->settings['CP_API'] . 'movie.add?identifier=' . urlencode($id)));
	        if ($json){
	        	return $json->success;
	        } else {
	        	return false;
	        }
	    }


    	##- SHOWS
	    public function getAllShows(){
	        $result = array();
	        $data = $this->getJson($this->settings['SB_API'] . 'shows');
	        if ($data){
		        foreach ($data->data as $id_=>$id) {
		            array_push($result, $this->tvdb->getSerie($id_));
		        }
		    }
	        return $result;
	    }

	    public function getLatestEpisodes($limit=10){
	    	$result = array();
	    	$data = json_decode(file_get_contents($this->settings['SB_API'] . 'history&limit=' . urlencode($limit)));
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
	        return (json_decode(file_get_contents($url)));
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
	        $finfo = finfo_open(FILEINFO_MIME_TYPE);
	        $result = finfo_file($finfo, $inputFile);
	        finfo_close($finfo);
	        return $result;
	    }


	}

?>