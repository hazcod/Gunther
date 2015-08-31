<?php 

class Dashboard extends Core_controller
{

    public function __construct()
    {
        parent::__construct(true);

        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');

        $this->template->setPagetitle($this->lang['dashboard'] . ' - ' . $this->lang['title']);
    }

    public function index()
    { 
        if ($this->checkPrivilege() == true){
            if ($this->mediamodel->movieProvider() != false)
            $this->template->movies = $this->mediamodel->movieProvider()->getMovies('done', 10);
            if ($this->mediamodel->showProvider() != false)
            $this->template->episodes = $this->mediamodel->showProvider()->getLatestEpisodes(10);
            $this->template->render('dashboard/index');
        }
    }


}
