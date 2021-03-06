<?php
include_once(SITE_ROOT.'/core/DBColumn.php');
include_once(SITE_ROOT.'/core/DBColumns.php');

class OrderDetail extends DBRow {
	function createTable() {
		$cols = array(
			'id?',
			DBColumn::make('integer', 'order_nb', 'Order'),
			DBColumn::make('integer', 'product', 'Product'),
			DBColumn::make('text', 'product_name', 'Product Name'),
			DBColumn::make('integer', 'quantity', 'Quantity'),
		);
		return new DBTable("ecomm_order_detail", __CLASS__, $cols);
	}
	
	public static function getAll($orderId=0){
		$sql = 'select `id` from ecomm_order_detail';
		if ($orderId)
			$sql .= " where order_nb = '" . e($orderId) . "'";
		$sql .= " order by id";
		$results = Database::singleton()->query_fetch_all($sql);
		
		foreach ($results as &$result) {
			$result = new OrderDetail($result['id']);
		}
		return $results;
	}
	
	public function getProductPluginInfo(){
		require_once SITE_ROOT . '/modules/EComm/plugins/products/ECommProduct.php';
		$hookResults = ECommProduct::adminPluginHooks("BeforeDisplayOrder", $this);
		//var_dump($hookResults);exit;
		return $hookResults;
	}
	
	static function getQuickFormPrefix() {return 'orderdetail_';}
}
DBRow::init('OrderDetail');
?>