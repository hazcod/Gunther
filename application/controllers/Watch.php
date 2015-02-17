<?php

class Watch extends Core_controller
{

    public function __construct()
    {
        parent::__construct(true);
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        //load models
        $this->menu_m = Load::model('menu_m');
        $this->langs_m = Load::model('langs_m');
        $this->user_m = Load::model('user_m');

        $this->template->menuitems = $this->menu_m->getUserMenu($this->lang);
        $this->template->langs = $this->langs_m->getLangs();
        //set page title
        $this->template->setPagetitle($this->lang['title']);	
    }

    private function streamMovie($id){
        $movie = $this->mediamodel->getMovie($id); 
        $stream = new VideoStream($movie->releases[0]->files->movie[0]);
        return $stream->start();
    }

    private function streamShow($id){
        $parts = explode('-', $id);
        $serie_id = $parts[0];
        $season_id = $parts[1];
        $episode_id = $parts[2];
        $serie = $this->mediamodel->getShow($serie_id, $season_id, $episode_id);
        $stream = new VideoStream($serie->location);
        return $stream->start();
    }

   public function stream($id=false){
        $prefix = substr($id, 0, 2);
        if (strcmp($prefix, 'ss') == 0){
            #custom id, so tv show
            return $this->streamShow(substr($id, 2));
        } else {
            if (strcmp($prefix, 'tt') == 0){
                $id = substr($id, 2);
            }
            return $this->streamMovie($id);
        }
   }

   private function getMovieSub($id, $lang){
        $movie = $this->mediamodel->getMovie($id);
        $subfile = false;
        foreach ($movie->releases[0]->files->subtitle as $sub){
            if ($lang == substr($sub, strpos(substr($sub,0,-4), '.')+1, -4)){
               $subfile = $sub;
            }
        }
        return $subfile;
   }

   private function getShowSub($id, $lang){
        $parts = explode('-', $id);
        $serie_id = $parts[0];
        $season_id = $parts[1];
        $episode_id = $parts[2];
        $episode = $this->mediamodel->getShow($serie_id, $season_id, $episode_id);
        #TODO; get subs

        $subfile = false;
        return $subfile; 
   }

   public function sub($id=false, $lang=false){
        $prefix = substr($id, 0, 2);
        if (strcmp($prefix, 'ss') == 0){
            #custom id, so tv show
            $subfile = $this->getShowSub($id, $lang);
        } else {
            if (strcmp($prefix, 'tt') == 0){
                $id = substr($id, 2);
            }
            $subfile = $this->getMovieSub($id, $lang);
        }
        return $this->offerFile($subfile);
   }

   private function offerFile($file){
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Type: text/plain'); # Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
        header('Content-Length: ' . strlen($file));
        header('Connection: close');
        return file_get_contents($file);
   }


   private function watchMovie($id){
        $movie = $this->mediamodel->getMovie($id);
        $filepath = $movie->releases[0]->files->movie[0];
        $this->template->file = $id;
        $this->template->type = $this->mediamodel->getMimeType($filepath);
        $this->template->codec= $this->mediamodel->getCodecInfo($filepath)['videoCodec'];
        $subs = array();
        foreach ($movie->releases[0]->files->subtitle as $sub){
            array_push($subs, array(
                    'file' => $sub,
                    'lang' => substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                    'label'=> substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                ));
        }
        $this->template->streamstr = $id;
        $this->template->subs = $subs;
        $this->template->setPagetitle($movie->info->original_title . ' - Gunther');
        $this->template->render('media/watch');
   }

   private function watchShow($id){
        $parts = explode('-', $id);
        $serie_id = $parts[0];
        $season_id = $parts[1];
        $episode_id = $parts[2];
        $episode = $this->mediamodel->getEpisode($serie_id, $season_id, $episode_id);
        $this->template->file = $episode->location;
        $this->template->type = $this->mediamodel->getMimeType($episode->location);
        $this->template->codec = $this->mediamodel->getCodecInfo($episode->location)['videoCodec'] . ',' . $this->mediamodel->getCodecInfo($episode->location)['audioCodec'];
        $this->template->streamstr = 'ss' . $id;
        $subs = array();
        foreach (glob(basename($episode->location . '*.srt')) as $sub){
            array_push($subs, array(
                    'file' => $sub,
                    'lang' => substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                    'label'=> substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                ));
        }
        $this->template->subs = $subs;
        $this->template->setPagetitle($episode->name . ' - ' . $this->lang['title']);
        $this->template->render('media/watch');
   }

    public function index($id=false)
    {
        $prefix = substr($id, 0, 2);
        if (strcmp($prefix, 'ss') == 0){
            #custom id, so tv show
            $this->watchShow(substr($id, 2));
        } else {
            if (strcmp($prefix, 'tt') == 0){
                $id = substr($id, 2);
            }
            $this->watchMovie($id);
        }
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

