<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent(
	"bitrix:menu.sections", 
	"", 
	array(
		"IS_SEF" => "Y",
		"SEF_BASE_URL" => "/catalog/",
		"SECTION_PAGE_URL" => "#SECTION_CODE_PATH#/",
//		"DETAIL_PAGE_URL" => "#SECTION_CODE_PATH##ELEMENT_CODE#-#ELEMENT_ID#",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => IB_PROD,
		"DEPTH_LEVEL" => "3",
		"CACHE_TYPE" => "Y",
		"CACHE_TIME" => "36000000"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);



$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>