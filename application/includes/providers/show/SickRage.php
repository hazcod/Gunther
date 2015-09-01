<?php

class SickRage extends SerieProvider {

	private function buildURL(){
		return $this->settings['location'] . 'api/' . $this->settings['api'] . '/?cmd=';
	}

	private function buildShow($id){
		$show = false;
		$json = $this->cache->getJson($this->buildURL() . 'show&tvdbid=' . urlencode($id), 1440);
		if ($json && $json->success == 'success'){
			$show = $this->convertShow($json);
			if ($show){
				$this->cache->storeObject($id, $show);
			}
		}
		return $show;
	}

	private function convertShow($show){
		$result = false;
		if ($show != false){
			$result = new Show();
			$result->quality = $show->quality;
			$result->id = $show->tvdbid;
			$result->name = $show->show_name;
			$result->network = $show->network;
			$result->language = $show->language;
			if (array_key_exists('imdbid', $show) == true) $result->imdbid = $show->imdbid;

			$tvdb = $this->cache->tvdb->getSerieEpisodes($result->id, $this->settings['lang']);

			$result->seasons = $this->getSeasons($result->id, $tvdb);
			$result->active = ($show->status != 'Ended');
			$result->status = $show->status;
			$result->images = array(
					'poster' => $this->cache->getImage('http://thetvdb.com/banners/' . $tvdb['serie']->poster),
					'banner' => $this->cache->getImage($this->getBanner($result->id))
				);
			$result->description = $tvdb['serie']->overview;
			$result->airs = $tvdb['serie']->airsDayOfWeek . ' ' . $tvdb['serie']->airsTime;

			$result->air_by_date = $tvdb['serie']->firstAired->format('Y');
			$result->genres = $tvdb['serie']->genres;
			$result->rating = $tvdb['serie']->rating;
		}
		return $result;
	}

	private function convertEpisode($episode, $id, $nr, $e_nr){
		$result = false;
		if ($episode != false){
			$result = new Episode();
			$result->airdate = $episode->airdate;
			$result->size = $episode->file_size;
			$result->location = $episode->location;
			$result->name = $episode->name;
			$result->status = $episode->status;

			$tvdb = $this->cache->tvdb->getEpisode($id, $nr, $e_nr);
			$result->description = $tvdb->overview;
			$result->images = array(
					'thumbnail' => $this->cache->getImage('http://thetvdb.com/banners/' . $tvdb->thumbnail)
				);
		}
		return $result;
	}

	private function getBanner($id){
		return $this->cache->getImage($this->buildURL() . 'show.getbanner&tvdbid=' . urlencode($id));
	}
/*
	function searchShow($title){
		$result = array();
		$shows = $this->getShows();
		foreach ($shows as $show){
			if ($title == '' or strpos(strtolower($show->name), strtolower($title)) !== 0){
				array_push($result, $show);
			}
		}
		return $result;
	}
*/

	function getLatestEpisodes($type="downloaded", $limit=10){
    	$result = array();
    	$json = $this->cache->getJson($this->buildURL() . 'history&type=' . urlencode($type) . '&limit=' . urlencode($limit));
    	if ($json){
	    	foreach ($json->data as $log){
				array_push($result, $this->convertEpisode($log));
	    	}
	    }
    	return $result;
    }

    function getShow($id){
    	$result = false;
    	$json = $this->cache->getJson($this->buildURL() . 'show&tvdbid=' . urlencode($id), 1440);
    	if ($json && $json->result == 'success'){
    		$result = $this->convertShow($json->data);
    	}
    	return $result;
    }

	function getShows($force=false){
		$result = array();
		if ($force == false) $force = '-1 day';
		$json = $this->cache->getJson($this->buildURL() . 'shows&sort=name', $force);
		if ($json && $json->result == 'success'){
			foreach ($json->data as $id => $showObj){
				array_push($result, $this->convertShow($showObj));
			}
		}
		return $result;
	}

	
	function getSeasons($id){
		$result = array();
		$json = $this->cache->getJson($this->buildURL() . 'show.seasons&tvdbid=' . urlencode($id), 1440);
		if ($json && $json->result == 'success'){
			foreach ($json->data as $nr => $s){
				$season = array();
				foreach ($s as $e_nr => $episode){
					array_push($season, $this->convertEpisode($episode, $id, $nr, $e_nr));
				}
				array_push($result, $season);
			}
		}
		return $result;
	}

	function getEpisode($id, $season, $episode){
		$result = false;
		$json = $this->cache->getJson($this->buildURL() . 'episode&full_path=1&tvdbid=' . urlencode($id) . '&season=' . urlencode($season) . '&episode=' . urlencode($episode), 1440);
		if ($json && $json->result == 'success'){
			$result = $this->convertEpisode($json->data, $id, $season, $episode);
		}
		return $result;
	}

	function restartApp(){
		$json = $this->cache->getJson($this->buildURL() . 'sb.restart');
		return ($json && $json->result == 'success');
	}

	function refresh(){
		return false;
	}

	function getRefreshProgress(){
		return false;
	}

	function refreshShow($id){
		$json = $this->cache->getJson($this->buildURL() . 'show.refresh&tvdbid=' . urlencode($id));
		return ($json && $json->result == 'success');
	}

	function searchShow($title){
		$result = array();
		//echo $this->buildURL() . 'sb.searchtvdb&name=' . urlencode($title) . '&lang=' . urlencode($this->settings['lang']); die();
		$json = $this->cache->getJson($this->buildURL() . 'sb.searchtvdb&name=' . urlencode($title) . '&lang=' . urlencode($this->settings['lang']));
		if ($json->result == 'success'){
			foreach ($json->data->results as $i => $showObj){
				$show = new Show();
				$show->id = $showObj->tvdbid;
				$show->name = $showObj->name;
				$show->air_by_date = $showObj->first_aired;
				array_push($result, $show);
			}
		}
		return $result;
	}

	function addShow($id){
		$json = $this->cache->getJson($this->buildURL() . 'show.addnew&status=wanted&tvdbid=' . urlencode($id));
		return ($json && $json->result == 'success');
	}

	function getLatestNotifications($limit=100){
		$json = $this->cache->getJSon($this->buildURL() . 'history&type=downloaded&limit=' . urlencode($limit));
	}
}

?>