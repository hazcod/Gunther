<?php

class CouchPotato extends MovieProvider {

	private function buildURL(){
		return $this->settings['location'] . 'api/' . $this->settings['api'] . '/';
	}

	private function convert($json){
		$result = false;
		if ($json != false){
			$result = new Movie();
			$result->name = $json->title;
			$result->year = $json->info->year;
			$result->description = $json->info->plot;
			$result->genres = $json->info->genres;
			$result->id = $json->identifiers->imdb;
			$result->actors = $json->info->actors;
			$release = $this->getRelease($json->releases);
			$result->location = $release['movie'];
			$result->subtitles = $release['subtitles']; 
			$result->quality  = $release['quality'];
			$result->size = $release['size'];
			$result->rating = 0; //todo

			foreach ($json->info->images->actors as $actor => $img){
				$json->info->images->actors->$actor = $this->cache->getImage($img);
			}

			$result->images = array(
				'poster' => $this->cache->getImage($json->info->images->poster[0]),
				'actors' => $json->info->images->actors,
				'backdrop' => $this->cache->getImage($json->info->images->backdrop[0]),
			);
			$result->status = $json->status;
			$result->rating = $json->info->rating->imdb[0];
		}
		return $result;
	}

    public function humanFileSize($size, $unit='')
    {
	if ( (!$unit && $size >= 1<<30) || $unit == "GB")
		return number_format($size/(1<<30),2). " GB";
	if ( (!$unit && $size >= 1<<20) || $unit == "MB")
		return number_format($size/(1<<20),2) . " MB";
	if ( (!$unit && $size >= 1<<10) || $unit == "KB")
		return number_format($size/(1<<10),2) . " KB";
	return number_format($size) . " bytes";
    }


 	private function getRelease($releases){
		$result = array('movie' => false, 'subtitles' => array(), 'quality' => false, 'size' => 0);
		foreach ($releases as $id => $release){
			if ($release->status == "done"){
				//movie
				foreach ($release->files->movie as $id => $movie_entry){
					if (file_exists($movie_entry)){
						if ($result['movie'] == false or filesize($movie_entry) > filesize($result['movie'])){
							$result['movie'] = $movie_entry;
							$result['quality'] = $release->quality;
							$result['size'] = $this->humanFileSize(filesize($movie_entry));
						}
					}
				}
				//subs
				foreach ($release->files->subtitle as $id => $sub_entry){
					if (file_exists($sub_entry)){
						$lang = pathinfo(pathinfo($sub_entry, PATHINFO_EXTENSION), PATHINFO_EXTENSION);
						if ($lang == false) $lang = 'en';
						array_push($result['subtitles'], array('language' => $lang, 'subtitle' => $sub_entry));
					}
				}
			}
		}
		return $result;
	}

	function getMovies($status='done', $num=false, $force=false){
		$result = array();
		$url = 'media.list?x=x';
		if ($status != false) $url .= '&status=' . urlencode($status);
		if ($num != false) $url .= '&limit_offset=' . urlencode($num);
		$ttl = '-1 hour';
		if ($force == true){
			$ttl = false;
		}
		$json = $this->cache->getJson($this->buildURL() . $url, $ttl);
		if ($json->success){
			foreach ($json->movies as $id => $movie){
				array_push($result, $this->convert($movie));
			}
		}
		return $result;
	}

	function getMovie($id){
		$json = $this->cache->getJson($this->buildURL() . 'media.get?id=' . $id, '-1 week');
		return $this->convert($json->media);
	}

	function restartApp(){
		$response = $this->cache->getJson($this->buildURL() . 'app.restart');
		if ($response){
			return true;
		} else {
			return false;
		}
	}

	function refresh(){
		$json = $this->cache->getJson($this->buildURL() . 'manage.update?full=true');
		return ($json && $json->success);
	}

	function getRefreshProgress(){
		$json = $this->cache->getJson($this->buildURL() . 'manager.progress');
		return $json->progress;
	}

	function refreshMovie($id){
		$json = $this->cache->getJson($this->buildURL() . 'media.refresh?id=' . urlencode($id));
		return ($json && $json->success == true);
	}

	function searchMovie($title){
		$result = array();
		$json = $this->cache->getJson($this->buildURL() . 'media.list?search=' . urlencode($title), '-1 week');
    	if ($json->success){
    		foreach ($json->movies as $id => $movie){
    			array_push($result, $this->convert($movie));
    		}
    	}
    	return $result;
	}

	function addMovie($id){
		$json = $this->cache->getJson($this->buildURL() . 'movie.add?identifier=' . urlencode($id));
		return ($json != false && $json->success == true);
	}

	function getLatestNotifications($offset=false, $limit=100){
		if ($offset == false) $offset = '';
		$json = $this->cache->getJson($this->buildURL() . 'notification.list?limit_offset=' . urlencode($offset) . ',' . urlencode($limit));
		$result = $json->success;
		if ($result == true) $result = $json->notifications;
		return $result;
	}

}

?>
