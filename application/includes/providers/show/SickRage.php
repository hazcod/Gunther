<?php

class SickRage extends ShowProvider {

	private function buildURL(){
		return $this->settings['location'] . 'api/' . $this->settings['api'] . '/?cmd=';
	}

	private function findMediaByTitle($type, $title){
		$url = "https://www.omdbapi.com/?plot=full&r=json&type=" . urlencode($type) . "&t=" . urlencode($title);
		return $this->cache->getJson($url, '-1 month');
	}

	private function convertShow($show){
		$result = false;
		if ($show != false){
			$result = new Show();
			$result->air_by_date = $show->air_by_date;
			$result->quality = $show->qality;
			$result->id = $show->tvdbid;
			$result->name = $show->show_name;
			$result->network = $show->network;
			$result->language = $show->language;
			$result->seasons = $this->getSeasons($result->id);
			$result->active = ($show->status != 'Ended');

			$omdb = $this->findMediaByTitle($show->name);
			$result->images = array(
					'poster' => $this->cache->getImage($omdb->Poster), //$this->getPoster($result->id),
					'banner' => $this->getBanner($result->id)
				);
			$result->description = $omdb->Plot;
			$result->airs = $omdb->Released;
			$result->genres = array(); //not available
		}
		return $result;
	}

	private function convertSeason($season){
		$result = false;
		if ($season != false){
			$result = new Season();
			$result->name = $season->name;
			$result->nr = $season->nr;
			$result->status = $season->status;
			$result->airdate = $season->airdate;
			$result->images = array(); //not available
		}
		return $result;
	}

	private function convertEpisode($episode){
		$result = false;
		if ($episode != false){
			$result = new Episode();
			$result->airdate = $episode->airdate;
			$result->description = $episode->description;
			$result->size = $episode->file_size_human;
			$result->location = $episode->location;
			$result->name = $episode->name;
			$result->status = $episode->status;
			$result->images = array(); //not available
		}
		return $result;
	}

	private function getBanner($id){
		return $this->cache->getImage($this->buildURL() . 'show.getbanner&tvdbid=' . urlencode($id));
	}

	function getLatestEpisodes($type="downloaded", $limit=10){
    	$result = array();
    	$json = $this->cache->getJson($this->buildURL() . 'history&type=' . urlencode($type) . '&limit=' . urlencode($limit)));
    	if ($json){
	    	foreach ($json->data as $log){
				array_push($result, $this->convertEpisode($log));
	    	}
	    }
    	return $result;
    }

	function getShows($force=false){
		$result = array();
		if ($force == false) $force = '-1 day';
		$json = $this->cache->getJson($this->buildURL() . 'shows&sort=name');
		if ($json && $json->result == 'success'){
			for ($json->data as $id => $showObj){
				array_push($result, $this->convertShow($showObj));
			}
		}
		return $result;
	}

	function getSeasons($id){
		$result = array();
		$json = $this->cache->getJson($this->buildURL() . 'show.seasons&tvdbid=' . urlencode($id));
		if ($json && $json->result == 'success'){
			foreach ($json->data as $nr => $seasonObj){
				$seasonObj->nr = $nr;
				array_push($result, $this->convertSeason($seasonObj));
			}
		}
		return $result;
	}

	function getEpisodes($id, $season){
		return array();		
	}

	function getEpisode($id, $season, $episode){
		$result = false;
		$json = $this->cache->getJson($this->buildURL() . 'episode&tvdbid=' . urlencode($id) . '&season=' . urlencode($season) . '&episode=' . urlencode($episode));
		if ($json && $json->result == 'success'){
			$result = $this->convertEpisode($json->data);
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
		$json = $this->cache->getJson($this->buildURL() . 'sb.searchtvdb&name=' . urlencode($title) . '&lang=' . urlencode($this->settings['lang']));
		if ($json->result == 'success'){
			foreach ($json->results as $showObj){
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