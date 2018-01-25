<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="article">
	<?if($arResult['FILTER_DISPLAY']):?>
	<div class="article-filter">
		<?foreach($arResult['FILTER_DISPLAY'] as $idValue => $value):?>
			<div><a href="<?=$APPLICATION->GetCurPageParam("filter=$idValue", array("filter"));?>"><?=$value?></a></div>
		<?endforeach;?>
	</div>
		<div><a href="<?=$APPLICATION->GetCurPageParam("", array("filter"));?>"><?=GetMessage('CT_BNL_FILTER_ALL_ELEMENTS')?></a></div>
	<?endif;?>
	<div class="article-shell">
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="article-cell">
			<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="article-title"><?=$arItem["NAME"]?></a>
			<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" class="article-img">
			<div class="article-block">
				<p class="article-text"><?echo $arItem["NEW_PREVIEW_TEXT"];?></p>
				<p class="article-footer"><span class="article-date"><?echo $arItem["NEW_DISPLAY_ACTIVE_FROM"]?></span></p>
			</div>
		</div>
		<?endforeach;?>
	</div>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
</div>