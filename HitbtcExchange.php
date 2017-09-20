<?php
require_once('Exchange.php');

class HitbtcExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://api.hitbtc.com/api/1/public/ticker'));
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
		return 'HITBTC';
	}
}

// $ex = new HitbtcExchange;
// $newmarkets = $ex->getNewMarkets();
// $deletedmarkets = $ex->getDeletedMarkets();
//
// print_r($newmarkets);
// print_r($deletedmarkets);
//
//
