<?php
//$arRoute = $this->GetRouteData();
$arRequest = $this->GetRequestData();
$objBookImport = new \Book24\Import;

$importResult = false;
if(isset($arRequest["do"])){
    
    switch($arRequest["do"]){
        case "import": 
            $importResult = $objBookImport->ImportSectionData([ "section_id" => $arRequest["form_data"]["section_id"] ]);
            
            break;   
            
    }
}



$arTemplateData = array(
    "title" => "Book24 Импорт"
);

$arTemplateImportForm = $arTemplateData;
$arTemplateImportForm["form_action"] = "";
$arTemplateImportForm["form_data"] = ( isset($arRequest["form_data"]) ? $arRequest["form_data"] : array() );

$arTemplateImportResult = $arTemplateData;

if($importResult){
    $arTemplateImportForm["answer"] = array(
        "success" => $importResult->GetStatus(),
        "errors" => $importResult->GetErrors()
    );
    $arTemplateImportResult["result"] = $importResult->GetData();
}

$this->IncludeTemplate('header', $arTemplateData);

$this->IncludeTemplate('content/book24/import_form', $arTemplateImportForm);
$this->IncludeTemplate('content/book24/import_result', $arTemplateImportResult);

$this->IncludeTemplate('footer', $arTemplateData);
?>