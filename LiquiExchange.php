<?php
require_once('Exchange.php');

class LiquiExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://api.liqui.io/api/3/info'));
// 		print_r($markets->pairs);
		if(!is_object($markets->pairs)) return false;
		if(count($markets->pairs) == 0) return false;
		foreach($markets->pairs as $market=>$v) {
			if($v->hidden) continue;
// 			if(rand(0, 10) != 1)
			$result[$market] = 1;
		}
		$this->tLastUpdate = time();

		return $result;
	}

	public function  getName() {
		return 'LIQUI';
	}
}

// $ex = new LiquiExchange;
// $newmarkets = $ex->getNewMarkets();
// $deletedmarkets = $ex->getDeletedMarkets();
//
// print_r($newmarkets);
// print_r($deletedmarkets);
