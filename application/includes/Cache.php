<?php
class Cache {

	private $location;

	public function __construct($cache_location){
		$this->location = $cache_location;
	}

	private function download($url, $path){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($ch);

		curl_close($ch);

		file_put_contents($path, $data);
	}

	public function getImage($url, $force=false) {
		if (!file_exists($this->location . 'img/')) {
		    mkdir($this->location . 'img/', 0777, true);
		}
		$cacheFile =  $this->location . 'img/' . sha1($url) . '.' . pathinfo($url, PATHINFO_EXTENSION);
		if (file_exists($cacheFile) and $force == false){
			return '/' . $cacheFile;
		} else {
			$this->download($url, $cacheFile);
			if (file_exists($cacheFile)){
				return '/' . $cacheFile;
			} else {
				error_log('Could not fetch ' . $url . ' to ' . $cacheFile);
				return false;
			}
		}
	}

	public function getJson($url, $ttl=false) {
	    $cacheFile = $this->location . md5($url);

	    if (file_exists($cacheFile) and $ttl != false) {
	        $fh = fopen($cacheFile, 'r');
	        $cacheTime = trim(fgets($fh));

	        if ($cacheTime > strtotime($ttl)) {
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
		    	fwrite($fh, time() . "\n");
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
?>