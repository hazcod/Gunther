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
            $this->template->user = $this->user_m->getUserByLogin($_SESSION['user']);
            $this->template->movies = $this->mediamodel->getLastMovies(10);
            $this->template->episodes = $this->mediamodel->getLatestEpisodes(10);
            $this->template->render('dashboard/index');
        }
    }


}