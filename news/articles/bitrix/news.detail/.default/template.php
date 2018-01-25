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
<div class="single">
	<p class="single-header"><span class="single-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span></p>
	<div class="single-main">
		<p><?echo $arResult["DETAIL_TEXT"];?></p>
	</div>
</div>
