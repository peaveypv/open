<?define('STOP_STATISTICS', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
$LOCATION_ID = Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getCookie('VPALAB_IP_CODE');
if(!$LOCATION_ID)
{
	//если нет в куках метоположения то из копмонента через global(((
	$LOCATION_ID = $GLOBALS['VPA_LINE_ID'];
}

$idProduct = Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getPost('idProduct');
$productQuanity = Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getPost('productQuanity');
$OFFERS = array();
if(!$idProduct || !$productQuanity){
	return;
}
$OFFERS = array(array("ID" => $idProduct, "COUNT" => $productQuanity));
?>
<?$APPLICATION->IncludeComponent(
	"vpalab:product.delivery",
	"",
	Array(
		"LOCATION_ID" => $LOCATION_ID,
		"OFFERS" => $OFFERS,
		"ID_DELIVERY" => array(23, 11, 24, 21)
	));
//?>