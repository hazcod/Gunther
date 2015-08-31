<?php

	class CouchPotato extends MovieProvider {

		private function buildURL(){
			return $this->location . '/api/' . $this->settings['api'] . '/';
		}

		private function convert($json){

		}

		function getMovies(){
			$json = $this->cacher->getJson($this->buildURL() . 'media.list');
			
		}

		function getMovie($id){
			$json = $this->cacher->getJson($this->buildURL() . 'media.get?id=' . $id);

		}

		function restartApp(){
			$response = $this->cacher->getJson($this->buildURL() . 'app.restart');
			if ($response){
				return true;
			} else {
				return false;
			}
		}

		function refresh(){

		}

		function getRefreshProgress(){

		}

		function refreshMovie($id){

		}

		function searchMovie($title){

		}

		function addMovie($id){

		}

		function getLatestNotifications(){

		}

	}

?>
