<?php
class Template {
    private $form;
    private $data = array();
    private $content = array();
    private $partials = array();
    private $layoutfile;
    private $pagetitle;

    // on instantiation: check the layoutfile
    public function __construct($layoutname = 'default')
    {
        $this->setLayout($layoutname);

        if (class_exists('Form')) {
            $this->form = Form::getInstance();
        }
    }

    // render the main content of the site
    // while rendering the layout, render the partials as well
    public function render($viewname)
    {
        $this->content = $this->renderView(Load::view($viewname));

        $this->renderLayout();
    }

    // helper to display the content in the layout template
    public function getContent()
    {
        echo $this->content;
    }

    // first step: generate partial
    public function renderPartial($name)
    {
        if (array_key_exists($name, $this->partials)) {
            echo $this->renderView($this->partials[$name]);
        } else {
            error_log("partial not rendered: $name");
        }
    }

    // second step: generate the layout
    public function renderLayout()
    {
        include($this->layoutfile);
    }

    public function setLayout($layoutname)
    {
        $this->layoutfile = Load::layout($layoutname);
    }

    // helper to generate a partial
    public function setPartial($partialname, $partialfile = '')
    {
        // if $partialfile is not set, use the partialname as filename
        $partialfile = ($partialfile) ? $partialfile : $partialname;

        $this->partials[$partialname] = Load::partial($partialfile);

        return $this;
    }

    // render a view (partial or main content)
    private function renderView($view)
    {



        // create an output buffer to get a rendered partial and store its content in a var.
        ob_start();
        include($view);
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }

    public function setPagetitle($title)
    {
        $this->pagetitle = $title;

        return $this;
    }

    public function getPagetitle()
    {
        return $this->pagetitle;
    }

    // automatic getter and setter, remapping every value to the protected attribute
    // read more about this on www.php.net/manual/en/language.oop5.overloading.php
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
}

