<?php

class Series extends Core_controller
{

    public function __construct()
    {
        parent::__construct(true);
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
            
        //set page title
        $this->template->setPagetitle($this->lang['series'] . ' - ' . $this->lang['title']);  
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
        $formdata = $this->form->getPost();
        $this->template->searchterm = $formdata->title;
        $existing = $this->mediamodel->getAllShows();
        $arr=array();
        foreach ($this->mediamodel->getMediaInfo('series', $formdata->title) as $result){
            $id = $result->imdbID;
            if ($this->isShowInList($existing, $id) == false){
                array_push($arr, $this->mediamodel->tvdb->getSerieByRemoteId(array('imdbid' => $id)));
            } else {
                $this->setCurrentflashmessage($this->lang['hiddenshows'], 'info');
            }
        }
        $this->template->results = $arr;
        $this->template->setPagetitle('Search: ' . $formdata->title . ' - Gunther');
        $this->template->render('media/series.add');
    }

    public function add($id=false){
        $this->template->setPagetitle($this->lang['addshow'] . ' - ' . $this->lang['title']);
        if ($id){
            if ($this->mediamodel->addSeries($id)){
                $this->setflashmessage($this->lang['showadded'], 'info');
            } else {
                $this->setflashmessage($this->lang['showadderr'], 'danger');
            }
            $this->redirect('series/index');
         } else {
            $this->template->render('media/series.add');
         }
     }

    public function episodes($id=false){
        if ($id != false) {
            $info = $this->mediamodel->tvdb->getSerieEpisodes($id);
            $this->template->show = $info['serie'];

            $seasons = array();
            $nr = 0;
            foreach ($info['episodes'] as $episode){
                $epi = $this->mediamodel->getEpisode($id, $episode->season, $nr);

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
        if ($_POST){
            $formdata = $this->form->getPost();
            $this->template->searchterm = $formdata->search;
            $this->template->shows = $this->mediamodel->getShowsWith($formdata->search);
        } else {
            $this->template->shows = $this->mediamodel->getAllShows();
        }
        $this->template->render('media/series');
    }


}