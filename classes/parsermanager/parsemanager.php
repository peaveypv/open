<?
namespace Vpa;

class ParseManager{

	const LOG_FILE_NAME = '/local/php_interface/log/logPriceUpdate.html';
	private $productList = array();
	private $arParser = array();
	private $httpClient;
	private $log;

	//функция для запуска с агента
	static function init(){

			//создаем список товаров
			$productList = new \Vpa\ProductsList(10, 10, array(16));
			$arProducts = $productList->getProducts();

			//создаем менеджер парсеров
			$parseManager = new \Vpa\ParseManager($arProducts);

			//добавляем в менеджер парсер на 16 того поставщика
			$parseManager->addParser(16, function($product, $status, $page) use ($parseManager){

				//нынешняя цена
				$oldPrice = $product->getPrice();
				$newPrice = 0;
				$parseData = array();

				if($status == 200)
				{
					//парс простой цены
					preg_match('/<table.*Цена.*<span.*>([0-9.]*)<\/span>.*<\/table>/U',$page,$matchesPrice);
					//парс специальной цены
					preg_match('/<table.*Спеццена.*<span.*>([0-9.]*)<\/span>.*<\/table>/U',$page,$matchesSpecPrice);

					$specPrice = 0;
					$price = 0;

					if(count($matchesSpecPrice) > 0)
					{
						$specPrice = floatval($matchesSpecPrice[1]);
					}
					if(count($matchesPrice) > 0)
					{
						$price = floatval($matchesPrice[1]);
					}

					//если необходимо сохранять специальную
					if($product->getProperty(226) != 'Y' && $specPrice)
					{
						$newPrice = $specPrice;
					}
					elseif($price)
					{
						$newPrice = $price;
					}
					else{
						$newPrice = $oldPrice;
					}

					//для лога
					$parseData['SpecPrice'] = $specPrice;
					$parseData['Price'] = $price;
					$parseData['OldPrice'] = $oldPrice;

				}
				//устанавливаем цену
				$product->setPrice($newPrice);
				//сохраняем товар
				$product->save();

				//запись в лог
				$idProduct = $product->getId();
				$urlProductSupplier = $product->getSupplierUrl();
				$parseManager->logParseProduct($idProduct, $urlProductSupplier, $status, $parseData);


			});

			//запуск парса
			$parseManager->parse();



		//возврат для агента
		return "\\Vpa\\ParseManager::init();";
	}

	function __construct(array $productList)
	{
		if(is_array($productList))
		{
			$this->productList = $productList;
		}
		//инстанс лога
		$this->log = new \Bitrix\Main\IO\File(\Bitrix\Main\Application::getDocumentRoot() . self::LOG_FILE_NAME);
		//инстанс http клиента
		$this->httpClient = new \Bitrix\Main\Web\HttpClient();
	}

	function parse(){

		foreach($this->productList as $product)
		{
			$idSupplier = $product->getSupplierId();
			//если есть парсер для поставщика
			if(array_key_exists($idSupplier, $this->arParser)){

				$url = $product->getSupplierUrl();
				$httpRes = $this->httpClient->get($url);
				$status = $this->httpClient->getStatus();

				//вызываем лямду
				$this->arParser[$idSupplier]($product, $status, $httpRes);

			}
		}
	}

	function addParser($idSupplier, $parser){
		$this->arParser[$idSupplier] = $parser;
	}

	function logParseProduct($id, $url, $status, array $parseData){
		if (!is_numeric($id) || !$url || !is_numeric($status) || !is_array($parseData)) {
			throw new \Bitrix\Main\ArgumentTypeException("id or url or status");
		}
		$dateNow = ConvertTimeStamp(false, 'FULL');
		$logData = "$dateNow $id  <a href='$url' target='_blank'>Url supplier</a>  <b>$status</b><br>";
		foreach($parseData as $key => $data)
		{
			$logData .= "$key: $data<br>";
		}
		$this->addLog($logData);
	}

	private function addLog($data){
		if (!$data) {
			throw new \Bitrix\Main\ArgumentTypeException("data");
		}

		$this->log->putContents($data, \Bitrix\Main\IO\File::APPEND);
	}
}
?>