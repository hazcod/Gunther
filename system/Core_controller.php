<?php 
if (!isset($_SESSION['lang'])){
    $_SESSION['lang'] = 'en'; //default
}

abstract class Core_controller
{
    protected $template;
    protected $data = array();

    public function __construct($controllername)
    {
        $this->template = new Template();
        $this->template->flashmessage = $this->getFlashMessage();

        $this->form = Form::getInstance();
        
        include(APPLICATION_PATH . 'languages/lang.'.$_SESSION['lang'].'.php');
        $this->lang = getLang($controllername);
        $this->template->lang = $this->lang;
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
