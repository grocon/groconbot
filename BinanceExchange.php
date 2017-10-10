<?php
require_once('Exchange.php');

class BinanceExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://www.binance.com/api/v1/ticker/allPrices'));
		if(!is_array($markets)) return false;
		if(count($markets) == 0) return false;
		foreach($markets as $market) {
				$result[$market->symbol] = 1;
		}
		$this->tLastUpdate = time();
		return $result;
	}

	public function getName() {
		return 'BINANCE';
	}
}


// $ex = new BinanceExchange;
// $newmarkets = $ex->getNewMarkets();
// $deletedmarkets = $ex->getDeletedMarkets();
//
// print_r($newmarkets);
// print_r($deletedmarkets);

