<?php
require_once 'TGGroconBot.php';
require_once 'BittrexExchange.php';
require_once 'config.php';

$bot = new TGGroconBot(BOT_TGTOKEN);
$btex = new BittrexExchange;


while(true) {
	$newmarkets = $btex->getNewMarkets();
	$deletedmarkets = $btex->getDeletedMarkets();

	if(count($newmarkets) || count($deletedmarkets)) {
		echo count($newmarkets)." marchés ajoutés et ".count($deletedmarkets)." supprimés...\n";
		$message = "BITTREX";
		if(count($newmarkets)) {
			$message = "marchés ajoutés : \n";
			foreach($newmarkets as $k=>$v) {
				$message.=$k."\n";
			}
		}
		if(count($deletedmarkets)) {
			$message .= "\nmarchés supprimés : \n";
			foreach($deletedmarkets as $k=>$v) {
				$message.=$k."\n";
			}
		}
		$bot->broadcast($message);
	}
	sleep(30);
}


