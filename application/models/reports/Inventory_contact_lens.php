<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Report.php");

class Inventory_contact_lens extends Report
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
		if($inputs['category'] != '')
		{
			$this->db->where('items.category',$inputs['category']);
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

	/**
	 * returns the array for the dropdown-element item-count in the form for the inventory summary-report
	 * 
	 * @return array
	 */
	public function getCategoryDropdownArray()
	{
		
		// KM NH
		return array(
            'CLEARLAB-USA' => 'CLEARLAB-USA',
            );
		/* KMVH
			return array(
				$this->lang->line('reports_category_seed') => $this->lang->line('reports_category_seed'),
				$this->lang->line('reports_category_bq') => $this->lang->line('reports_category_bq'),
				$this->lang->line('reports_category_akp') => $this->lang->line('reports_category_akp'),
				$this->lang->line('reports_category_ann365') => $this->lang->line('reports_category_ann365'),
				);
		*/
	}
}
?>