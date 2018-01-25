<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "E")))
	{
		$arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}

$arTemplateParameters = array(
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"FILTER_PROPS" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_FILTER_PROPS"),
		"TYPE" => "LIST",
		"VALUES" => $arProperty_LNS
	),
	"USE_RSS" => Array(
		"HIDDEN" => 'Y',
	),
	"USE_RATING" => Array(
		"HIDDEN" => 'Y',
	),
	"USE_FILTER" => Array(
		"HIDDEN" => 'N',
	),
	"USE_SEARCH" => Array(
		"HIDDEN" => 'Y',
	),
	"CACHE_FILTER" => Array(
		"HIDDEN" => 'Y',
	),


);



?>