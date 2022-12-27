<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Report.php");

class Inventory_lens extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		return array(array('item_name' => $this->lang->line('reports_item_name')),
					array('item_number' => $this->lang->line('reports_item_number')),
					array('quantity' => $this->lang->line('reports_quantity')),
					array('reorder_level' => $this->lang->line('reports_reorder_level')),
					array('location_name' => $this->lang->line('reports_stock_location')),
					array('cost_price' => $this->lang->line('reports_cost_price'), 'sorter' => 'number_sorter'),
					array('unit_price' => $this->lang->line('reports_unit_price'), 'sorter' => 'number_sorter'),
					array('subtotal' => $this->lang->line('reports_sub_total_value'), 'sorter' => 'number_sorter'));
	}

	public function getData(array $inputs)
	{	
        $this->db->select('items.name,items.standard_amount, items.item_number, item_quantities.quantity, items.reorder_level, stock_locations.location_name, items.cost_price, items.unit_price, (items.cost_price * item_quantities.quantity) AS sub_total_value');
        $this->db->from('items AS items');
        $this->db->join('item_quantities AS item_quantities', 'items.item_id = item_quantities.item_id');
        $this->db->join('stock_locations AS stock_locations', 'item_quantities.location_id = stock_locations.location_id');
        $this->db->where('items.deleted', 0);
        $this->db->where('stock_locations.deleted', 0);

		// should be corresponding to values Inventory_summary::getItemCountDropdownArray() returns...
		$categories = $this->config->item('iKindOfLens');
		if($inputs['category'] != '')
		{
			$this->db->where('items.category',$categories[$inputs['category']]);
		}

		if($inputs['location_id'] != 'all')
		{
			$this->db->where('stock_locations.location_id', $inputs['location_id']);
		}

        $this->db->order_by('items.name');

		return $this->db->get()->result_array();
	}

	/**
	 * calculates the total value of the given inventory summary by summing all sub_total_values (see Inventory_summary::getData())
	 * 
	 * @param array $inputs expects the reports-data-array which Inventory_summary::getData() returns
	 * @return array
	 */
	public function getSummaryData(array $inputs)
	{
		$return = array('total_inventory_value' => 0);

		foreach($inputs as $input)
		{
			$return['total_inventory_value'] += $input['sub_total_value'];
		}

		return $return;
	}

	public function _getData(array $inputs)
	{	
        $filter = $this->config->item('filter_lens'); //define in app.php
	    $this->db->select('items.category, SUM(item_quantities.quantity) AS quantity, stock_locations.location_id');
        $this->db->from('items AS items');
        $this->db->join('item_quantities AS item_quantities', 'items.item_id = item_quantities.item_id');
        $this->db->join('stock_locations AS stock_locations', 'item_quantities.location_id = stock_locations.location_id');
        $this->db->where('items.deleted', 0);
        $this->db->where('stock_locations.deleted', 0);
        $this->db->where_in('items.category', $filter);

		// should be corresponding to values Inventory_summary::getItemCountDropdownArray() returns...

		if($inputs['location_id'] != 'all')
		{
			$this->db->where('stock_locations.location_id', $inputs['location_id']);
		}
        $this->db->group_by('items.category');
        $this->db->order_by('items.category');

        $data = array();
		
		$tmp = $this->db->get()->result_array();

		$sales = $this->_getSalesToday();
		if(empty($sales))
		{

			foreach($tmp as $k=>$v)
			{
				$v['sale_quantity'] = 0;
				$data['summary'][] = $v;
			}
			
		} else {
			$_sales = array();
			foreach($sales as $k=>$v)
			{
				$_sales[$v['item_category']] = $v['quantity'];
			}
			foreach($tmp as $k=>$v)
			{
				if(isset($_sales[$v['category']]))
				{
					$v['sale_quantity'] = $_sales[$v['category']];
				} else{
					$v['sale_quantity'] = 0;
				}
				$data['summary'][] = $v;
			}
		}
        //$data['summary'] = $this->db->get()->result_array();
		//$data['summary'] = $tmp;
		//var_dump($data);
        return $data;

	}

	public function _getSalesToday()
	{
		$filter = $this->config->item('filter_lens'); //define in app.php
		$this->db->select('s.sale_time, SUM(si.quantity_purchased) AS quantity, si.item_category');
        $this->db->from('sales_items AS si');
        $this->db->join('sales AS s', 'si.sale_id = s.sale_id');
        $this->db->where_in('si.item_category', $filter);
		$this->db->where('DATE(s.sale_time)=CURDATE()');
        $this->db->group_by('si.item_category');
        $this->db->order_by('si.item_category');
        $data = array();
        $data = $this->db->get()->result_array();
        return $data;
	}

	public function _getDataColumns()
	{
		return array(

			'summary' => array(
				array('id' => $this->lang->line('reports_sale_id')),
				array('cat' => 'Loại mắt'),
				array('quantity' => $this->lang->line('reports_quantity')),
				array('sale_quantity'=>'Số lượng đã bán'),
			)
		);
	}


	/**
	 * returns the array for the dropdown-element item-count in the form for the inventory summary-report
	 * 
	 * @return array
	 */
	public function getCategoryDropdownArray()
	{
		return $this->config->item('iKindOfLens');
	}
}
?>