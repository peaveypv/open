<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//$this->setFrameMode(true);
use Bitrix\Main\Localization\Loc;
?>
<?CJSCore::Init(array("jquery"));?>
	<? Loc::loadMessages(__FILE__);?>
	<div id="vpalab_ip_line_main_box"class="vpalab_ip_line_main_box">
	<div class="vpalab_ip_line_default_box">

		<span class="city_title_box"><?= Loc::getMessage("VPALAB_IP_ARE_YOU_CITY");?></span>
		<span class="city_box">

		<span class="vpalab_ip_line_city_name" id="vpalab_ip_line_city_name"><?$frame = $this->createFrame()->begin('')?><?=$arResult['NAME'];?><?$frame->end()?></span>

		<div class="city_question_box">
			<div class="city_question_title_box">
				<?= Loc::getMessage('VPALAB_IP_CITY_QUESTION'); ?>
				<br/><span id="vpalab_ip_line_epilog_city_name"class="vpalab_ip_line_epilog_city_name"><?$frame = $this->createFrame()->begin('')?><?=$arResult['NAME'];?><?$frame->end()?></span>?
			</div>
			<div class="btn_box">
				<div class="btn_no"><?= Loc::getMessage('VPALAB_IP_CITY_QUESTION_NO'); ?></div>
				<div class="btn_yes"><?= Loc::getMessage('VPALAB_IP_CITY_QUESTION_YES'); ?></div>
				<div class="clearfix"></div>
			</div>
		</div>

	</span>
	</div>
	<div class="vpalab_ip_line_default-box">
		<div class="vpalab_ip_line_city_change_box">
			<div class="bg_box"></div>
			<div class="main_box">
				<div class="btn_close">&times;</div>
				<div class="header">
					<?=Loc::getMessage("VPALAB_IP_LINE_HEADER");?>
				</div>
				<div class="city_search_box">
					<?$APPLICATION->IncludeComponent(
						"bitrix:sale.location.selector.search",
						"template1",
						Array(
							"CACHE_TIME" => "36000000",
							"CACHE_TYPE" => "A",
							"CODE" => $arResult['ID'],
							"COMPONENT_TEMPLATE" => ".default",
							"FILTER_BY_SITE" => "N",
							"ID" => "",
							"INITIALIZE_BY_GLOBAL_EVENT" => "",
							"INPUT_NAME" => "LOCATION",
							"JS_CALLBACK" => "vpaIpCallback();",
							"JS_CONTROL_GLOBAL_ID" => "",
							"PROVIDE_LINK_BY" => "id",
							"SHOW_DEFAULT_LOCATIONS" => "N",
							"SUPPRESS_ERRORS" => "N"
						)
					);?>
					<div class="search_options_box">
						<?
						$iColRows = ceil(count($arResult['VPALAB_IP_MORE_CITY']) / 3);
						?>
						<div class="co4">
							<?
							$i = -1;
							foreach ($arResult['VPALAB_IP_MORE_CITY'] as $item) {
								if (++$i > 0 && $i % $iColRows == 0) {
									echo '</div><div class="co4 ">';
								}
								echo "<div class='item' name='".$item['CITY_NAME']."' id='".$item['ID']."'><span>".$item['CITY_NAME']."</span></div>";
							}
							?>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>

	</div>
	</div>

	<?$frame = $this->createFrame()->begin('')?>
<script>SetcityCookie('<?=$arResult['ID'];?>', '<?=$arResult['NAME'];?>');
	if(!cityQuestionGet())
	{
		$('.vpalab_ip_line_default_box .city_box .city_question_box').css("display", "block");
	}
</script>
	<?$frame->end();?>
