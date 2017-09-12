<?php


class Exchange {
	protected $markets;
	protected $updatedmarkets;
	protected $tLastUpdate = 0;

	function __construct() {
		$this->updateMarketList();
		$this->tLastUpdate = 0;
	}

	protected function updateMarketList() {
		if((time() - $this->tLastUpdate) > 10) {
			$this->markets = $this->updatedmarkets;
			$this->updatedmarkets = $this->_updateMarketList();
		}
	}

	protected function _updateMarketList() {}

	public function getNewMarkets() {
		$this->updateMarketList();
		$newmarkets = array_diff_key($this->updatedmarkets, $this->markets);
		return $newmarkets;
	}

	public function getDeletedMarkets() {
		$this->updateMarketList();
		$newmarkets = array_diff_key($this->markets, $this->updatedmarkets);
		return $newmarkets;
	}

}




class BittrexExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://bittrex.com/api/v1.1/public/getmarkets'));
		foreach($markets->result as $market) {
				$result[$market->MarketName] = 1;
		}
		$this->tLastUpdate = time();
		return $result;
	}
}



