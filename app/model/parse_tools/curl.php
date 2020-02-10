<?php
namespace ParseTools;
use Main\App as App;

class Curl{
	private $defaultCurlParams = array(
		"CONNECTTIMEOUT" => 60,
	);
	
	
	public function Init($url, $arAddSettings = array()){
	    /*
	     * $arAddSettings = [
	     *     headers = [],
	     *     send_post_data = [],
	     *     request_method = GET/POST,
	     *     ssl_check = boolean,
	     *     connect_timeout = int,
	     *     
	     *     save_cookie = boolean,
	     *     cookie_file = file path,
	     *     
	     *     add_opts = [opt_constant_name => val ]
	     * ]
	     * 
	     */
	    $result = new \Main\Result;
		$url = (string) $url;
		do{
		    if($url == ''){
		        $result->AddError("Url is empty");
		        break;
		    }
		    
	        try{
	            $arHeaders = array();
	            if(isset($arAddSettings["headers"]) && !empty($arAddSettings["headers"])){
	                foreach($arAddSettings["headers"] as $headerStr){
	                    $headerStr = (string) $headerStr;
	                    if($headerStr != ''){
	                        $arHeaders[]= $headerStr;
	                    }
	                }
	            }
	            $sendPostData = false;
	            if(isset($arAddSettings['send_post_data']) && !empty($arAddSettings['send_post_data'])){
	                $sendPostData = $arAddSettings['send_post_data'];
	                //$arHeaders[]='Content-Length: ' . strlen(json_encode($sendData)) ;
	            }
	            
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_URL, $url);
	            if(isset($arAddSettings["request_method"]) && $arAddSettings["request_method"] != ""){
	                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $arAddSettings["request_method"]);
	            }
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, (isset($arAddSettings["ssl_check"]) ? !!$arAddSettings["ssl_check"] : false));
	            if(!empty($arHeaders)){
	                curl_setopt($ch, CURLOPT_HTTPHEADER, $arHeaders);
	            }
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	            
	            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,
	                (isset($arAddSettings["connect_timeout"]) ? $arAddSettings["connect_timeout"]
	                    : $this->defaultCurlParams["CONNECTTIMEOUT"]));
	            //curl_setopt($ch, CURLOPT_REFERER, "https://www.google.com/");
	            if($sendPostData){
	                curl_setopt($ch, CURLOPT_POST, 1);
	                curl_setopt($ch, CURLOPT_POSTFIELDS, $sendPostData);
	            }
	            
	            if(isset($arAddSettings["save_cookie"]) && $arAddSettings["save_cookie"]){
	                $cookie_file = $arAddSettings["cookie_file"] != '' ? $arAddSettings["cookie_file"]
	                : App::getRootDir() . '/include/curl_cookies/' . crc32($url);
	                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	                curl_setopt($ch, CURLOPT_COOKIEFILE,  $cookie_file);
	            }
	            
	            if(!empty($arAddSettings["add_opts"]) && is_array($arAddSettings["add_opts"])){
	                foreach($arAddSettings["add_opts"] as $code =>$val){
	                    if(defined($code)){
	                        curl_setopt($ch, constant($code), $val);
	                    }
	                }
	            }
	            
	            $curlAnswer = curl_exec($ch);
	            
	            $result->SetStatus( ($curlAnswer !== false) );
	            $result->SetData([
	                "answer" => $curlAnswer,
	                "info" => curl_getinfo($ch)
	            ]);
	            unset($curlAnswer);
	            curl_close($ch);
	            
	        }
	        catch (Exception $e) {
	            $result->AddError( $e->getMessage() );
	        }
		}
		while(false);

		return $result;
	}
}
?>