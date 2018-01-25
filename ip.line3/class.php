<?
class IpLine3 extends \CBitrixComponent
{
	const LOC_ID = 'VPALAB_IP_CODE';
	const LOC_NAME = 'VPALAB_IP_NAME';
	static $ORDER_PROP_CODE = '';

	public function onPrepareComponentParams($params)
	{
		$params['LOC_ID'] = self::LOC_ID;
		$params['LOC_NAME'] = self::LOC_NAME;

		if (!empty($params['LOC_DEFAULT'])) {
			$params['LOC_DEFAULT'] = trim($params['LOC_DEFAULT']);
		} else {
			$params['LOC_DEFAULT'] = "Москва";
		}

		self::$ORDER_PROP_CODE = ((int)$params['ORDER_PROP_CODE'] > 0) ? (int)$params['ORDER_PROP_CODE'] : null;

		return $params;
	}

	static function getLocIdFromCookie()
	{
		$data = Bitrix\Main\Application::getInstance()
			->getContext()
			->getRequest()
			->getCookie(self::LOC_ID);

		return $data;
	}

	static function getLocNameFromCookie()
	{
		$data = Bitrix\Main\Application::getInstance()
			->getContext()
			->getRequest()
			->getCookie(self::LOC_NAME);

		return $data;
	}

	static function OnSaleComponentOrderPropertiesHandler(&$arUserResult)
	{
		if (self::$ORDER_PROP_CODE) {
			$LocId = self::getLocIdFromCookie();
			if (static::findEmptyPropToOrder($arUserResult) AND $LocId) {
				if ($LocCode = self::getLocCodeToId($LocId)) {
					$arUserResult['ORDER_PROP'][self::$ORDER_PROP_CODE] = $LocCode;
				}
			}
		}

	}

	static function getLocCodeToId($id)
	{
		if ($result = self::getLocationById($id)) {
			return $result['CODE'];
		}
	}

	static function getLocationById($id)
	{
		$parameters = array(
			'select' => array('CODE', 'NAME_CITY' => 'NAME.NAME'),
			'filter' => array("NAME.LANGUAGE_ID" => LANGUAGE_ID, "ID" => $id),
			'limit' => 1,
		);

		$result = \Bitrix\Sale\Location\LocationTable::getList($parameters)->fetch();

		if ($result) {
			return $result;
		} else {
			return false;
		}

	}

	static function findEmptyPropToOrder($arUserResult)
	{

		if (!self::$ORDER_PROP_CODE) {
			return;
		}
		return array_key_exists(self::$ORDER_PROP_CODE, $arUserResult['ORDER_PROP']) AND empty($arUserResult['ORDER_PROP'][self::$ORDER_PROP_CODE]);
	}

	protected function checkModules()
	{
		if (!CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '17.0.9')) {
			throw new \Bitrix\Main\SystemException(\Bitrix\Main\Localization\Loc::getMessage('VPALAB_IP_VERSION_MAIN_MODULE'));
		}

	}

	protected function getLocationDataToCity($locationExt)
	{
		$result = array();
		if ($locationExt) {
			$parameters = array(
				'select' => array('ID', 'NAME.NAME'),
				'filter' => array("NAME.LANGUAGE_ID" => LANGUAGE_ID, 'PHRASE' => $locationExt),
				'limit' => 1,
			);

			$result = \Bitrix\Sale\Location\Search\Finder::find($parameters)->fetch();
		}

		return $result;
	}

	private function GetMoreCity($count = 42)
	{
		$res = \Bitrix\Sale\Location\LocationTable::getList(array(
			'select' => array('ID', 'CITY_NAME' => 'NAME.NAME'),
			'order' => array('SORT' => 'ASC'),
			'filter' => array(
				'=NAME.LANGUAGE_ID' => LANGUAGE_ID,
				'!CITY_ID' => false
			),
			'limit' => $count
		));

		$result = array();

		while ($ar = $res->fetch()) {
			$result[] = $ar;
		}

		return $result;
	}

	public function executeComponent()
	{
		try {
			$this->checkModules();
		} catch (\Bitrix\Main\SystemException $e) {
			ShowError($e->getMessage());
			return;
		}

		$idLoc = self::getLocIdFromCookie();
		$name = self::getLocNameFromCookie();


		if (!$idLoc || !$name) {
			$locNameExt = \Bitrix\Main\Service\GeoIp\Manager::getCityName('', LANGUAGE_ID);
			$LocData = array();
			if ($locNameExt) {
				$LocData = $this->getLocationDataToCity($locNameExt);
			}
			if (sizeof($LocData) == 0) {
				$LocData = $this->getLocationDataToCity($this->arParams['LOC_DEFAULT']);
			}
			if (sizeof($LocData) == 0 || !$LocData['ID'] || !$LocData['NAME']) {
				return;
			}

			$idLoc = $LocData['ID'];
			$name = $LocData['NAME'];
		}

		if (!$idLoc || !$name) {
			return;
		}
		$GLOBALS['VPA_LINE_ID'] = $idLoc;

		$this->arResult['ID'] = $idLoc;
		$this->arResult['NAME'] = $name;


		$moreCity = $this->GetMoreCity();
		$this->arResult['VPALAB_IP_MORE_CITY'] = $moreCity;

		$this->includeComponentTemplate();


		Bitrix\Main\EventManager::getInstance()->addEventHandler("sale", "OnSaleComponentOrderProperties", array("IpLine3", "OnSaleComponentOrderPropertiesHandler"));


	}
}

?>