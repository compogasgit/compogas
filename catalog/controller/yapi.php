<?php
class ControllerYapi extends Controller {

	public function index() {
		$res = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'ya_market_pl_addon_productmanager_status' AND 
			`code` = 'ya_market_pl_addon_productmanager_status'");

		if ($res) {
			echo 'правки внесены, файл удалиться автоматически';
		}
		else {
			echo 'какая то ошибка';
		}

		@unlink(__FILE__);
	}
}