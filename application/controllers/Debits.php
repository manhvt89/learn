<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Debits extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('sales');

		$this->load->library('sale_lib');
		$this->load->library('barcode_lib');
		$this->load->library('email_lib');
		$this->load->library('ciqrcode'); // Load QR Code library
		$this->config->load('qrcode'); // Load QR code config file;
		$this->logedUser_type = $this->session->userdata('type');
		$this->logedUser_id = $this->session->userdata('person_id');
	}

	public function index()
	{
		
	}
	
	public function manage()
	{
		
		
		
	}
	
	public function get_row($row_id=0)
	{
		if($row_id == 0)
		{
			echo 'Invalid Data';
			exit();
		}
		$sale_info = $this->Sale->get_info($row_id)->row();
		if($sale_info == null)
		{
			echo 'Not Found a Record';
			exit();
		}
		$data_row = $this->xss_clean(get_sale_data_row($sale_info));
		echo json_encode($data_row);
	}

	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');

		$filters = array('sale_type' => 'all',
						'location_id' => 'all',
						'start_date' => $this->input->get('start_date'),
						'end_date' => $this->input->get('end_date'),
						'only_cash' => FALSE,
						'debit'=>FALSE, //added 03.02.2023 - manhvt
						'only_invoices' => $this->config->item('invoice_enable') && $this->input->get('only_invoices'),
						'is_valid_receipt' => $this->Sale->is_valid_receipt($search));

		// check if any filter is set in the multiselect dropdown
		if($this->input->get('filters') == null)
		{
			echo 'Invalid Data';
			exit();
		}
		//var_dump($this->input->get('filters'));
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//var_dump($filledup);
		//die();
		$filters = array_merge($filters, $filledup);

		$sales = $this->Sale->search_debits($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Sale->get_found_debit_rows($search, $filters);
		//$payments = $this->Sale->get_payments_summary($search, $filters, $this->logedUser_type, $this->logedUser_id);
		//$payment_summary = $this->xss_clean(get_sales_manage_payments_summary($payments, $sales, $this));
		$payment_summary = '';
		$data_rows = array();
		//$i =1;
		foreach($sales->result() as $sale)
		{
			//$sale->sale_id = $i;
			//$i++;
			$data_rows[] = $this->xss_clean(get_debit_sale_data_row($sale));
		}

		if($total_rows > 0)
		{
			//$data_rows[] = $this->xss_clean(get_sale_data_last_row($sales, $this));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'payment_summary' => $payment_summary));
	}
}
?>
