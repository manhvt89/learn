<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Report.php");

class Debit_sales extends Report
{
	function __construct()
	{
        parent::__construct();
        $CI =& get_instance();
        $this->iLoggedIn_Id = $CI->session->userdata('person_id');
        $this->bLoggedIn_type = $CI->session->userdata('type');
	}

	public function create(array $inputs)
	{
		//Create our temp tables to work with the data in our report
		$this->Sale->create_temp_table($inputs);
	}

	public function getDataColumns()
	{
        $CI =& get_instance();
        //$person_id = $this->iLoggedIn_Id;
        if($CI->Employee->has_grant('reports_debit_accounting')) //Phân quyền cho kế toán
        {
            return array(
                'summary' => array(
                    array('id' => $this->lang->line('reports_sale_id')),                    
                    array('customer_name' => 'Tên khách hàng',
                    array('remain_amount' => 'Tổng công nợ', 'sorter' => 'number_sorter'),
                    array('tax' => $this->lang->line('reports_tax'), 'sorter' => 'number_sorter'),
                    array('cost' => $this->lang->line('reports_cost'), 'sorter' => 'number_sorter'),
                    array('profit' => $this->lang->line('reports_profit'), 'sorter' => 'number_sorter'),
                    array('payment_type' => $this->lang->line('sales_amount_tendered')),
                    array('comment' => $this->lang->line('reports_comments'))),
                'details' => array(
                    $this->lang->line('reports_item_number'),
                    $this->lang->line('reports_name'),
                    $this->lang->line('reports_category'),
                    $this->lang->line('reports_quantity'),
                    $this->lang->line('reports_subtotal'),
                    $this->lang->line('reports_tax'),
                    $this->lang->line('reports_total'),
                    $this->lang->line('reports_cost'),
                    $this->lang->line('reports_profit'),
                    $this->lang->line('reports_discount'))
            ));
        }else{
            return array(
                'summary' => array(
                    array('id' => $this->lang->line('reports_sale_id')),
                    array('sale_date' => $this->lang->line('reports_date')),
                    array('quantity' => $this->lang->line('reports_quantity')),
                    array('employee_name' => $this->lang->line('reports_sold_by')),
                    array('customer_name' => $this->lang->line('reports_sold_to')),
                    array('total' => 'Tổng cộng tiền hàng', 'sorter' => 'number_sorter'),
                    array('payment_type' => 'Loại thanh toán'),
                    array('comment' => $this->lang->line('reports_comments'))),
                'details' => array(
                    $this->lang->line('reports_item_number'),
                    $this->lang->line('reports_name'),
                    $this->lang->line('reports_category'),
                    $this->lang->line('reports_quantity'),
                    $this->lang->line('reports_total'),
                    $this->lang->line('reports_discount'))
            );
        }
	}

	public function getDataBySaleId($sale_id)
	{
		$this->db->select('sale_id, sale_date, SUM(quantity_purchased) AS items_purchased, employee_name, customer_name, SUM(subtotal) AS subtotal, SUM(tax) AS tax, SUM(total) AS total, SUM(cost) AS cost, SUM(profit) AS profit, payment_type, comment');
		$this->db->from('sales_items_temp');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get()->row_array();
	}

	public function getData(array $inputs)
	{
		$this->db->select('sale_id, kind, sale_time, sale_date, SUM(quantity_purchased) AS items_purchased, employee_name, customer_name, SUM(subtotal) AS subtotal, SUM(tax) AS tax, SUM(total) AS total, SUM(cost) AS cost, SUM(profit) AS profit, payment_type, comment');
		$this->db->from('sales_items_temp');

		if($inputs['location_id'] != 'all')
		{
			$this->db->where('item_location', $inputs['location_id']);
		}

		if($inputs['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif($inputs['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }

        if(!empty($inputs['code']))
        {
            $this->db->where('sale_id >=', $inputs['code']);
        }

        if($this->bLoggedIn_type == 2)
        {
            $this->db->where('ctv_id=', $this->iLoggedIn_Id);
        }

		$this->db->group_by('sale_id');
		$this->db->order_by('sale_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();

		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('name, category, item_number, quantity_purchased, item_location, serialnumber, description, subtotal, tax, total, cost, profit, discount_percent');
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

		if($inputs['location_id'] != 'all')
		{
		 	$this->db->where('item_location', $inputs['location_id']);
		}

		if($inputs['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif($inputs['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }
        if($this->bLoggedIn_type == 2)
        {
            $this->db->where('ctv_id=', $this->iLoggedIn_Id);
        }

		return $this->db->get()->row_array();
	}

    public function _getDataColumns()
	{
        $CI =& get_instance();
        //$person_id = $this->iLoggedIn_Id;
        if($CI->Employee->has_grant('reports_debit_accounting')) //Phân quyền cho kế toán
        {
            return array(
                'summary' => array(
                    array('id' => $this->lang->line('reports_sale_id')),   
                    array('sale_time' => 'Ngày tháng'),                  
                    array('customer_name' => 'Tên khách hàng'),
                    array('code' => 'Mã đơn hàng'),
                    array('remain_amount' => 'Tổng công nợ', 'sorter' => 'number_sorter','align'=>'right')
                    ),
                'details' => array(
                    $this->lang->line('reports_item_number'),
                    $this->lang->line('reports_name'),                   
                    $this->lang->line('reports_quantity'),
                    $this->lang->line('reports_subtotal'),                   
                    $this->lang->line('reports_total'),
                    $this->lang->line('reports_cost'))
            );
        }else{
            return array(
                'summary' => array(
                    array('id' => $this->lang->line('reports_sale_id')),   
                    array('sale_time' => 'Ngày tháng'),                   
                    array('customer_name' => 'Tên khách hàng'),
                    array('code' => 'Mã đơn hàng'),
                    array('remain_amount' => 'Tổng công nợ', 'sorter' => 'number_sorter','align'=>'right')
                    ),
                'details' => array(
                    array('item_number'=>$this->lang->line('reports_item_number')),
                    array('item_name'=>$this->lang->line('reports_name')),                   
                    array('quantity_purchased'=>$this->lang->line('reports_quantity'),'align'=>'right'),
                    array('item_unit_price'=>'Giá','align'=>'right'),             
                    array('total'=>$this->lang->line('reports_total'),'align'=>'right')
                    
                    )
            );
        }
	}

    public function _getData(string $inputs)
	{
		$this->db->select('
				sales.total_amount,
				sales.remain_amount,
				sales.paid_amount,
                sales.code,
                sales.sale_uuid as sale_uuid,
                sales.sale_time,
				CONCAT(customer_p.last_name, " ", customer_p.first_name) AS customer_name,
				customer.company_name AS company_name,
				customer_p.phone_number AS phone_number,
				sales.sale_id AS sale_id,
				customer.customer_uuid AS uuid'
				);

		$this->db->from('sales AS sales');	
		$this->db->join('people AS customer_p', 'sales.customer_id = customer_p.person_id', 'left');
		$this->db->join('customers AS customer', 'sales.customer_id = customer.person_id', 'left');
		$this->db->where('status', 1); //Chỉ các bản ghi với trạng thái chưa thanh toán; = 0 là đã thanh toán;
		//$this->db->where('DATE(sales.sale_time) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));
		$this->db->where('customer.customer_uuid',$inputs);
		
		$this->db->order_by('sale_id', 'desc');


		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();

		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('*');
			$this->db->from('sales_items');
			$this->db->where('sale_id', $value['sale_id']);
			$data['details'][$key] = $this->db->get()->result_array();
            //$data['details'][$key]=array();
		}

		return $data;
	}

}
?>