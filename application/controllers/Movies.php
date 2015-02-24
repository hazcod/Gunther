<?php

class Movies extends Core_controller
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
        $this->template->setPagetitle($this->lang['movies'] . ' - ' . $this->lang['title']);  
    }

    public function busy(){
        $this->template->setPagetitle($this->lang['inactivemov'] . ' - ' . $this->lang['title']);
        $this->template->movies = $this->mediamodel->getBusyMovies();
        $this->template->render('media/movies.busy');
    }

    private function isMovieInList($list, $id){
        $result = false;
        if ($list){
            foreach ($list as $movie){
                if (strcmp($movie->info->imdb,$id) == 0){
                    $result = true;
                }
            }
        }
        return $result;
    }

     public function search(){
        $formdata = $this->form->getPost();
        $this->template->searchterm = $formdata->title;
        $existing = $this->mediamodel->getAllMovies();        
        $searchresults = $this->mediamodel->getMediaInfo('movie', $formdata->title);
        if ($searchresults){
            $arr=array();
            foreach ($this->mediamodel->getMediaInfo('movie', $formdata->title) as $result){
                $id = $result->imdbID;
                if ($this->isMovieInList($existing, $id) == false){
                    array_push($arr, $result);
                } else {
                    $this->setCurrentflashmessage($this->lang['hiddenmov'], 'info');
                }
            }
            $this->template->results = $arr;
        }
        $this->template->setPagetitle($this->lang['search'] . ': ' . $formdata->title . ' - Gunther');
        $this->template->render('media/movies.add');
    }

    public function add($id=false){
        $this->template->setPagetitle($this->lang['addmovie'] . ' - ' . $this->lang['title']);
        if ($id){
            if ($this->mediamodel->addMovie($id) == true){
                $this->setflashmessage($this->lang['movieadded'], 'info');
            } else {
                $this->setflashmessage($this->lang['movadderr'], 'danger');
            }
            $this->redirect('movies/index');
         } else {
            $this->template->render('media/movies.add');
         }
     }

    public function index(){
        if ($_POST){
            $formdata = $this->form->getPost();
            $this->template->searchterm = $formdata->search;
            $this->template->movies = $this->mediamodel->findExistingMovie($formdata->search);
        } else {
            $this->template->movies = $this->mediamodel->getDoneMovies();
        }
        $this->template->render('media/movies');
    }


}