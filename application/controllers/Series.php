<?php
include __DIR__ . '/../includes/TvDb/Http/HttpClient.php';
include __DIR__ . '/../includes/TvDb/Http/CurlClient.php';
include __DIR__ . '/../includes/TvDb/CurlException.php';
include __DIR__ . '/../includes/TvDb/Client.php';
include __DIR__ . '/../includes/TvDb/Serie.php';
include __DIR__ . '/../includes/TvDb/Banner.php';
include __DIR__ . '/../includes/TvDb/Episode.php';
use Moinax\TvDb\Client as TvDbClient;

class Series extends Core_controller
{

    public function __construct()
    {
        parent::__construct('series');
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        //load models
        $this->menu_m = Load::model('menu_m');
        $this->langs_m = Load::model('langs_m');
        $this->user_m = Load::model('user_m');

        $this->template->menuitems = $this->menu_m->getUserMenu();
        $this->template->langs = $this->langs_m->getLangs();
        //set page title
        $this->template->setPagetitle($this->lang['series'] . ' - ' . $this->lang['title']);  

        global $settings;
        $this->settings = $settings;

        $this->tvdb = new TvDbClient('http://thetvdb.com', $settings['TVDB_API']);
        #$cache = new FilesystemCache('/tmp/cache');
        #$httpClient = new CacheClient($cache, 600);
        #$his->tvdb->setHttpClient($httpClient);
    }

    function checkPrivilege()
    {
        if (!isset($_SESSION['user'])){
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('home/index');
            return false;
        } else {
            return true;
        }
    }

    public function getAllShows(){
        $result = array();
        $data = json_decode(file_get_contents($this->settings['SB_API'] . 'shows'))->data;
        foreach ($data as $id_=>$id) {
            array_push($result, $this->tvdb->getSerie($id_));
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

    public function getMediaInfo($title){
        $url = "http://www.omdbapi.com/?type=series&s=" . urlencode($title);
        $r = json_decode(file_get_contents($url));
        if ($r){
            return $r->Search;
        } else {
            return false;
        }
    }

    private function isShowInList($list, $id){
        $result = false;
        foreach ($list as $item){
            if (strcmp($item->imdbId,$id) == 0){
                $result = true;
            }
        }
        return $result;
    }

     public function search(){
        if ($this->checkPrivilege() == true) {
            $formdata = $this->form->getPost();
            $this->template->searchterm = $formdata->title;
            $existing = $this->getAllShows();
            $arr=array();
            foreach ($this->getMediaInfo($formdata->title) as $result){
                $id = $result->imdbID;
                if ($this->isShowInList($existing, $id) == false){
                    array_push($arr, $this->tvdb->getSerieByRemoteId(array('imdbid' => $id)));
                } else {
                    $this->setCurrentflashmessage($this->lang['hiddenshows'], 'info');
                }
            }
            $this->template->results = $arr;
            $this->template->setPagetitle('Search: ' . $formdata->title . ' - Gunther');
            $this->template->render('media/series.add');
        }
    }

    private function addSeries($id){
        $url = $this->settings['SB_API'] . 'show.addnew&tvdbid=' . urlencode($id) . '&status=wanted';
        return (json_decode(file_get_contents($url)));
    }

    public function add($id=false){
        if ($this->checkPrivilege() == true) {
            $this->template->setPagetitle($this->lang['addshow'] . ' - ' . $this->lang['title']);
            if ($id){
                if (strcmp($this->addSeries($id)->result, "success") == 0){
                    $this->setflashmessage($this->lang['showadded'], 'info');
                } else {
                    $this->setflashmessage($this->lang['showadderr'], 'danger');
                }
                $this->redirect('series/index');
             } else {
                $this->template->render('media/series.add');
             }
        }
     }

    public function getEpisode($serie_id, $season_id, $episode_id){
        return json_decode(file_get_contents($this->settings['SB_API'] . 'episode&tvdbid=' . urlencode($serie_id) . '&season=' . $season_id . '&episode=' . $episode_id . '&full_path=1'))->data;
    }

    public function episodes($id=false){
        if ($this->checkPrivilege() == true and $id != false) {
            $info = $this->tvdb->getSerieEpisodes($id);
            $this->template->show = $info['serie'];
            $seasons = array();
            $nr = 0;
            foreach ($info['episodes'] as $episode){
                try {
                    $epi = $this->getEpisode($id, $episode->season, $nr);
                } catch (Exception $e){}
                if (array_key_exists('status', $epi) and strcmp($epi->status, 'Downloaded') == 0){
                    if (array_key_exists($episode->season, $seasons) == false){
                        $seasons[$episode->season] = array();
                        $nr=0;
                    }
                    array_push($seasons[$episode->season], $episode);
                }
                $nr++;
            }
            $this->template->seasons = $seasons;
            $this->template->setPagetitle($this->template->show->name . ' - ' . $this->lang['title']);
            $this->template->render('media/series.episodes');
        }
    }

    public function index(){
        if ($this->checkPrivilege() == true) {
            if ($_POST){
                $formdata = $this->form->getPost();
                $this->template->searchterm = $formdata->search;
                $this->template->shows = $this->getShowsWith($formdata->search);
            } else {
                $this->template->shows = $this->getAllShows();
            }
            $this->template->render('media/series');
        } 
    }


}