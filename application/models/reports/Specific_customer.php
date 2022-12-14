<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Report.php");

class Specific_customer extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function create(array $inputs)
	{
		//Create our temp tables to work with the data in our report
		$this->Sale->create_temp_table($inputs);
	}
	
	public function getDataColumns()
	{
        $CI =& get_instance();
        $person_id = $CI->session->userdata('person_id');
        if($CI->Employee->has_grant('reports_sales-accounting', $person_id)) {
            return array(
                'summary' => array(
                    array('id' => $this->lang->line('reports_sale_id')),
                    array('sale_date' => $this->lang->line('reports_date')),
                    array('quantity' => $this->lang->line('reports_quantity')),
                    array('sold_by' => $this->lang->line('reports_sold_by')),
                    array('subtotal' => $this->lang->line('reports_subtotal'), 'sorter' => 'number_sorter'),
                    array('tax' => $this->lang->line('reports_tax'), 'sorter' => 'number_sorter'),
                    array('total' => $this->lang->line('reports_total'), 'sorter' => 'number_sorter'),
                    array('cost' => $this->lang->line('reports_cost'), 'sorter' => 'number_sorter'),
                    array('profit' => $this->lang->line('reports_profit'), 'sorter' => 'number_sorter'),
                    array('payment_type' => $this->lang->line('reports_payment_type')),
                    array('comments' => $this->lang->line('reports_comments'))),
                'details' => array(
                    $this->lang->line('reports_name'),
                    $this->lang->line('reports_category'),
                    $this->lang->line('reports_serial_number'),
                    $this->lang->line('reports_description'),
                    $this->lang->line('reports_quantity'),
                    $this->lang->line('reports_subtotal'),
                    $this->lang->line('reports_tax'),
                    $this->lang->line('reports_total'),
                    $this->lang->line('reports_cost'),
                    $this->lang->line('reports_profit'),
                    $this->lang->line('reports_discount'))
            );
        }else{
            return array(
                'summary' => array(
                    array('id' => $this->lang->line('reports_sale_id')),
                    array('sale_date' => $this->lang->line('reports_date')),
                    array('quantity' => $this->lang->line('reports_quantity')),
                    array('sold_by' => $this->lang->line('reports_sold_by')),
                    array('total' => $this->lang->line('reports_total'), 'sorter' => 'number_sorter'),
                    array('payment_type' => $this->lang->line('reports_payment_type')),
                    array('comments' => $this->lang->line('reports_comments'))),
                'details' => array(
                    $this->lang->line('reports_name'),
                    $this->lang->line('reports_category'),
                    $this->lang->line('reports_serial_number'),
                    $this->lang->line('reports_description'),
                    $this->lang->line('reports_quantity'),
                    $this->lang->line('reports_total'),
                    $this->lang->line('reports_discount'))
            );
        }
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('sale_id, sale_date, SUM(quantity_purchased) AS items_purchased, employee_name, SUM(subtotal) AS subtotal, SUM(tax) AS tax, SUM(total) AS total, SUM(cost) AS cost, SUM(profit) AS profit, payment_type, comment');
		$this->db->from('sales_items_temp');
		$this->db->where('customer_id', $inputs['customer_id']);

		if ($inputs['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif ($inputs['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }

		$this->db->group_by('sale_id');
		$this->db->order_by('sale_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('name, category, serialnumber, description, quantity_purchased, subtotal, tax, total, cost, profit, discount_percent');
			$this->db->from('sales_items_temp');
			$this->db->where('sale_id', $value['sale_id']);
			$data['details'][$key] = $this->db->get()->result_array();
		}

		return $data;
	}
	
	public function getSummaryData(array $inputs)
	{
        $CI =& get_instance();
        $person_id = $CI->session->userdata('person_id');
        if($CI->Employee->has_grant('reports_sales-accounting', $person_id)) {
            $this->db->select('SUM(subtotal) AS subtotal, SUM(tax) AS tax, SUM(total) AS total, SUM(cost) AS cost, SUM(profit) AS profit');
        }else{
            $this->db->select('SUM(total) AS total');
        }
		$this->db->from('sales_items_temp');
		$this->db->where('customer_id', $inputs['customer_id']);

		if ($inputs['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif ($inputs['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }

		return $this->db->get()->row_array();
	}
}
?>