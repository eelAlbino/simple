<?php
namespace Main;

class Controller {
    private $template = NULL;
    private $arRoute = array();
    private $arRequest = array();
    protected static $controller_dir = 'app/controller/';
    public $site_dir = '';
    	

    function __construct($arRoute = array(), $arRequest = array()){
        $this->arRoute = $arRoute;
        $this->arRequest = $arRequest;
	    $this->site_dir = str_replace($_SERVER["DOCUMENT_ROOT"], '/', App::getRootDir());
	}
	
	public function Init( &$result = array()){
	    $cPathScript = self::GetControllerDir() . $this->arRoute[0] . '.php';
	    if( file_exists($cPathScript) ){
	        $result['no_find'] = false;
	        include $cPathScript;
	        
	    }
	}
	
	public function GetRequestData(){
	    return $this->arRequest;
	}
	
	public function GetRouteData(){
	    return $this->arRoute;
	}
	
	public static function GetControllerDir(){
	   return App::getRootDir() . self::$controller_dir;
	}
	
	public static function IsHave(string $code = ''){	    
	    return ($code != '' && file_exists(self::GetControllerDir() . $code . '.php') );
	}
	
	
	private function getTemplateObj(){
	    if(null === $this->template ){
	        $this->template = new Template();
	    }
	    return $this->template;
	}
	
	public function IncludeTemplate($file_path = '', $data = array()){
	    $data['site_dir'] = $this->site_dir;
	    $objTemplate = $this->getTemplateObj();
	    $objTemplate->IncludeTemplate($file_path, $data, $this);
	}
}
?>