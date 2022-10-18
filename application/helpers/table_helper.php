<?php

function get_reminder_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('no' => $CI->lang->line('reminder_no')),
		array('name' => $CI->lang->line('reminder_name')),
		array('phone' => $CI->lang->line('reminder_phone')),
		array('tested_date' => $CI->lang->line('reminder_tested_date')),
		array('des' => $CI->lang->line('reminder_description')),
		array('remain'=>$CI->lang->line('reminder_remain')),
		array('messages'=>$CI->lang->line('reminder_messages')),
		array('call'=>$CI->lang->line('reminder_call')),
		array('retest'=>$CI->lang->line('reminder_retest'))
	);

	//$headers[] = array('invoice_number' => $CI->lang->line('sales_invoice_number'));

	return transform_headers($headers);
}
function get_reminder_data_row($reminder,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$diff = time() - $reminder->expired_date;
	return array (
		'no' => $reminder->no,
		'name' => $reminder->name,
		'phone' => $reminder->phone,
		'remain'=>floor($diff/(60*60*24)),
		'tested_date' => date('d/m/Y',$reminder->tested_date),
		'des' => $reminder->des,
		'messages' => empty($reminder->phone) ? '' : anchor("reminders/smsview/$reminder->id", '<span class="glyphicon glyphicon-phone"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('messages_sms_send'))),
		);
}
function get_test_manage_table_headers($sale_display=0)
{
	$CI =& get_instance();

	$headers = array(
		array('test_id' => $CI->lang->line('common_id')),
		array('test_time' => $CI->lang->line('test_test_time')),
		array('customer_name' => $CI->lang->line('test_customer_name')),
		array('note' => $CI->lang->line('test_note')),
		array('eyes' => $CI->lang->line('test_eyes')),
		array('toltal' => $CI->lang->line('test_toltal'))
	);

		//$headers[] = array('invoice_number' => $CI->lang->line('sales_invoice_number'));
	if($sale_display == 1)
	{
		$headers[] = array('sale' => '&nbsp', 'sortable' => FALSE);
	}


	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

function get_orders_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('sale_id' => $CI->lang->line('common_id')),
		array('sale_time' => $CI->lang->line('order_sale_time')),
		array('customer_name' => $CI->lang->line('order_customer_name')),
		array('amount_due' => $CI->lang->line('order_amount_due')),
		array('shipping_address' => $CI->lang->line('order_shipping_address')),
		array('shipping_phone' => $CI->lang->line('order_customer_phone')),
		array('shipping_method' => $CI->lang->line('order_shipping_method')),
		array('completed' => $CI->lang->line('order_completed'))

	);

	if($CI->config->item('invoice_enable') == TRUE)
	{
		$headers[] = array('invoice_number' => $CI->lang->line('sales_invoice_number'));
		$headers[] = array('invoice' => '&nbsp', 'sortable' => FALSE);
	}

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}


function get_sales_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('sale_id' => $CI->lang->line('common_id')),
		array('sale_time' => $CI->lang->line('sales_sale_time')),
		array('customer_name' => $CI->lang->line('customers_customer')),
		array('amount_due' => $CI->lang->line('sales_amount_due')),
		array('amount_tendered' => $CI->lang->line('sales_amount_tendered')),
		array('change_due' => $CI->lang->line('sales_change_due')),
		array('phone_number' => $CI->lang->line('sales_customer_phone'))
	);
	
	if($CI->config->item('invoice_enable') == TRUE)
	{
		$headers[] = array('invoice_number' => $CI->lang->line('sales_invoice_number'));
		$headers[] = array('invoice' => '&nbsp', 'sortable' => FALSE);
	}

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

/*
 Gets the html data rows for the sales.
 */
function get_sale_data_last_row($sales, $controller)
{
	$CI =& get_instance();
	$sum_amount_due = 0;
	$sum_amount_tendered = 0;
	$sum_change_due = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$sum_amount_due += $sale->amount_due;
		$sum_amount_tendered += $sale->amount_tendered;
		$sum_change_due += $sale->change_due;
	}

	return array(
		'sale_id' => '-',
		'sale_time' => '<b>'.$CI->lang->line('sales_total').'</b>',
		'amount_due' => '<b>'.to_currency($sum_amount_due).'</b>',
		'amount_tendered' => '<b>'. to_currency($sum_amount_tendered).'</b>',
		'change_due' => '<b>'.to_currency($sum_change_due).'</b>'
	);
}

