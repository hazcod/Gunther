<?php
	
	include __DIR__ . '/../includes/TvDb/Http/HttpClient.php';
	include __DIR__ . '/../includes/TvDb/Http/CurlClient.php';
	include __DIR__ . '/../includes/TvDb/CurlException.php';
	include __DIR__ . '/../includes/TvDb/Client.php';
	include __DIR__ . '/../includes/TvDb/Serie.php';
	include __DIR__ . '/../includes/TvDb/Banner.php';
	include __DIR__ . '/../includes/TvDb/Episode.php';
	use Moinax\TvDb\Client as TvDbClient;

	class MediaModel
	{
		public function __construct($settings)
		{
			$this->settings = $settings;
	        $this->tvdb = new TvDbClient($settings['TVDB_URL'], $settings['TVDB_API']);
	        #TODO: tvdb caching
	        #$cache = new FilesystemCache($this->settings['CACHE_DIR']);
	        #$httpClient = new CacheClient($cache, (int) $this->settings['CACHE_TTL']);
	        #$his->tvdb->setHttpClient($httpClient);
		}


		##- DASHBOARD
		public function getLastMovies($limit=10){
        	return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?status=done&offset=' . urlencode($limit)))->movies;
    	}

    	##- MOVIES
	 	public function getAllMovies(){
	        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list'))->movies;
	    }

	    public function getDoneMovies(){
	        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?status=done'))->movies;
	    }

	    public function getBusyMovies(){
	        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?status=active'))->movies;
	    }

		public function getMovie($id=false){
		 return json_decode(file_get_contents($this->settings['CP_API'] . 'media.get?id=' . $id))->media;
		}

	    public function findExistingMovie($title){
	        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?search=' . urlencode($title)))->movies;
	    }

	    public function findMovies($title){
	        return json_decode(file_get_contents($this->settings['CP_API'] . 'movie.add?title=' . urlencode($title)));
	    }

	    public function getMediaInfo($type, $title){
	        $url = "http://www.omdbapi.com/?type=" . urlencode($type) . "&s=" . urlencode($title);
	        return json_decode(file_get_contents($url))->Search;
	    }

	    public function addMovie($id){
	        return json_decode(file_get_contents($this->settings['CP_API'] . 'movie.add?identifier=' . urlencode($id)))->success;
	    }


    	##- SHOWS
	    public function getAllShows(){
	        $result = array();
	        $data = json_decode(file_get_contents($this->settings['SB_API'] . 'shows'))->data;
	        foreach ($data as $id_=>$id) {
	            array_push($result, $this->tvdb->getSerie($id_));
	        }
	        return $result;
	    }

	    public function getLatestEpisodes($limit=10){
	    	$result = array();
	    	$data = json_decode(file_get_contents($this->settings['SB_API'] . 'history&limit=' . urlencode($limit)))->data;
	    	foreach ($data as $log){
	    		array_push($result, $this->tvdb->getEpisode($log->tvdbid, $log->season, $log->episode));
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
	        return json_decode(file_get_contents($this->settings['SB_API'] . 'episode&tvdbid=' . urlencode($serie_id) . '&season=' . $season_id . '&episode=' . $episode_id . '&full_path=1'))->data;
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