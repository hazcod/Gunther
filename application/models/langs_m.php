<?php
class Langs_m
{
    var $LANG_PATH = 'application/languages/';

    private function getLanguageName( $code ){
        $result = 'English (?)';

        switch ($code){
            case 'nl':
                $result = 'Nederlands';
                break;

            default:
                $result = 'English';
        }

        return htmlentities($result);
    }
    
    public function getLangs()
    {
        $result = array();

        $all = glob($this->LANG_PATH . "*.[a-z][a-z].php");
        
	foreach ($all as $i => $langf){
            $parts = explode('.', $langf, 3);
	    $result[] = (object) array(
                'id'   => $i,
                'name' => $this->getLanguageName($parts[1]),
                'flag' => $parts[1],
            );
        }
	#echo(var_dump($result));
	#exit();
	
        return $result;
    }
    
}