function get_test_data_row($test, $controller,$sale_display=0)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	//var_dump($test);
	$row = array (
		'test_id' => $test->test_id,
		'test_time' => date("d/m/Y h:m:s",$test->test_time),
		//'customer_name' => '<a href="test/detail_test/'.$test->customer_id.'">'.$test->last_name . ' ' . $test->first_name.'</a>',
		'customer_name' => '<a href="test/detail_test/'.$test->account_number.'">'.$test->last_name . ' ' . $test->first_name.'</a>',
		'note' => $test->note
	);
	$re_arr = json_decode($test->right_e);
	$le_arr = json_decode($test->left_e);
	$re = '<table id="right-e">
			<tr>
				<td></td>
				<td>SPH</td>
				<td>CYL</td>
				<td>AX</td>
				<td>ADD</td>
				<td>VA</td>
				<td>PD/2</td>
			</tr>
			<tr>
				<td> R </td>
				<td>'. $re_arr->SPH.'</td>
				<td>'.$re_arr->CYL.'</td>
				<td>'.$re_arr->AX.'</td>
				<td>'.$re_arr->ADD.'</td>
				<td>'.$re_arr->VA.'</td>
				<td>'.$re_arr->PD.'</td>
</tr>
<tr>
				<td> L </td>
				<td>'. $le_arr->SPH.'</td>
				<td>'.$le_arr->CYL.'</td>
				<td>'.$le_arr->AX.'</td>
				<td>'.$le_arr->ADD.'</td>
				<td>'.$le_arr->VA.'</td>
				<td>'.$le_arr->PD.'</td>
</tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>';

	$row['eyes']=$re;
	$row['toltal'] = $test->toltal;
	if($sale_display == 1) {
		$row['sale'] = anchor("sales/test/$test->customer_id/$test->test_id", '<span class="glyphicon glyphicon-shopping-cart"></span>',
			array('title' => 'Bán hàng'));
	}

	$row['receipt'] = '';/*anchor($controller_name."/receipt/$test->test_id", '<span class="glyphicon glyphicon-usd"></span>',
		array('title' => $CI->lang->line('sales_show_receipt'))
	);*/
	$row['edit'] = '';/*anchor($controller_name."/edit/$test->test_id", '<span class="glyphicon glyphicon-edit"></span>',
		array('class' => 'modal-dlg print_hide', 'data-btn-delete' => $CI->lang->line('common_delete'), 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
	);*/

	return $row;
}

function get_the_payment_method_by_id($id)
{
	$arr = array(
		'COD'=>'COD',
		'CK ngân hàng'=>'CK ngân hàng',
		'Mã quà tặng'=>'Mã quà tặng'
	);
	return $arr[$id];
}
function get_the_order_source_by_id($id)
{
	$sourses = array(
		'fb'=>'facebook',
		'sp'=>'shopee',
		'ad'=>'adayroi',
		'ld'=>'lazada',
	);
	return $sourses[$id];
}

function get_order_status_by_id($id)
{
	$CI =& get_instance();
	$arr = array(
		-1=>$CI->lang->line('order_return'),
		0=>$CI->lang->line('order_info'),
		1=>$CI->lang->line('order_ordered'),
		2=>$CI->lang->line('order_shipping'),
		3=>$CI->lang->line('order_received'),
		4=>$CI->lang->line('order_completed')
	);
	return $arr[$id];
}

function get_shiping_method_by_id($id)
{
	$CI =& get_instance();
	$arr = array(
		'vnp'=>$CI->lang->line('order_vnp'),
		'vtp'=>$CI->lang->line('order_vtp'),
		'ghn'=>$CI->lang->line('order_ghn')
	);
	return $arr[$id];
}

