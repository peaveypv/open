<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	"NAME" => Loc::getMessage("VPALAB_IP3_LINE_NAME"),
	"DESCRIPTION" => Loc::getMessage("VPALAB_IP3_LINE_NAME_DESC"),
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "vpalab",
		"NAME" => Loc::getMessage('VPALAB_IP3_LINE_NAME_GROUP'),
		"SORT" => 10,

	),
);
?>