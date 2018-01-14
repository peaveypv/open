<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<? if($arResult['DELIVERY']):?>
	<div><b><?= Loc::getMessage('VPALAB_DELIVERY_PRODUCT');?><span class="vpalab_ip_line_city_name">: <?$frame = $this->createFrame()->begin('');?><?=$arResult['LOCATION_NAME']?><?$frame->end()?></span></b></div>
	<?$frame = $this->createFrame()->begin('');?>
	<table class="vpalab_product_delivery">


	<?foreach ($arResult['DELIVERY'] as $delivery):?>
<tr>
<td class="vpalab_product_name"><?if($delivery['PARENT_NAME']):?><?=$delivery['PARENT_NAME']?> (<?=$delivery['NAME']?>)<?else:?><?=$delivery['NAME']?><?endif;?>:</td>
<td class="vpalab_product_price"><b><?=$delivery['DISPLAY_PRICE']?></b></td>
</tr>
<?
endforeach;

?>
</table>
	<script>
		$( ".vpalab_ip_line_city_name" ).click(function() {
			cityQuestionSet();
			openCityBox();
		});
	</script>
	<?$frame->end()?>
<?endif;?>
<script>
	function getDeliveryProducts(obj)
	{
		var url = '<?=$componentPath.DIRECTORY_SEPARATOR.'ajax.php'?>';
		getDeliveryProductsFunc(obj, url);
	}
</script>