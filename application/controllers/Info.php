<?php

class Info extends Core_controller
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
        
    }


    public function movie($id){
        $this->template->info = $this->mediamodel->getMovie($id);
        if ($this->template->info){
            $this->template->id = $id;
            $this->template->setPagetitle($this->template->info->info->original_title . ' - ' . $this->lang['title']);  
            $this->template->render('info/movie');
        } else {
            $this->setFlashmessage($this->lang['movienotfound'], 'danger');
            $this->redirect('movies/index');
        }
    }

    public function episode($id){
        
    }

    public function index(){
        $this->redirect('dashboard/index');
    }


}