<?php
namespace Main;

class Route {
	public $route_path = '';
	public $arRoute = '';
	public $arRequest = '';
	

	function __construct(){
	}
	
	private function SetRouteParams(){

		$this->app = App::getInstance();
		$this->route_path = isset($_REQUEST['route']) ? htmlspecialchars($_REQUEST['route']) : 'index';
		$this->arRoute = explode('/', trim($this->route_path, '/'));
		$this->arRequest = $_REQUEST;
	}
	public function GetRoutePath(){
	    return $this->route_path;
	}
	
	public function GetRoute(){
	    return $this->arRoute;
	}
	
	function Init(){
		$result = array('no_find' => true);
		$arRoute = $this->SetRouteParams();
		$arRoute = $this->arRoute;
		$arRequest = $this->arRequest;

		switch ($arRoute[0]){

			case 'test':
			    include App::getRootDir().'/test.php';
			    App::Exit();
				
			default:
                if(Controller::IsHave($arRoute[0])){
                    $controller = new Controller($arRoute, $arRequest);
                    $controller->Init( $result);
                }
                else{
                }
			    
		}
		
		if($result['no_find'] === true){
		    $this->SetNotFoundPage();
		}
	}
	
	private function SetNotFoundPage(){
	    header("HTTP/1.0 404 Not Found");
	}

}
?>