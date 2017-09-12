<?php
class TGGroconBot {
	private $curl;
	private $tgUrl;
	private $db;
	private $chats = array();

	function __construct($token) {
		$this->curl = curl_init();
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
// 		curl_setopt($this->curl, CURLOPT_VERBOSE, true);

		$this->tgUrl = 'https://api.telegram.org/bot'.urlencode($token).'/';

		$this->db = new PDO('sqlite:groconbot.sqlite');
		$this->db->exec("CREATE TABLE if not exists chats (id INTEGER PRIMARY KEY, username varchar(200))");
	}

	private function postData($url, $data, $contenttype) {
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-type: '.$contenttype));

		return curl_exec($this->curl);
	}

	public function sendTextMessage($recipient, $text) {
// 		echo "\n======== sendTextMessage\n";
		$endpointUrl = $this->tgUrl.'sendMessage';
		$data = new stdClass;
		$data->chat_id = $recipient;
		$data->text = $text;

		$result = $this->postData($endpointUrl, json_encode($data), 'application/json');
		$result = json_decode($result);

		return $result;
	}

	private function getUpdates() {
// 		echo "\n======== getUpdates\n";
		$endpointUrl = $this->tgUrl.'getUpdates';
		$data = new stdClass;
		$data->offset = 0;

		$result = $this->postData($endpointUrl, json_encode($data), 'application/json');
		$result = json_decode($result);

		return $result;
	}

	public function update() {
// 		echo "\n======== update\n";
		$updates = $this->getUpdates();
		foreach($updates->result as $x) {
			$this->chats[$x->message->chat->id] = $x->message->chat->first_name;
			$st = $this->db->prepare('insert into chats(id, username) values(?, ?)');
			$st->execute(array($x->message->chat->id, $x->message->chat->first_name));
		}
	}

	public function broadcast($text) {
		$this->update();
		foreach($this->chats as $chatid=>$user) {
			$this->sendTextMessage($chatid, $text);
		}
	}
}


// $bot = new TGGroconBot(BOT_TGTOKEN);
// $bot->update();



