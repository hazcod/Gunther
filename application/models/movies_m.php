<?php
class Movies_m extends Core_db
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'movies';
    }
    
    public function getLatestMovies(){
	$result = false;
	$query = " SELECT * FROM videos ORDER BY date_added LIMIT 10;";
	$videos = $this->db->query($query)->getResult();
	if ($videos){
		$result = $videos;
	}
	return $result;
	}
    		
}
