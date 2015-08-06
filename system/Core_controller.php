<?php 

abstract class Core_controller
{
    protected $template;
    protected $data = array();

    public function __construct($protected=false)
    {
        $this->template = new Template();
        $this->template->flashmessage = $this->getFlashMessage();

        $this->form = Form::getInstance();
        
        global $settings;
        $this->settings = $settings;

        if (!isset($_SESSION['lang'])){
            $_SESSION['lang'] = $settings['DEFAULT_LANG'];
        }

        include(APPLICATION_PATH . 'languages/lang.'.$_SESSION['lang'].'.php');
        $this->lang = getLang();
        $this->template->lang = $this->lang;

        include(APPLICATION_PATH . 'includes/MediaModel.php');
        $this->mediamodel = new MediaModel($settings);
        $this->template->mediamodel = $this->mediamodel;

        include(APPLICATION_PATH . 'includes/Password.php');

        if ($protected == true){
            $this->checkPrivilege();
        }

        $this->user_m = Load::model('user_m');
        $this->user_m->settings = $this->settings;
        
        if (isset($_SESSION['user'])){
        	$this->user = $this->user_m->getUserByLogin($_SESSION['user']);      
	}

	$this->menu_m = Load::model('menu_m');
        $this->template->menuitems = $this->menu_m->getMenu($this->user, $this->lang);

        $this->langs_m = Load::model('langs_m');
        $this->template->langs = $this->langs_m->getLangs();
    }

    public function checkPrivilege()
    {
        if (!isset($_SESSION['user'])){
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('home/index');
            return false;
        } else {
            return true;
        }
    }

    public function isAdminUser(){
        if ($this->user != false and strcmp($this->user->id, '1') == 0){
            return true;
        } else {
            return false;
        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return false;
    }

    public function setFlashmessage($message, $status = 'success')
    {
        $flashmessage = new stdClass();
        $flashmessage->status = $status;
        $flashmessage->message = $message;

        $_SESSION['flashmessage'] = $flashmessage;

        return $this;
    }

    public function setCurrentFlashmessage($message, $status = 'success')
    {
        $this->template->flashmessage = new stdClass();
        $this->template->flashmessage->status = $status;
        $this->template->flashmessage->message = $message;

        return $this;
    }

    public function getFlashMessage()
    {
        if (isset($_SESSION['flashmessage'])) {
            $flashmessage = $_SESSION['flashmessage'];
            unset($_SESSION['flashmessage']);
        } else {
            $flashmessage = false;
        }

        return $flashmessage;
    }

    public function redirect($newlocation = '')
    {
        if (!headers_sent($filename, $linenum)) {
            header('Location: ' .  URL::base_uri($newlocation));
            exit();
        } else {
            echo "Headers already sent in $filename on line $linenum\n";
        }
    }
}
