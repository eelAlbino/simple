<?php
namespace Main;

class App {
	
	private static $instance = null;
	private $root_dir = '';
	private $settings = array();
	private $route = NULL;
	
	function __construct(){
	    $classDir = str_replace('\\','/',__DIR__);
	    if(preg_match("/(.*)app\/model\/main$/", $classDir, $matches)){
	        $this->root_dir = $matches[1];
	    }
		$this->setSettings();
		session_start();
	}
	
	public static function getInstance()
	{
		if (null === self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	private function setSettings(){
	    $this->settings = parse_ini_file($this->root_dir.'app/config.cnf', true);
	}
	        
	private function getRoute(){
	    if(null === $this->route ){
	        $this->route = new Route();
	    }
	    return $this->route;
	}
	
	public function LoadPage(){
	    $obRoute = $this->getRoute();
	    $obRoute->Init();
	    
	    $this->Exit();
	}

	public static function Exit(){
	    if (null !== self::$instance)
	    {
	        $selfObj = self::$instance;
    		$selfObj->route = NULL;
    		$selfObj->settings = NULL;
	    }
		exit();
	}
	
	public static function getGroupSettings($groupCode = '' ){
	    $selfObj = self::getInstance();
	    return isset($selfObj->settings[$groupCode]) ? $selfObj->settings[$groupCode] : array();
	}

	public static function getRootDir(){
	    $selfObj = self::getInstance();
	    return $selfObj->root_dir;
	}

	public static function ReturnJsonData($data){
		header('Content-Type: application/json');
		echo json_encode($data);
		self::Exit();
	}
	
}
?>