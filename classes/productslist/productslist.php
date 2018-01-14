<?
	namespace Vpa;

	define("ID_PRICE_TYPE", 1);
	define("CURRENCY", 'RUB');
	define("ID_SUPPLIER_PROP", 84);
	define("ID_PROP_SUPPLIER_URL", 85);
	define("ID_PROP_NOT_UPDATE_PRICE", 136);


	class ProductsList{


		const CODE_PRODUCT_PROP_URL_SUPPLIER = 'URL_SUPPLIER';

		private $arProducts = array();

		private $idIblock;
		private $count;
		private $arIdSuplier;

		function __construct($idIblock, $count, $arIdSuplier = array())
		{
			if (!is_numeric($idIblock) || !is_numeric($count) || !is_array($arIdSuplier)) {
				throw new \Bitrix\Main\ArgumentTypeException("idIblock or count or idSuplier");
			}

			$this->idIblock = $idIblock;
			$this->count = $count;
			$this->arIdSuplier = $arIdSuplier;

			//создаем объекты продуктов
			$arIdProducts = $this->createObjectsProducts();

			if(is_array($arIdProducts) && sizeof($arIdProducts) > 0)
			{
				//добавляем объектам продуктов цены
				$this->createObjectsPrice($arIdProducts);
			}

		}

		private function createObjectsProducts(){

			$arSelect = Array("ID", "IBLOCK_ID", "TIMESTAMP_X", "PROPERTY_PRICE_NOT_UPDATE", "PROPERTY_URL_SUPPLIER");
			$arFilter = Array("=IBLOCK_ID" => IntVal($this->idIblock), "=ACTIVE_DATE" => "Y", "=ACTIVE" => "Y", "=PROPERTY_".ID_SUPPLIER_PROP => $this->arIdSuplier, '!PROPERTY_'.self::CODE_PRODUCT_PROP_URL_SUPPLIER => false, 'PROPERTY_'.ID_PROP_NOT_UPDATE_PRICE => false);

			$res = \CIBlockElement::GetList(Array("TIMESTAMP_X" => "ASC"), $arFilter, false, Array("nTopCount"=>IntVal($this->count)), $arSelect);

			$arIdProducts = array();
			while ($ob = $res->GetNextElement()) {
				$arFields = $ob->GetFields();
				$arProps = $ob->GetProperties();
				$arIdProducts[] = $arFields['ID'];
				$productProperty = array();
				foreach ($arProps as $prop)
				{
					$productProperty[$prop['ID']] = $prop['VALUE'];
				}
				$this->arProducts[$arFields['ID']] = new Product($arFields['ID'], $productProperty);
			}

			return $arIdProducts;
		}

		private function createObjectsPrice($arIdProducts){
			if (!is_array($arIdProducts)) {
				throw new \Bitrix\Main\ArgumentTypeException("arIdProducts");
			}

			if (count($arIdProducts) > 0){

				$db_res = \Bitrix\Catalog\PriceTable::getList(array(

						'select' => array('ID', 'PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY'),
						"filter" => array('PRODUCT_ID' => $arIdProducts, 'CATALOG_GROUP_ID' => IntVal(ID_PRICE_TYPE)),
					)
				);
				while ($ar_res = $db_res->Fetch()) {
					$product = $this->getProduct($ar_res['PRODUCT_ID']);
					if($product)
					{
						$product->setArPrice($ar_res);
					}
				}
			}


		}
		function getProducts(){
			return $this->arProducts;
		}
		function getProduct($id):Product{
			if (!is_numeric($id)) {
				throw new \Bitrix\Main\ArgumentTypeException("id");
			}

			if(array_key_exists($id, $this->arProducts)){
				return $this->arProducts[$id];
			}
			else{
				return;
			}
		}


	}

?>