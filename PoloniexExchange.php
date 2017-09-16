<?php
require_once('Exchange.php');

class PoloniexExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://poloniex.com/public?command=returnTicker'));
		if(!is_object($markets)) return false;
		if(count($markets) == 0) return false;
		foreach($markets as $market=>$v) {
// 			if(rand(0, 10) != 1)
			$result[$market] = 1;
		}
		$this->tLastUpdate = time();
		return $result;
	}

	public function  getName() {
		return 'POLONIEX';
	}
}

// $ex = new PoloniexExchange;
// $newmarkets = $ex->getNewMarkets();
// $deletedmarkets = $ex->getDeletedMarkets();
//
// print_r($newmarkets);
// print_r($deletedmarkets);
