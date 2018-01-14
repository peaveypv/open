<?
class ProductDelivery extends \CBitrixComponent
{
	function onPrepareComponentParams($arParams)
	{
		$arParams['LOCATION_ID'] = intval($arParams['LOCATION_ID']);
		return $arParams;
	}

	public function executeComponent()
	{

		if(!is_array($this->arParams['ID_DELIVERY']) || sizeof($this->arParams['ID_DELIVERY']) < 1 || !is_array($this->arParams['OFFERS']) || sizeof($this->arParams['OFFERS']) < 1 || !$this->arParams['LOCATION_ID'])
		{
			//если нет ид доставок или предложений или ид местоположения
			return;
		}
		$element_id = 0;

		foreach ($this->arParams['OFFERS'] as $key => $offer) {
			$this->arParams['OFFERS'][$key]['COUNT'] = (int)$offer['COUNT'];
			$this->arParams['OFFERS'][$key]['ID'] = (int)$offer['ID'];
			$element_id = $offer['ID'];
		}

		if(!$element_id)
		{
			//если нет ни одного ид предложения
			return;
		}

		$relativePath = $this->getRelativePath();
		//для каждого элемента вотдельной папке
		$cachePath = "/".SITE_ID.$relativePath."/".$element_id;

		if ($this->StartResultCache('36000000', false, $cachePath)) {

			$parameters = array(
				'select' => array('CODE', 'NAME_CITY' => 'NAME.NAME'),
				'filter' => array("NAME.LANGUAGE_ID" => LANGUAGE_ID, "ID" => $this->arParams['LOCATION_ID']),
				'limit' => 1,
			);
			//ищем имя местоположения по ид
			$result = \Bitrix\Sale\Location\LocationTable::getList($parameters)->fetch();
			if (!$result['NAME_CITY']) {
				$this->AbortResultCache();
				return;
			}

			$this->arResult['LOCATION_NAME'] = $result['NAME_CITY'];

			//создаем корзину
			$basket = Bitrix\Sale\Basket::create(SITE_ID);

			foreach ($this->arParams['OFFERS'] as $offer) {

				//создаем эдемент в корзине
				$item = $basket->createItem('catalog', $offer['ID']);
				$item->setFields(array(
					'QUANTITY' => $offer['COUNT'],
					'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
					'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
					'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
				));

				if ($offer['COUNT'] <> 1)
				{
					//не кешируем если количество товара не 1
					$this->AbortResultCache();
				}
			}

			//создаем заказ
			$order = Bitrix\Sale\Order::create(SITE_ID);
			$order->setBasket($basket);
			$propertyCollection = $order->getPropertyCollection();
			$locVal = CSaleLocation::getLocationCODEbyID($this->arParams['LOCATION_ID']);

			$arProps = array();
			foreach ($propertyCollection as $property) {
				$arProperty = $property->getProperty();
				if ($arProperty["TYPE"] == 'LOCATION')
					$arProps[$arProperty["ID"]] = $locVal;
			}

			$propertyCollection->setValuesFromPost(array('PROPERTIES' => $arProps), array());

			$shipmentCollection = $order->getShipmentCollection();
			$shipment = $shipmentCollection->createItem();

			$shipmentItemCollection = $shipment->getShipmentItemCollection();
			$shipment->setField('CURRENCY', $order->getCurrency());

			foreach ($order->getBasket() as $item) {
				$shipmentItem = $shipmentItemCollection->createItem($item);
				$shipmentItem->setQuantity($item->getQuantity());
			}

			$arShipments = array();

			$arActiveDelivery = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();

			foreach ($this->arParams['ID_DELIVERY'] as $idDelivery) {
				if (key_exists($idDelivery, $arActiveDelivery)) {
					$resCalc = Bitrix\Sale\Delivery\Services\Manager::calculateDeliveryPrice($shipment, $idDelivery);
					if ($resCalc->isSuccess()) {
						$arShipments[$idDelivery]['PRICE'] = $resCalc->getDeliveryPrice();
						$arShipments[$idDelivery]['DISPLAY_PRICE'] = CurrencyFormat($resCalc->getDeliveryPrice(), $arActiveDelivery[$idDelivery]['CURRENCY']);;
						$arShipments[$idDelivery]['NAME'] = $arActiveDelivery[$idDelivery]['NAME'];
						$arShipments[$idDelivery]['DESCRIPTION'] = $arActiveDelivery[$idDelivery]['DESCRIPTION'];
						$arShipments[$idDelivery]['CURRENCY'] = $arActiveDelivery[$idDelivery]['CURRENCY'];
						if ($arActiveDelivery[$idDelivery]['PARENT_ID'] > 0) {
							$arShipments[$idDelivery]['PARENT_NAME'] = $arActiveDelivery[$arActiveDelivery[$idDelivery]['PARENT_ID']]['NAME'];
						}
					}

				}

			}
			//если нет доставок
			if (sizeof($arShipments) < 1) {
				return;
			}
			//наименьшая цена вверху
			usort($arShipments, function ($a, $b) {
				if ($a['PRICE'] == $b['PRICE']) {
					return 0;
				}
				return ($a['PRICE'] < $b['PRICE']) ? -1 : 1;
			});

			$this->arResult['DELIVERY'] = $arShipments;

			$this->setResultCacheKeys(array(""));

			//регистрируем тег для сброса кеша при изменении товара
			if (defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']) && $element_id)
			{
				$GLOBALS['CACHE_MANAGER']->RegisterTag('element_'.$element_id);
			}

			$this->includeComponentTemplate();
		}

	}
}

?>