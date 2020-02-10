<?php
namespace Book24\Import;
use Main\App as App;
use SimpleXMLElement;

class ImportXml {
    private $parse_link = "https://book24.ru/partner/tools/xml-download/";
    private $settings = array();
    
    function __construct( $settings = array() ){
        $this->settings = $settings;
    }
    
    private function getSimpleXmlFromStr($str = '') {
        return new SimpleXMLElement($str);
    }
    
    public function ImportSectionData(\Main\Result &$result, $selectParams = array()) {
        
        $parse_link = $this->parse_link;
        do{
            $arLinkQuery = array(
                'key' => $this->settings['key'],
                'partnerId' => $this->settings['partnerId'],
            );
            
            $arLinkQuery["section-id"]= (int) $selectParams["section_id"];
            if($arLinkQuery["section-id"] <= 0){
                $result->AddError('import Book24: не найден параметр "ID каталога".')->SetStatus(false);
                break;
            }
    
            $objCurl = new \ParseTools\Curl;
            $curlResult = $objCurl->Init($parse_link . '?' . http_build_query($arLinkQuery) );
            
            if(!$curlResult->GetStatus()){
                $result->AddErrors($curlResult->GetErrors())->SetStatus(false);
                break;
            }
            $curlData = $curlResult->GetData();
            
            if($curlData["info"]["http_code"] != 200){
                $result->AddError('import Book24: сервер отдал ошибку '
                    . $curlData["info"]["http_code"] . '.')->SetStatus(false);
                break;
            }
            if(!preg_match('/^text\/xml;/', $curlData["info"]["content_type"])){
                $result->AddError( 'import Book24: сервер отдал данные не в формате xml - content_type "'
                        .$curlData["info"]["content_type"] .'"')->SetStatus(false);
                break;
            }
                        
            $xmlObj = $this->getSimpleXmlFromStr($curlData["answer"]);
            if(!isset($xmlObj->shop)){
                $result->AddError( 'import Book24: ошибка парсинга - не найден объект "shop"')->SetStatus(false);
                break;
            }
            
            if(!isset($xmlObj->shop->categories->category)){
                $result->AddError( 
                    'import Book24: ошибка парсинга - не найден объект "shop->categories->category"')->SetStatus(false);
                break;
            }
            
            $arSectionData = array();
            $arSectionData = array(
                'name' => (string) $xmlObj->shop->categories->category,
                'offers' => []
            );
            if(isset($xmlObj->shop->offers)){
                $xmlOffers = $xmlObj->shop->offers->offer;
                foreach($xmlOffers as $xmlOffer){
                    $arOffer = array(
                        'name' => (string) $xmlOffer->name,
                        'price' => (int) $xmlOffer->price,
                        'image' => (string) $xmlOffer->image,
                        'url' =>  (string) $xmlOffer->url,
                        'authors' => array()
                    );
                    $authors = explode(',', (string) $xmlOffer->author);
                    foreach($authors as $author){
                        $author = trim($author, " \t\n\r\0\x0B.");
                        if($author != ''){
                            $arOffer['authors'][] = $author;
                        }
                    }
                    $arSectionData['offers'][] = $arOffer;
                }
                unset($xmlOffers);
            }
            $arSectionData['offers_count'] = count($arSectionData['offers']);
            
            unset($xmlObj);
            $result->SetData($arSectionData)->SetStatus(true);
            
        }
        while (false);
        
        return $result;
        
    }
    
}