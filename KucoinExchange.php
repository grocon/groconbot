<?php
require_once('Exchange.php');

class KucoinExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://api.kucoin.com/v1/open/tick'));
		if(!is_array($markets->data)) return false;
		if(count($markets) == 0) return false;
		foreach($markets->data as $market) {
			if($market->trading) {
				$result[$market->symbol] = 1;
			}
			else {
				$result[$market->symbol.' (desactivé)'] = 1;
			}
		}
		$this->tLastUpdate = time();
		print_r($result);
	}

	public function getName() {
		return 'BINANCE';
	}
}


// $ex = new KucoinExchange;
// $newmarkets = $ex->getNewMarkets();
// $deletedmarkets = $ex->getDeletedMarkets();
//
// print_r($newmarkets);
// print_r($deletedmarkets);
//
