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
        $this->template->movies = $this->mediamodel->movieProvider()->getMovies('active');
        $this->template->render('media/movies.busy');
    }

    private function isMovieInList($list, $id){
        $result = false;
        if ($list && $id){
            foreach ($list as $movie){
                if (strcmp($movie->id, $id) == 0){
                    $result = true;
                }
            }
        }
        return $result;
    }

     public function search(){
        $formdata = $this->form->getPost();
        if ($formdata){
            $this->template->searchterm = $formdata->title;
            $allmovies = $this->mediamodel->movieProvider()->getMovies(false);        
            $found_movies = array();
            foreach ($this->mediamodel->findMedia('movie', $formdata->title) as $id => $found_movie){
                if ($this->isMovieInList($allmovies, $found_movie->imdbID) == false){
                    array_push($found_movies, $found_movie);
                }
            }
            $this->template->results = $found_movies;
            $this->template->setPagetitle($this->lang['search'] . ': ' . $formdata->title . ' - Gunther');
            $this->template->render('media/movies.add');
        } else {
            $this->redirect('movies/add');
        }
    }

    public function add($id=false){
        $this->template->setPagetitle($this->lang['addmovie'] . ' - ' . $this->lang['title']);
        if ($id){
            if ($this->mediamodel->movieProvider()->addMovie($id) == true){
                $this->mediamodel->flushMovieCache();
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
            $this->template->movies = $this->mediamodel->movieProvider()->searchMovie($formdata->search);
        } else {
            $this->template->movies = $this->mediamodel->movieProvider()->getMovies();
        }
        $this->template->render('media/movies');
    }


}