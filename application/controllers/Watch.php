<?php

class Watch extends Core_controller
{
    public function __construct()
    {
        parent::__construct('watch');
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        //load models
        $this->menu_m = Load::model('menu_m');
        $this->langs_m = Load::model('langs_m');
	   $this->user_m = Load::model('user_m');

        $this->template->menuitems = $this->menu_m->getUserMenu();
        $this->template->langs = $this->langs_m->getLangs();
        //set page title
        $this->template->setPagetitle('Gunther');	
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

   public function stream(){
        if ($this->checkPrivilege() == true) {
            global $settings;
            $stream = new VideoStream($settings['media_location'] . $_GET['f']);
            $stream->start();
        } 
   }

    public function index()
    {
        if ($this->checkPrivilege() == true) {
            global $settings;
            $filepath = $settings['media_location'] . $_GET['f']; #TODO; use id instead of relative location
            $this->template->file = $filepath;
            $this->template->videotype = mime_content_type($filepath);
            $this->template->codec= $this->getCodecInfo($filepath)['videoCodec'];
            $subs = array();
            foreach (glob(dirname($filepath) . "*.srt") as $sub){
                array_push($subs, array(
                        'file' => $sub,
                        'lang' => substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                        'label'=> substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                    ));
            }
            $this->template->subs = $subs;
            $this->template->render('media/watch');
        } 
    }

    function getCodecInfo($inputFile)
    {
        $cmdLine = '/usr/bin/mediainfo --Output=XML ' . escapeshellarg($inputFile);

        exec($cmdLine, $output, $retcode);

        try
        {
            $xml = new SimpleXMLElement(join("\n",$output));
            $videoCodec = $xml->xpath('//track[@type="Video"]/Format');
            $audioCodec = $xml->xpath('//track[@type="Audio"]/Format');
        }
        catch(Exception $e)
        {
            return null;
        }

        return array(
            'videoCodec' => (string)$videoCodec[0],
            'audioCodec' => (string)$audioCodec[0],
        );
    }

}

/**
 * Description of VideoStream
 *
 * @author Rana
 * @link http://codesamplez.com/programming/php-html5-video-streaming-tutorial
 */
class VideoStream
{
    private $path = "";
    private $stream = "";
    private $buffer = 102400;
    private $start  = -1;
    private $end    = -1;
    private $size   = 0;
 
    function __construct($filePath) 
    {
        $this->path = $filePath;
    }
     
    /**
     * Open stream
     */
    private function open()
    {
        if (!($this->stream = fopen($this->path, 'rb'))) {
            die('Could not open stream for reading');
        }
         
    }
     
    /**
     * Set proper header to serve the video content
     */
    private function setHeader()
    {
        ob_get_clean();
        header("Content-Type: video/mp4");
        header("Cache-Control: max-age=2592000, public");
        header("Expires: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT');
        header("Last-Modified: ".gmdate('D, d M Y H:i:s', @filemtime($this->path)) . ' GMT' );
        $this->start = 0;
        $this->size  = filesize($this->path);
        $this->end   = $this->size - 1;
        header("Accept-Ranges: 0-".$this->end);
         
        if (isset($_SERVER['HTTP_RANGE'])) {
  
            $c_start = $this->start;
            $c_end = $this->end;
 
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                exit;
            }
            if ($range == '-') {
                $c_start = $this->size - substr($range, 1);
            }else{
                $range = explode('-', $range);
                $c_start = $range[0];
                 
                $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $c_end;
            }
            $c_end = ($c_end > $this->end) ? $this->end : $c_end;
            if ($c_start > $c_end || $c_start > $this->size - 1 || $c_end >= $this->size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                exit;
            }
            $this->start = $c_start;
            $this->end = $c_end;
            $length = $this->end - $this->start + 1;
            fseek($this->stream, $this->start);
            header('HTTP/1.1 206 Partial Content');
            header("Content-Length: ".$length);
            header("Content-Range: bytes $this->start-$this->end/".$this->size);
        }
        else
        {
            header("Content-Length: ".$this->size);
        }  
         
    }
    
    /**
     * close curretly opened stream
     */
    private function end()
    {
        fclose($this->stream);
        exit;
    }
     
    /**
     * perform the streaming of calculated range
     */
    private function stream()
    {
        $i = $this->start;
        set_time_limit(0);
        while(!feof($this->stream) && $i <= $this->end) {
            $bytesToRead = $this->buffer;
            if(($i+$bytesToRead) > $this->end) {
                $bytesToRead = $this->end - $i + 1;
            }
            $data = fread($this->stream, $bytesToRead);
            echo $data;
            flush();
            $i += $bytesToRead;
        }
    }
     
    /**
     * Start streaming video content
     */
    function start()
    {
        $this->open();
        $this->setHeader();
        $this->stream();
        $this->end();
    }
}