function get_order_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	//var_dump($sale);
	/*
	 *
	 * $headers = array(
		array('sale_id' => $CI->lang->line('common_id')),
		array('sale_time' => $CI->lang->line('order_sale_time')),
		array('customer_name' => $CI->lang->line('order_customer_name')),
		array('amount_due' => $CI->lang->line('order_amount_due')),
		array('shipping_address' => $CI->lang->line('order_shipping_phone')),
		array('change_due' => $CI->lang->line('sales_change_due')),
		array('shipping_phone' => $CI->lang->line('order_customer_phone'))
	);
	 */
	$row = array (
		'sale_id' => $sale->sale_id,
		'sale_time' => date( $CI->config->item('dateformat') . ' ' . $CI->config->item('timeformat'), strtotime($sale->sale_time) ),
		'customer_name' => $sale->customer_name,
		'amount_due' => number_format($sale->amount_due),
		'shipping_address' => $sale->shipping_address,
		'shipping_phone' => $sale->shipping_phone . ' <a class="glyphicon glyphicon-heart-empty" target="_blank" href="'.$sale->facebook.'" ></a>',
		'shipping_method' => '<a target="_blank" href="'.get_url_tracking_shipping($sale->shipping_method).$sale->shipping_code.'">'.get_shiping_method_by_id($sale->shipping_method).'</a>',
		'completed'=>get_order_status_by_id($sale->completed),
		'status'=>$sale->status
	);

	$row['receipt'] = anchor($controller_name."/receipt/$sale->sale_id", '<span class="glyphicon glyphicon-usd"></span>',
		array('title' => $CI->lang->line('sales_show_receipt'))
	);
	switch ($sale->completed) {
		case -1:
			$row['edit'] = anchor($controller_name."/receipt/$sale->sale_id", '<span class="glyphicon glyphicon-ok"></span>',
				array('title' => $CI->lang->line('sales_show_receipt'))
			);
			break;
		case 0:
			$row['edit'] = anchor($controller_name . "/editsale/$sale->sale_id", '<span class="glyphicon glyphicon-edit"></span>',
				array('title' => $CI->lang->line($controller_name . '_update'))
			);
			break;
		case 1:
			$row['edit'] = anchor($controller_name . "/editsale/$sale->sale_id", '<span class="glyphicon glyphicon-edit"></span>',
				array('title' => $CI->lang->line($controller_name . '_update'))
			);
			break;
		case 2:
			$row['edit'] = anchor($controller_name . "/editsale/$sale->sale_id", '<span class="glyphicon glyphicon-edit"></span>',
				array('title' => $CI->lang->line($controller_name . '_update'))
			);
			break;
		case 3:
			$row['edit'] = anchor($controller_name . "/editsale/$sale->sale_id", '<span class="glyphicon glyphicon-edit"></span>',
				array('title' => $CI->lang->line($controller_name . '_update'))
			);
			break;
		case 4:
			$row['edit'] = anchor($controller_name."/receipt/$sale->sale_id", '<span class="glyphicon glyphicon-ok"></span>',
				array('title' => $CI->lang->line('sales_show_receipt'))
			);
			break;
	}

	return $row;
}


function get_sale_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);

	$row = array (
		'sale_id' => $sale->sale_id,
		'bacsi_id'=>$sale->bacsi_id,
		'sale_time' => date( $CI->config->item('dateformat') . ' ' . $CI->config->item('timeformat'), strtotime($sale->sale_time) ),
		'customer_name' => $sale->customer_name,
		'amount_due' => number_format($sale->amount_due),
		'amount_tendered' => number_format($sale->amount_tendered),
		'change_due' => number_format($sale->change_due),
		'payment_type' => $sale->payment_type,
		'status'=>$sale->status
	);

	if($CI->config->item('invoice_enable'))
	{
		$row['invoice_number'] = $sale->invoice_number;
		$row['invoice'] = empty($sale->invoice_number) ? '' : anchor($controller_name."/invoice/$sale->sale_id", '<span class="glyphicon glyphicon-list-alt"></span>',
			array('title'=>$CI->lang->line('sales_show_invoice'))
		);
	}

	$row['receipt'] = anchor($controller_name."/receipt/$sale->sale_id", '<span class="glyphicon glyphicon-usd"></span>',
		array('title' => $CI->lang->line('sales_show_receipt'))
	);
	if($sale->status == 1) {
		$row['edit'] = anchor($controller_name . "/editsale/$sale->sale_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('title' => $CI->lang->line($controller_name . '_update'))
		);
	}else{
		$row['edit'] = anchor($controller_name."/receipt/$sale->sale_id", '<span class="glyphicon glyphicon-ok"></span>',
			array('title' => $CI->lang->line('sales_show_receipt'))
		);
	}
	return $row;
}

