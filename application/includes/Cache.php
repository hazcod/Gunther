<?php
/*
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
*/
class Cache {

	private $location;
	private $tvdb;

	public function __construct($cache_location){
		$this->location = $cache_location;
		/*
		$this->tvdb = new Client($settings['TVDB_URL'], $settings['TVDB_API']);
        $cache = new FilesystemCache($this->settings['CACHE_DIR']);
        $httpClient = new CacheClient($cache, (int) $this->settings['CACHE_TTL']);
        $this->tvdb->setHttpClient($httpClient);
        */
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