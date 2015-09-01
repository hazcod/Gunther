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
        foreach ($list as $item){
            if (strcmp((string) $item->id, $id) == 0){
                return true;
            }
        }
        return false;
    }

     public function search(){
        $formdata = $this->form->getPost();
        $this->template->searchterm = $formdata->title;
        $existing = $this->mediamodel->showProvider()->getShows();
        $arr=array();
        foreach ($this->mediamodel->showProvider()->searchShow($formdata->title) as $result){
            if ($this->isShowInList($existing, $result->id) == false){
                array_push($arr, $result);
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
            if ($this->mediamodel->showProvider()->addShow($id)){
                $this->setflashmessage($this->lang['showadded'], 'info');
            } else {
                $this->setflashmessage($this->lang['showadderr'], 'danger');
            }
            $this->mediamodel->flushShowCache();
            $this->redirect('series/index');
         } else {
            $this->template->render('media/series.add');
         }
     }

    public function episodes($id=false){
        if ($id != false) {
            $this->template->show = $this->mediamodel->showProvider()->getShow($id);
            $this->template->setPagetitle($this->template->show->name . ' - ' . $this->lang['title']);
            $this->template->render('media/series.episodes');
        }
    }

    public function index(){
        if ($_POST){
            $formdata = $this->form->getPost();
            $this->template->searchterm = $formdata->search;
            $this->template->shows = $this->mediamodel->showProvider()->searchShow($formdata->search);
        } else {
            $this->template->shows = $this->mediamodel->showProvider()->getShows();
        }
        $this->template->render('media/series');
    }


}
