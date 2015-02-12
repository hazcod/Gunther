<?php class Dashboard extends Core_controller
{

    public function __construct()
    {
        parent::__construct('dashboard');

        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        
        $this->langs_m = Load::model('langs_m');        
        $this->menu_m = Load::model('menu_m');
        $this->user_m = Load::model('user_m');
        $this->template->menuitems = $this->menu_m->getUserMenu($this->lang);
        $this->template->langs = $this->langs_m->getLangs();
        
       $this->template->setPagetitle('Dashboard - Gunther');
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

    public function index()
    { 
        if ($this->checkPrivilege() == true){
            $this->template->user = $this->user_m->getUserByLogin($_SESSION['user']);
            $this->template->render('dashboard/index');
        }
    }


}