/*
Get the sales payments summary
*/
function get_sales_manage_payments_summary($payments, $sales, $controller)
{
	$CI =& get_instance();
	$table = '<div id="report_summary">';

	foreach($payments as $key=>$payment)
	{
		$amount = $payment['payment_amount'];

		// WARNING: the strong assumption here is that if a change is due it was a cash transaction always
		// therefore we remove from the total cash amount any change due
		if( $payment['payment_type'] == $CI->lang->line('sales_cash') )
		{
			foreach($sales->result_array() as $key=>$sale)
			{
				$amount -= $sale['change_due'];
			}
		}
		$table .= '<div class="summary_row">' . $payment['payment_type'] . ': ' . to_currency( $amount ) . '</div>';
	}
	$table .= '</div>';

	return $table;
}

function transform_headers_readonly($array)
{
	$result = array();
	foreach($array as $key => $value)
	{
		$result[] = array('field' => $key, 'title' => $value, 'sortable' => $value != '', 'switchable' => !preg_match('(^$|&nbsp)', $value));
	}

	return json_encode($result);
}
function transform_headers_readonly_raw($array)
{
	$result = array();
	foreach($array as $key => $value)
	{
		$result[] = array('field' => $key, 'title' => $value, 'sortable' => $value != '', 'switchable' => false);
	}
	return $result;
}
function transform_headers($array, $readonly = FALSE, $editable = TRUE)
{
	$result = array();

	if (!$readonly)
	{
		$array = array_merge(array(array('checkbox' => 'select', 'sortable' => FALSE)), $array);
	}

	if ($editable)
	{
		$array[] = array('edit' => '');
	}

	foreach($array as $element)
	{
		reset($element);
		$result[] = array('field' => key($element),
			'title' => current($element),
			'switchable' => isset($element['switchable']) ?
				$element['switchable'] : !preg_match('(^$|&nbsp)', current($element)),
			'sortable' => isset($element['sortable']) ?
				$element['sortable'] : current($element) != '',
			'checkbox' => isset($element['checkbox']) ?
				$element['checkbox'] : FALSE,
			'class' => isset($element['checkbox']) || preg_match('(^$|&nbsp)', current($element)) ?
				'print_hide' : '',
			'sorter' => isset($element['sorter']) ?
				$element ['sorter'] : '');
	}
	return json_encode($result);
}

function transform_headers_raw($array, $readonly = FALSE, $editable = TRUE)
{
	$result = array();

	if (!$readonly)
	{
		$array = array_merge(array(array('checkbox' => 'select', 'sortable' => FALSE)), $array);
	}

	if ($editable)
	{
		$array[] = array('edit' => '');
	}

	foreach($array as $element)
	{
		reset($element);
		$result[] = array('field' => key($element),
			'title' => current($element),
			'switchable' => isset($element['switchable']) ?
				$element['switchable'] : !preg_match('(^$|&nbsp)', current($element)),
			'sortable' => isset($element['sortable']) ?
				$element['sortable'] : current($element) != '',
			'checkbox' => isset($element['checkbox']) ?
				$element['checkbox'] : FALSE,
			'class' => isset($element['checkbox']) || preg_match('(^$|&nbsp)', current($element)) ?
				'print_hide' : '',
			'sorter' => isset($element['sorter']) ?
				$element ['sorter'] : '');
	}
	return $result;
}

