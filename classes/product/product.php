<?
namespace Vpa;

class Product{
	private $id = null;
	private $price = array();
	private $property = array();

	function __construct($id, $arProperty = array())
	{
		if (!is_numeric($id) || !is_array($arProperty)) {
			throw new \Bitrix\Main\ArgumentTypeException("id or arProperty");
		}
		$this->id = $id;
		$this->property = $arProperty;
	}

	function setArPrice($price){
		if (!is_array($price)) {
			throw new \Bitrix\Main\ArgumentTypeException("price");
		}
		$this->price = $price;
	}

	function setPrice($price){
		if (!is_numeric($price)) {
			throw new \Bitrix\Main\ArgumentTypeException("price");
		}
		$this->price['PRICE'] = $price;
	}

	function getPrice(){
		if(array_key_exists('PRICE', $this->price))
		{
			return $this->price['PRICE'];
		}
		else{
			return 0;
		}
	}

	function getArPrice(){
		return $this->price;
	}

	function getId(){
		return $this->id;
	}

	function getProperty($id){
		if (!is_numeric($id)) {
			throw new \Bitrix\Main\ArgumentTypeException("id");
		}
		if(array_key_exists($id, $this->property))
		{
			return $this->property[$id];
		}
		return false;
	}

	function getSupplierId(){
		return $this->getProperty(ID_SUPPLIER_PROP);
	}

	function getSupplierURL(){

		return $this->getProperty(ID_PROP_SUPPLIER_URL);
	}

//		function getProperties(){
//			return $this->property;
//		}

	function save(){
		$this->updateTimestampProduct();
		$this->savePrice();

	}

	private function savePrice(){

		if(is_array($this->price) && $this->price['ID']){
			$db_res = \Bitrix\Catalog\PriceTable::update($this->price['ID'], array('PRICE' => floatval($this->price['PRICE'])));
			if (!$db_res->isSuccess())
			{
				throw new \Bitrix\Main\DB\SqlException("updatePrice");
			}
		}
		else{
			if(!is_numeric($this->id) || !is_numeric(ID_PRICE_TYPE) || !is_numeric($this->price['PRICE']) || !CURRENCY){
				throw new \Bitrix\Main\ArgumentTypeException("parameters addPrice");
			}

			$db_res = \Bitrix\Catalog\PriceTable::add(array(
				'PRODUCT_ID' => $this->id,
				'CATALOG_GROUP_ID' => ID_PRICE_TYPE,
				'PRICE' => floatval($this->price['PRICE']),
				'CURRENCY' => CURRENCY,
				'PRICE_SCALE' => floatval($this->price['PRICE'])

			));
			if (!$db_res->isSuccess())
			{
				//$errors = $db_res->getErrorMessages();
				throw new \Bitrix\Main\DB\SqlException("addPrice");
			}
			else{
				$this->price['ID'] = $db_res->getId();
			}
		}

	}

	function updateTimestampProduct(){
		if (!is_numeric($this->id)) {
			throw new \Bitrix\Main\ArgumentTypeException("id");
		}

		$el = new \CIBlockElement;
		$el->Update($this->id, array());
	}
}
?>