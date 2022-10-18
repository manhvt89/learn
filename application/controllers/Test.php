<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Test extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('test');
		$this->load->library('sale_lib');
		$this->load->library('test_lib');
		$this->load->library('barcode_lib');
		$this->load->library('email_lib');
		$this->load->library('barcode_lib');
		$this->logedUser_type = $this->session->userdata('type');
		$this->logedUser_id = $this->session->userdata('person_id');

	}

	public function detail_test($sAccount_Number){
        $person_id = $this->session->userdata('person_id');
        $this->test_lib->remove_customer();
        $this->test_lib->empty_cart();
        $this->test_lib->clear_test_id();

		if($this->logedUser_type != 2)
		{
			if(!$this->Employee->has_grant('test_manage', $person_id))
			{
				redirect('no_access/test/test_manage');
			}
			else
			{
				if($sAccount_Number) {

					if ($this->Customer->account_number_exists($sAccount_Number)) {

						$customer_id = $this->Customer->get_info_by_account_number($sAccount_Number)->person_id;
						$this->test_lib->set_customer($customer_id);
					}
					$this->_reload();
				}else{
					redirect('/test/index');
				}
			}
		} else {
			if($sAccount_Number) {

				if ($this->Customer->account_number_exists($sAccount_Number)) {

					$customer_id = $this->Customer->get_info_by_account_number($sAccount_Number)->person_id;
					//echo $customer_id; die();
					$this->test_lib->set_customer($customer_id);
				}
				$this->_reload();
			}else{
				redirect('/test/index');
			}
		}
    }
	public function index()
	{
		//test_create
        $person_id = $this->session->userdata('person_id');
       // echo str_replace('S','', strtoupper('s-0.00'));
		if($this->logedUser_type != 2)
		{
			if(!$this->Employee->has_grant('test_create', $person_id))
			{
				if(!$this->Employee->has_grant('test_manage', $person_id)) {
					redirect('no_access/test/test_create');
				}else{
					redirect('/test/manage');
				}
			}else {
				$this->test_lib->remove_customer();
				$this->test_lib->empty_cart();
				$this->test_lib->clear_test_id();
				$this->_reload();
			}
		} else {
			//Nếu là bác sĩ 
			$this->test_lib->remove_customer();
			$this->test_lib->empty_cart();
			$this->test_lib->clear_test_id();
			$this->_reload();
		}
	}

	public function manage()
	{
		$person_id = $this->session->userdata('person_id');

		if($this->logedUser_type != 2)
		{
			if(!$this->Employee->has_grant('test_manage', $person_id))
			{
				redirect('no_access/test/test_manage');
			}
			else
			{
				$data['is_create'] = $this->Employee->has_grant('test_create', $person_id);
				if($this->Employee->has_grant('sales_create', $person_id)) {
					$data['table_headers'] = get_test_manage_table_headers(1);
				}else{
					$data['table_headers'] = get_test_manage_table_headers();
				}

				// filters that will be loaded in the multiselect dropdown
				$data['filters'] = null;
				$this->load->view('test/manage', $data);
			}
		} else {
			/*
			** Chức năng này chỉ dành cho
			*/
			$data['is_create'] = 1;
			$data['table_headers'] = get_test_manage_table_headers(); //không cho phép có link (không hiển thị link)
			$data['filters'] = null;
			$this->load->view('test/manage', $data);
		}
	}

	public function get_row($row_id)
	{
		$sale_info = $this->Sale->get_info($row_id)->row();
		$data_row = $this->xss_clean(get_sale_data_row($sale_info, $this));
		echo json_encode($data_row);
	}

	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');

		$filters = array('type' => 'all',
			'location_id' => 'all',
			'start_date' => $this->input->get('start_date'),
			'end_date' => $this->input->get('end_date'));

		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);
		//$sales = $this->Testex->search($search, $filters, $limit, $offset, $sort, $order);
		$sales = $this->Testex->search($search, $filters, $limit, $offset, $sort, $order,$this->logedUser_type, $this->logedUser_id);
		$total_rows = $this->Testex->get_found_rows($search, $filters,$this->logedUser_type, $this->logedUser_id);
		//$payments = $this->Testex->get_payments_summary($search, $filters);
		//$payment_summary = $this->xss_clean(get_sales_manage_payments_summary($payments, $sales, $this));
        $user = $this->Employee->get_logged_in_employee_info();
        $permission = $this->Employee->has_grant('sales_create',$user->person_id);

		$data_rows = array();
        if($permission)
        {
            foreach ($sales->result() as $sale) {
                $data_rows[] = $this->xss_clean(get_test_data_row($sale, $this, 1));
            }
        }else {
            foreach ($sales->result() as $sale) {
                $data_rows[] = $this->xss_clean(get_test_data_row($sale, $this));
            }
        }

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	public function item_search()
	{
		$suggestions = array();
		$receipt = $search = $this->input->get('term') != '' ? $this->input->get('term') : NULL;

		if($this->sale_lib->get_mode() == 'return' && $this->Sale->is_valid_receipt($receipt))
		{
			// if a valid receipt or invoice was found the search term will be replaced with a receipt number (POS #)
			$suggestions[] = $receipt;
		}
		$suggestions = array_merge($suggestions, $this->Item->get_search_suggestions($search, array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE));
		$suggestions = array_merge($suggestions, $this->Item_kit->get_search_suggestions($search));

		$suggestions = $this->xss_clean($suggestions);

		echo json_encode($suggestions);
	}

	public function suggest_search()
	{
		$search = $this->input->post('term') != '' ? $this->input->post('term') : NULL;

		$suggestions = $this->xss_clean($this->Sale->get_search_suggestions($search));

		echo json_encode($suggestions);
	}

	public function select_customer()
	{
		$customer_id = $this->input->post('customer');

		if($this->Customer->exists($customer_id))
		{

			$this->test_lib->set_customer($customer_id);

		}
		$this->_reload();
	}

	public function remove_customer()
	{

		$this->test_lib->remove_customer();
        $this->test_lib->empty_cart();
        $this->test_lib->clear_test_id();

		$this->_reload();
	}

	public function complete()
	{
		$data = array();
        $obj = array();

        if ($this->input->post('hidden_test')) {

            $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
            $employee_info = $this->Employee->get_info($employee_id);
            $data['employee'] = $employee_info->last_name[0] . ' ' . $employee_info->first_name;
            $data['company_info'] = implode("\n", array(
                $this->config->item('address'),
                $this->config->item('phone'),
                $this->config->item('account_number')
            ));
            $customer_id = $this->test_lib->get_customer();
            $test_id = $this->test_lib->get_test_id();
            $data['test_id'] = $test_id;

            $reArray = array(); // right eye information
            $leArray = array(); // left eye information

            $leArray['ADD'] = $this->input->post('l_add') ? $this->input->post('l_add') : '';
            $leArray['AX'] = $this->input->post('l_ax') ? $this->input->post('l_ax') : '';
            $leArray['CYL'] = $this->input->post('l_cyl') ? $this->input->post('l_cyl') : '';
            $leArray['PD'] = $this->input->post('l_pd') ? $this->input->post('l_pd') : '';
            //$leArray['ADD'] = $this->input->post('l_add') ? $this->input->post('l_add');
            $leArray['SPH'] = $this->input->post('l_sph') ? $this->input->post('l_sph') : '';
            $leArray['VA'] = $this->input->post('l_va') ? $this->input->post('l_va') : '';

            $reArray['ADD'] = $this->input->post('r_add') ? $this->input->post('r_add') : '';
            $reArray['AX'] = $this->input->post('r_ax') ? $this->input->post('r_ax') : '';
            $reArray['CYL'] = $this->input->post('r_cyl') ? $this->input->post('r_cyl') : '';
            $reArray['PD'] = $this->input->post('r_pd') ? $this->input->post('r_pd') : '';
            //$reArray['ADD'] = $this->input->post('r_add');
            $reArray['SPH'] = $this->input->post('r_sph') ? $this->input->post('r_sph') : '';
            $reArray['VA'] = $this->input->post('r_va') ? $this->input->post('r_va') : '';


            $obj['note'] = $this->input->post('note') ? $this->input->post('note') : '';
            $obj['right_e'] = json_encode($reArray);
            $obj['left_e'] = json_encode($leArray);
            $obj['toltal'] = $this->input->post('distance') . ';' . $this->input->post('reading');
            $obj['lens_type'] = $this->input->post('single') . ';' .
                $this->input->post('bifocal') . ';' .
                $this->input->post('progressive') . ';' .
                $this->input->post('rx');
            $obj['type'] = $this->input->post('type') ? $this->input->post('type') : 0;
            $obj['reminder'] = $this->input->post('reminder') ? $this->input->post('reminder') : 0;
            $obj['duration'] = $this->input->post('duration') ? $this->input->post('duration') : 6;
            $obj['employeer_id'] = $employee_id;
            $obj['customer_id'] = $customer_id;
            $obj['contact_lens_type'] = '';
            $new = 0;
            if ($this->input->post('hidden_test_id') == 0) {
                $new = 1;
            }

            if ($new == 0) {

                $data['test_id_num'] = $this->Testex->update($test_id, $obj);
            } else {
                $obj['code'] = 'TD' . time(); // just only create new
                $data['test_id_num'] = $this->Testex->save($obj);
                $this->test_lib->set_cart($data['test_id_num']);
                $this->test_lib->set_test_id($data['test_id_num']);
            }
            $customer_info = $this->_load_customer_data($customer_id, $data);

            $data = $this->xss_clean($data);

            if ($data['test_id_num'] == -1) {
                $data['error_message'] = $this->lang->line('sales_transaction_failed');
            }
            $this->_reload();
        }
        else
        {
            $customer_id = $this->test_lib->get_customer();
            $customer_info = $this->_load_customer_data($customer_id, $data);
            $this->_reload();
        }

	}

	public function send_invoice($sale_id)
	{
		$sale_data = $this->_load_sale_data($sale_id);

		$result = FALSE;
		$message = $this->lang->line('sales_invoice_no_email');

		if(!empty($sale_data['customer_email']))
		{
			$to = $sale_data['customer_email'];
			$subject = $this->lang->line('sales_invoice') . ' ' . $sale_data['invoice_number'];

			$text = $this->config->item('invoice_email_message');
			$text = str_replace('$INV', $sale_data['invoice_number'], $text);
			$text = str_replace('$CO', 'POS ' . $sale_data['sale_id'], $text);
			$text = $this->_substitute_customer($text, (object) $sale_data);

			// generate email attachment: invoice in pdf format
			$html = $this->load->view('sales/invoice_email', $sale_data, TRUE);
			// load pdf helper
			$this->load->helper(array('dompdf', 'file'));
			$filename = sys_get_temp_dir() . '/' . $this->lang->line('sales_invoice') . '-' . str_replace('/', '-' , $sale_data['invoice_number']) . '.pdf';
			if(file_put_contents($filename, pdf_create($html)) !== FALSE)
			{
				$result = $this->email_lib->sendEmail($to, $subject, $text, $filename);
			}

			$message = $this->lang->line($result ? 'sales_invoice_sent' : 'sales_invoice_unsent') . ' ' . $to;
		}

		echo json_encode(array('success' => $result, 'message' => $message, 'id' => $sale_id));

		$this->sale_lib->clear_all();

		return $result;
	}

	public function send_receipt($sale_id)
	{
		$sale_data = $this->_load_sale_data($sale_id);

		$result = FALSE;
		$message = $this->lang->line('sales_receipt_no_email');

		if(!empty($sale_data['customer_email']))
		{
			$sale_data['barcode'] = $this->barcode_lib->generate_receipt_barcode($sale_data['sale_id']);

			$to = $sale_data['customer_email'];
			$subject = $this->lang->line('sales_receipt');

			$text = $this->load->view('sales/receipt_email', $sale_data, TRUE);

			$result = $this->email_lib->sendEmail($to, $subject, $text);

			$message = $this->lang->line($result ? 'sales_receipt_sent' : 'sales_receipt_unsent') . ' ' . $to;
		}

		echo json_encode(array('success' => $result, 'message' => $message, 'id' => $sale_id));

		$this->sale_lib->clear_all();

		return $result;
	}

	private function _substitute_variable($text, $variable, $object, $function)
	{
		// don't query if this variable isn't used
		if(strstr($text, $variable))
		{
			$value = call_user_func(array($object, $function));
			$text = str_replace($variable, $value, $text);
		}

		return $text;
	}

	private function _substitute_customer($text, $customer_info)
	{
		// substitute customer info
		$customer_id = $this->sale_lib->get_customer();
		if($customer_id != -1 && $customer_info != '')
		{
			$text = str_replace('$CU', $customer_info->first_name . ' ' . $customer_info->last_name, $text);
			$words = preg_split("/\s+/", trim($customer_info->first_name . ' ' . $customer_info->last_name));
			$acronym = '';
			foreach($words as $w)
			{
				$acronym .= $w[0];
			}
			$text = str_replace('$CI', $acronym, $text);
		}

		return $text;
	}

	private function _is_custom_invoice_number($customer_info)
	{
		$invoice_number = $this->config->config['sales_invoice_format'];
		$invoice_number = $this->_substitute_variables($invoice_number, $customer_info);

		return $this->sale_lib->get_invoice_number() != $invoice_number;
	}

	private function _substitute_variables($text, $customer_info)
	{
		$text = $this->_substitute_variable($text, '$YCO', $this->Sale, 'get_invoice_number_for_year');
		$text = $this->_substitute_variable($text, '$CO', $this->Sale , 'get_invoice_count');
		$text = $this->_substitute_variable($text, '$SCO', $this->Sale_suspended, 'get_invoice_count');
		$text = strftime($text);
		$text = $this->_substitute_customer($text, $customer_info);

		return $text;
	}

	private function _load_customer_data($customer_id, &$data, $totals = FALSE)
	{
		$customer_info = '';

		if($customer_id != -1)
		{
			$customer_info = $this->Customer->get_info($customer_id);
            //var_dump($customer_info);die();

			$data['customer'] = $customer_info->last_name . ' ' . $customer_info->first_name;

			$data['first_name'] = $customer_info->first_name;
            $data['age'] = $customer_info->age;
			$data['last_name'] = $customer_info->last_name;
			$data['customer_email'] = $customer_info->email;
			$data['customer_address'] = $customer_info->address_1;
			$data['customer_phone'] = $customer_info->phone_number;
			$data['customer_old_data'] = $this->test_lib->old_data_test_by_customer($customer_id);

            $data['customer_account_number'] = $customer_info->account_number;
			$data['customer_discount_percent'] = $customer_info->discount_percent;

			$data['customer_info'] = implode("\n", array(
				$data['customer'],
				$data['customer_address'],
				$data['customer_account_number']
			));
            if($this->test_lib->get_test_id() > 0) {
                $data['test'] = $this->Testex->get_info($this->test_lib->get_test_id());
            }else{
                $data['test'] = null;
            }
		}

		return $customer_info;
	}

	private function _reload($data = array())
	{

        $data['test_id'] = $this->test_lib->get_test_id();
	    $data['cart'] = $this->test_lib->get_cart();
		$data['items_module_allowed'] = $this->Employee->has_grant('items', $this->Employee->get_logged_in_employee_info()->person_id);

		$customer_info = $this->_load_customer_data($this->test_lib->get_customer(), $data, TRUE);
		//$data['invoice_number'] = $this->_substitute_invoice_number($customer_info);
		//$data['invoice_number_enabled'] = $this->sale_lib->is_invoice_number_enabled();

		//$data['print_after_sale'] = $this->sale_lib->is_print_after_sale();

		//$data['payments_cover_total'] = $this->sale_lib->get_amount_due() <= 0;
        if(isset($data['test_id']))
        {
            $test = $this->Testex->get_info($data['test_id']);
            if(isset($test)) {
                //var_dump($test);
                $data['toltal'] = explode(';', $test['toltal']);
                $data['duration'] = $test['duration'];
                $data['code'] = $test['code'];
                $data['lens_type'] = explode(';', $test['lens_type']);
                $data['type'] = $test['type'];
                $data['note'] = $test['note'];
                $data['test_time'] = date('d/m/Y H:m:s', $test['test_time']);
                $data['contact_lens_type'] = explode(';',$test['contact_lens_type']);

                $data['right_e'] = json_decode($test['right_e'],true);
                $data['left_e'] = json_decode($test['left_e'],true);
                $data['test_time'] = $test['test_time'];
                $data['reminder'] = $test['reminder'];
            }

        }else{

        }
        $data = $this->xss_clean($data);
		$this->load->view("test/register", $data);
	}

	public function receipt($sale_id)
	{
		$data = $this->_load_sale_data($sale_id);

		$this->load->view('sales/receipt', $data);

		$this->sale_lib->clear_all();
	}

	public function invoice($sale_id)
	{
		$data = $this->_load_sale_data($sale_id);

		$this->load->view('sales/invoice', $data);

		$this->sale_lib->clear_all();
	}

	public function view_test()
    {
        $test_id = $this->input->post('test_id');
        $this->test_lib->set_test_id($test_id);
        $this->test_lib->set_cart($test_id);
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $employee_info = $this->Employee->get_info($employee_id);
        $data['employee'] = $employee_info->last_name[0] . ' ' . $employee_info->first_name;
        $data['company_info'] = implode("\n", array(
            $this->config->item('address'),
            $this->config->item('phone'),
            $this->config->item('account_number')
        ));
        $customer_id = $this->test_lib->get_customer();
        $data['test_id'] = $this->test_lib->get_test_id();

        $customer_info = $this->_load_customer_data($customer_id, $data);

        $data = $this->xss_clean($data);

        $this->_reload($data);
    }
	public function edit($sale_id)
	{
		$data = array();

		$data['employees'] = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			foreach(get_object_vars($employee) as $property => $value)
			{
				$employee->$property = $this->xss_clean($value);
			}

			$data['employees'][$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
		}

		$sale_info = $this->xss_clean($this->Sale->get_info($sale_id)->row_array());
		$data['selected_customer_name'] = $sale_info['customer_name'];
		$data['selected_customer_id'] = $sale_info['customer_id'];
		$data['sale_info'] = $sale_info;

		$data['payments'] = array();
		foreach($this->Sale->get_sale_payments($sale_id)->result() as $payment)
		{
			foreach(get_object_vars($payment) as $property => $value)
			{
				$payment->$property = $this->xss_clean($value);
			}

			$data['payments'][] = $payment;
		}

		// don't allow gift card to be a payment option in a sale transaction edit because it's a complex change
		$data['payment_options'] = $this->xss_clean($this->Sale->get_payment_options(FALSE));

		$this->load->view('sales/form', $data);
	}

	public function delete($sale_id = -1, $update_inventory = TRUE)
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$sale_ids = $sale_id == -1 ? $this->input->post('ids') : array($sale_id);

		if($this->Sale->delete_list($sale_ids, $employee_id, $update_inventory))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('sales_successfully_deleted') . ' ' .
				count($sale_ids) . ' ' . $this->lang->line('sales_one_or_multiple'), 'ids' => $sale_ids));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('sales_unsuccessfully_deleted')));
		}
	}

	public function save($sale_id = -1)
	{
		$newdate = $this->input->post('date');

		$date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $newdate);

		$sale_data = array(
			'sale_time' => $date_formatter->format('Y-m-d H:i:s'),
			'customer_id' => $this->input->post('customer_id') != '' ? $this->input->post('customer_id') : NULL,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment'),
			'invoice_number' => $this->input->post('invoice_number') != '' ? $this->input->post('invoice_number') : NULL
		);

		// go through all the payment type input from the form, make sure the form matches the name and iterator number
		$payments = array();
		$number_of_payments = $this->input->post('number_of_payments');
		for ($i = 0; $i < $number_of_payments; ++$i)
		{
			$payment_amount = $this->input->post('payment_amount_' . $i);
			$payment_type = $this->input->post('payment_type_' . $i);
			// remove any 0 payment if by mistake any was introduced at sale time
			if($payment_amount != 0)
			{
				// search for any payment of the same type that was already added, if that's the case add up the new payment amount
				$key = FALSE;
				if(!empty($payments))
				{
					// search in the multi array the key of the entry containing the current payment_type
					// NOTE: in PHP5.5 the array_map could be replaced by an array_column
					$key = array_search($payment_type, array_map(function($v){return $v['payment_type'];}, $payments));
				}

				// if no previous payment is found add a new one
				if($key === FALSE)
				{
					$payments[] = array('payment_type' => $payment_type, 'payment_amount' => $payment_amount);
				}
				else
				{
					// add up the new payment amount to an existing payment type
					$payments[$key]['payment_amount'] += $payment_amount;
				}
			}
		}

		if($this->Sale->update($sale_id, $sale_data, $payments))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('sales_successfully_updated'), 'id' => $sale_id));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('sales_unsuccessfully_updated'), 'id' => $sale_id));
		}
	}

	public function cancel()
	{
		$this->sale_lib->clear_all();

		$this->_reload();
	}

	public function suspend()
	{
		$cart = $this->sale_lib->get_cart();
		$payments = $this->sale_lib->get_payments();
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$customer_id = $this->sale_lib->get_customer();
		$customer_info = $this->Customer->get_info($customer_id);
		$invoice_number = $this->_is_custom_invoice_number($customer_info) ? $this->sale_lib->get_invoice_number() : NULL;
		$comment = $this->sale_lib->get_comment();

		//SAVE sale to database
		$data = array();
		if($this->Sale_suspended->save($cart, $customer_id, $employee_id, $comment, $invoice_number, $payments) == '-1')
		{
			$data['error'] = $this->lang->line('sales_unsuccessfully_suspended_sale');
		}
		else
		{
			$data['success'] = $this->lang->line('sales_successfully_suspended_sale');
		}

		$this->sale_lib->clear_all();

		$this->_reload($data);
	}

	public function suspended()
	{
		$data = array();
		$data['suspended_sales'] = $this->xss_clean($this->Sale_suspended->get_all()->result_array());

		$this->load->view('sales/suspended', $data);
	}

	public function unsuspend()
	{
		$sale_id = $this->input->post('suspended_sale_id');

		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_suspended_sale($sale_id);
		$this->Sale_suspended->delete($sale_id);

		$this->_reload();
	}

	public function check_invoice_number()
	{
		$sale_id = $this->input->post('sale_id');
		$invoice_number = $this->input->post('invoice_number');
		$exists = !empty($invoice_number) && $this->Sale->check_invoice_number_exists($invoice_number, $sale_id);

		echo !$exists ? 'true' : 'false';
	}
}
?>