function get_people_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('people.person_id' => $CI->lang->line('common_id')),
		array('last_name' => $CI->lang->line('common_last_name')),
		array('first_name' => $CI->lang->line('common_first_name')),
		//array('email' => $CI->lang->line('common_email')),
		array('phone_number' => $CI->lang->line('common_phone_number')),
		array('address_1' => $CI->lang->line('common_address_1'))
	);

	if($CI->Employee->has_grant('messages', $CI->session->userdata('person_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}
	
	return transform_headers($headers);
}

function get_person_data_row($person, $controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	return array (
		'people.person_id' => $person->person_id,
		'last_name' => $person->last_name,
		'first_name' => $person->first_name,
		//'email' => empty($person->email) ? '' : mailto($person->email, $person->email),
		'phone_number' => $person->phone_number,
		'address_1'=>$person->address_1,
		'messages' => empty($person->phone_number) ? '' : anchor("Messages/view/$person->person_id", '<span class="glyphicon glyphicon-phone"></span>', 
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('messages_sms_send'))),
		'edit' => anchor($controller_name."/view/$person->person_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
	));
}

function get_suppliers_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('people.person_id' => $CI->lang->line('common_id')),
		array('company_name' => $CI->lang->line('suppliers_company_name')),
		array('agency_name' => $CI->lang->line('suppliers_agency_name')),
		array('last_name' => $CI->lang->line('common_last_name')),
		array('first_name' => $CI->lang->line('common_first_name')),
		array('email' => $CI->lang->line('common_email')),
		array('phone_number' => $CI->lang->line('common_phone_number'))
	);

	if($CI->Employee->has_grant('messages', $CI->session->userdata('person_id')))
	{
		$headers[] = array('messages' => '');
	}

	return transform_headers($headers);
}

function get_supplier_data_row($supplier, $controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	return array (
		'people.person_id' => $supplier->person_id,
		'company_name' => $supplier->company_name,
		'agency_name' => $supplier->agency_name,
		'last_name' => $supplier->last_name,
		'first_name' => $supplier->first_name,
		'email' => empty($supplier->email) ? '' : mailto($supplier->email, $supplier->email),
		'phone_number' => $supplier->phone_number,
		'messages' => empty($supplier->phone_number) ? '' : anchor("Messages/view/$supplier->person_id", '<span class="glyphicon glyphicon-phone"></span>', 
			array('class'=>"modal-dlg", 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('messages_sms_send'))),
		'edit' => anchor($controller_name."/view/$supplier->person_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>"modal-dlg", 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
}

//added by ManhVT
function get_accounting_manage_summary($payments, $controller)
{
	$CI =& get_instance();
	$table = '<div id="report_summary">';
	$table .= '<p>Số dư đầu kỳ: <b>'. number_format($payments['starting']).'</b></p>';
	$table .= '<p>Thu trong kỳ: <b>'. number_format($payments['in']).'</b></p>';
	$table .= '<p>Chi trong kỳ: <b>'. number_format($payments['po']).'</b></p>';
	$table .= '<p>Cuối trong kỳ: <b>'. number_format($payments['ending']).'</b></p>';
	$table .= '</div>';

	return $table;
}

function get_accounting_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('total_id' => $CI->lang->line('common_id')),
		array('created_time' => $CI->lang->line('accounting_created_time')),
		array('employee' => $CI->lang->line('accounting_employee')),
		array('person' => $CI->lang->line('accounting_person')),
		array('amount' => $CI->lang->line('accounting_amount')),
		array('type' => $CI->lang->line('accounting_type')),
		array('note' => $CI->lang->line('accounting_note'))

	);

	return transform_headers($headers);
}

function get_account_data_row($accounting, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	//var_dump($test);
	$row = array (
		'total_id' => $accounting->total_id,
		'created_time' => date("d/m/Y h:m:s",$accounting->created_time),
		'employee' => $accounting->employee,
		'note' => $accounting->note,
		'person'=>$accounting->person,
		'amount'=>number_format($accounting->amount)
	);

	if($accounting->type==0)
	{
		$row['type'] = "Thu";
	}else{
		$row['type'] = "Chi";
	}

	return $row;
}


function get_items_manage_table_headers()
{
	$CI =& get_instance();
	$person_id = $CI->session->userdata('person_id');
	if(!$CI->Employee->has_grant('items_accounting', $person_id))
	{
		$headers = array(
			array('items.item_id' => $CI->lang->line('common_id')),
			array('item_number' => $CI->lang->line('items_item_number')),
			array('name' => $CI->lang->line('items_name')),
			array('category' => $CI->lang->line('items_category')),
			array('company_name' => $CI->lang->line('suppliers_company_name')),
			array('unit_price' => $CI->lang->line('items_unit_price')),
			array('quantity' => $CI->lang->line('items_quantity')),
			array('tax_percents' => $CI->lang->line('items_tax_percents'), 'sortable' => FALSE),
			array('standard_amount' => $CI->lang->line('items_standard_amount'), 'sortable' => FALSE),
			array('inventory' => ''),
			array('stock' => '')
		);
	}else {
		$headers = array(
			array('items.item_id' => $CI->lang->line('common_id')),
			array('item_number' => $CI->lang->line('items_item_number')),
			array('name' => $CI->lang->line('items_name')),
			array('category' => $CI->lang->line('items_category')),
			array('company_name' => $CI->lang->line('suppliers_company_name')),
			array('cost_price' => $CI->lang->line('items_cost_price')),
			array('unit_price' => $CI->lang->line('items_unit_price')),
			array('quantity' => $CI->lang->line('items_quantity')),
			array('tax_percents' => $CI->lang->line('items_tax_percents'), 'sortable' => FALSE),
			array('standard_amount' => $CI->lang->line('items_standard_amount'), 'sortable' => FALSE),
			array('inventory' => ''),
			array('stock' => '')
		);
	}

	return transform_headers($headers);
}

