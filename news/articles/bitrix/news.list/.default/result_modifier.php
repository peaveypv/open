<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$items['NEW_PREVIEW_TEXT'] = "";
$items['NEW_DISPLAY_ACTIVE_FROM'] = "";

$arFilterValue = array();
foreach($arResult['ITEMS'] as &$items){


	//если нет анонса, то текс из детального описания
	$items['NEW_PREVIEW_TEXT'] = $items['PREVIEW_TEXT'];
	if(!$items['NEW_PREVIEW_TEXT']){

		if($arParams['PREVIEW_TRUNCATE_LEN']){
			$items['NEW_PREVIEW_TEXT'] = TruncateText($items['DETAIL_TEXT'], $arParams['PREVIEW_TRUNCATE_LEN']);
		}
		else{
			$items['NEW_PREVIEW_TEXT'] = $items['DETAIL_TEXT'];
		}

	}
	//если нет даты начала активности, то датат создания
	$items['NEW_DISPLAY_ACTIVE_FROM'] = $items['DISPLAY_ACTIVE_FROM'] ? $items['DISPLAY_ACTIVE_FROM'] : CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($items['DATE_CREATE'], CSite::GetDateFormat()));;

	//id свойств для фильтра
	if($arParams['FILTER_PROPS'] && array_key_exists($arParams['FILTER_PROPS'], $items['DISPLAY_PROPERTIES']))
	{
		$arFilterValue = array_merge($arFilterValue, $items['DISPLAY_PROPERTIES'][$arParams['FILTER_PROPS']]['VALUE']);
	}
}
$arFilterValue = array_unique($arFilterValue);
$arFilterDisplay = array();
if($arFilterValue){

	$arSelect = Array("ID", "NAME");
	$arFilter = Array("ID"=>$arFilterValue, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
	while($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arFilterDisplay[$arFields['ID']] = $arFields['NAME'];
	}
}
$arResult['FILTER_DISPLAY'] = $arFilterDisplay;

?>