<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);


$arComponentParameters = array(
	"PARAMETERS"  =>  array(
	"LOC_DEFAULT"  =>  Array(
		"PARENT" => "BASE",
		"NAME" => Loc::getMessage('VPALAB_IP_LANE3_CITY_DEFAULT'),
		"TYPE" => "STRING",
		"DEFAULT" => "Москва"),

		"ORDER_PROP_CODE"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage('VPALAB_IP_LANE3_LOCATION_ID_PROPS'),
			"TYPE" => "STRING",
			"DEFAULT" => ""),

	)
);
?>