function get_item_data_row($item, $controller)
{
	$CI =& get_instance();
	$item_tax_info = $CI->Item_taxes->get_info($item->item_id);
	$tax_percents = '';
	foreach($item_tax_info as $tax_info)
	{
		$tax_percents .= to_tax_decimals($tax_info['percent']) . '%, ';
	}
	// remove ', ' from last item
	$tax_percents = substr($tax_percents, 0, -2);
	$controller_name = strtolower(get_class($CI));

	$image = '';
	if ($item->pic_id != '')
	{
		$images = glob('./uploads/item_pics/' . $item->pic_id . '.*');
		if (sizeof($images) > 0)
		{
			$image .= '<a class="rollover" href="'. base_url($images[0]) .'"><img src="'.site_url('items/pic_thumb/'.$item->pic_id).'"></a>';
		}
	}

	return array (
		'items.item_id' => $item->item_id,
		'item_number' => $item->item_number,
		'name' => $item->name,
		'category' => $item->category,
		'company_name' => $item->company_name,
		'cost_price' => to_currency($item->cost_price),
		'unit_price' => to_currency($item->unit_price),
		'quantity' => to_quantity_decimals($item->quantity),
		'tax_percents' => !$tax_percents ? '-' : $tax_percents,
		'standard_amount' => to_quantity_decimals($item->standard_amount),
		'inventory' => anchor($controller_name."/inventory/$item->item_id", '<span class="glyphicon glyphicon-pushpin"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_count'))
		),
		'stock' => anchor($controller_name."/count_details/$item->item_id", '<span class="glyphicon glyphicon-list-alt"></span>',
			array('class' => 'modal-dlg', 'title' => $CI->lang->line($controller_name.'_details_count'))
		),
		'edit' => anchor($controller_name."/view/$item->item_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		));
}

function get_giftcards_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('giftcard_id' => $CI->lang->line('common_id')),
		array('last_name' => $CI->lang->line('common_last_name')),
		array('first_name' => $CI->lang->line('common_first_name')),
		array('giftcard_number' => $CI->lang->line('giftcards_giftcard_number')),
		array('value' => $CI->lang->line('giftcards_card_value'))
	);

	return transform_headers($headers);
}

function get_giftcard_data_row($giftcard, $controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	return array (
		'giftcard_id' => $giftcard->giftcard_id,
		'last_name' => $giftcard->last_name,
		'first_name' => $giftcard->first_name,
		'giftcard_number' => $giftcard->giftcard_number,
		'value' => to_currency($giftcard->value),
		'edit' => anchor($controller_name."/view/$giftcard->giftcard_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
		));
}

function get_item_kits_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('item_kit_id' => $CI->lang->line('item_kits_kit')),
		array('name' => $CI->lang->line('item_kits_name')),
		array('description' => $CI->lang->line('item_kits_description')),
		array('cost_price' => $CI->lang->line('items_cost_price'), 'sortable' => FALSE),
		array('unit_price' => $CI->lang->line('items_unit_price'), 'sortable' => FALSE)
	);

	return transform_headers($headers);
}

function get_item_kit_data_row($item_kit, $controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	return array (
		'item_kit_id' => $item_kit->item_kit_id,
		'name' => $item_kit->name,
		'description' => $item_kit->description,
		'cost_price' => to_currency($item_kit->total_cost_price),
		'unit_price' => to_currency($item_kit->total_unit_price),
		'edit' => anchor($controller_name."/view/$item_kit->item_kit_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
		));
}

?>
