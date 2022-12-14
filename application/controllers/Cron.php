<?php

class Cron extends CI_Controller{

	public function __construct(){
		parent::__construct();
		//$this->load->library('email');
		//$this->load->model('Model_main');
        $this->load->library('sms_lib');
	}

    // Import Sản phẩm;
    public function import_products()
    {
        $message = ' Bắt đầu import SP '. date('d/m/Y h:m:s',time());
        echo 	$message .PHP_EOL;
        
        $lfile =  str_replace('/public/','/',FCPATH).'log.txt';
        $_flog=fopen($lfile, 'a');
        fwrite($_flog, $message.PHP_EOL);

        //1. Get All sản phẩm từ file csv
        $_file = str_replace('/public_html/public/','/',FCPATH)."sp.csv";
        if(($handle = fopen($_file, 'r')) !== FALSE)
		{
            fgetcsv($handle); // bỏ qua hàng đầu tiên không làm gì, chuyển đến dòng 2
            $i = 1;
            $failCodes = array();

            while(($data = fgetcsv($handle)) !== FALSE)
            {
                
					//$item_data = array();
                if(sizeof($data) >= 0)
                {
                    $item_data = array(
                        'name'					=> $data[2],
                        'description'			=> '',
                        'category'				=> 'Cũ',
                        'cost_price'			=> $data[6],
                        'unit_price'			=> $data[7],
                        'reorder_level'			=> 0,
                        'supplier_id'			=> 3,
                        'allow_alt_description'	=> '0',
                        'is_serialized'			=> '0',
                        'custom1'				=> '',
                        'custom2'				=> '',
                        'custom3'				=> '',
                        'custom4'				=> '',
                        'custom5'				=> '',
                        'custom6'				=> '',
                        'custom7'				=> '',
                        'custom8'				=> '',
                        'custom9'				=> '',
                        'custom10'				=> ''
                    );
                    $item_number = $data[3];
                    $invalidated = FALSE;
                    if($item_number != '')
                    {
                        $item_data['item_number'] = $item_number;
                        $invalidated = $this->Item->item_number_exists($item_number);
                    }
				} else {
					$invalidated = TRUE;
				}
				
                if(!$invalidated && $this->Item->save($item_data))
				{
					$items_taxes_data = NULL;
						//tax 1

					$items_taxes_data[] = array('name' => 'Tax', 'percent' => '10' );



						// save tax values
					if(count($items_taxes_data) > 0)
					{
						$this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
					}

					// quantities & inventory Info
					$employee_id = 1; // Khởi tạo dữ liệu ban đầu;
					$emp_info = $this->Employee->get_info($employee_id);
					$comment =$this->lang->line('items_qty_file_import');
					// array to store information if location got a quantity
                    $item_quantity_data = array(
                        'item_id' => $item_data['item_id'],
                        'location_id' => 1,
                        'quantity' => 0,
                    );
					$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], 1);

                    $excel_data = array(
                        'trans_items' => $item_data['item_id'],
                        'trans_user' => $employee_id,
                        'trans_comment' => $comment,
                        'trans_location' => 1,
                        'trans_inventory' => 0
                    );

					$this->Inventory->insert($excel_data);

				} 
                else //insert or update item failure
				{
						$failCodes[$i] = $item_data['item_number'];
                        $message = "$i,". $item_data['item_number'];
                        fwrite($_flog, $message.PHP_EOL);
                        echo 	$message .PHP_EOL;
				}

				++$i;
            }
            
        } else {
            $message = ' Lỗi đọc file sp.csv';
            echo 	$message .PHP_EOL;
        }
        fclose($_flog);
    }
    // Import đơn kính
    public function import_dk()
    {
        $message = ' Bắt đầu import đơn kính '. date('d/m/Y h:m:s',time());
        echo 	$message .PHP_EOL;
        
        $lfile =  str_replace('/public/','/',FCPATH).'log.txt';
        $_flog=fopen($lfile, 'a');
        fwrite($_flog, $message.PHP_EOL);

        //2. Get All đơn kính từ file csv
        $_file = str_replace('/public_html/public/','/',FCPATH).'dk.csv';
        if(($handle = fopen($_file, 'r')) !== FALSE)
		{
            // Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					//$item_data = array();
					if(sizeof($data) >= 14)
					{
                        if($this->Testex->exists_by_code($data[1]))
                        {
                            $invalidated = TRUE; //do Nothing
                            $failCodes[$i] = $data[4];
                            $message = "$i,".$data[4].',ERR-EXIST';
                            fwrite($_flog, $message.PHP_EOL);
                            echo 	$message .PHP_EOL;
                        } else {

                            $reArray = array(); // right eye information
                            $leArray = array(); // left eye information

                            $leArray['ADD'] = $data[9];
                            $leArray['AX'] = $data[8];
                            $leArray['CYL'] = $data[7];
                            $leArray['PD'] = $data[11];
                            //$leArray['ADD'] = $this->input->post('l_add') ? $this->input->post('l_add');
                            $leArray['SPH'] = $data[6];
                            $leArray['VA'] = $data[10];

                            $reArray['ADD'] = $data[16];
                            $reArray['AX'] = $data[15];
                            $reArray['CYL'] = $data[14];
                            $reArray['PD'] = $data[18];
                            //$reArray['ADD'] = $this->input->post('r_add');
                            $reArray['SPH'] = $data[13];
                            $reArray['VA'] = $data[17];

                            $obj['note'] = '';
                            $obj['right_e'] = json_encode($reArray);
                            $obj['left_e'] = json_encode($leArray);
                            if($data[19]==1)
                            {
                                $obj['toltal'] = 'Nhìn xa';
                            }else{
                                $obj['toltal'] = '';
                            }
                            if($data[12]==1){
                                $obj['toltal'] = $obj['toltal'] . ';' . 'Nhìn gần';
                            }else{
                                $obj['toltal'] = $obj['toltal'] . ';' . '';
                            }

                            if($data[20]==1){
                                $obj['lens_type']= 'Đơn tròng';
                            }else{
                                $obj['lens_type']= '';
                            }
                            if($data[22]==1){
                                $obj['lens_type'] = $obj['lens_type'] . ';Hai tròng';
                            }else{
                                $obj['lens_type'] = $obj['lens_type'] . ';';
                            }
                            if($data[23]==1){
                                $obj['lens_type'] = $obj['lens_type'] . ';Đa tròng';
                            }else{
                                $obj['lens_type'] = $obj['lens_type'] . ';';
                            }
                            if($data[24]==1){
                                $obj['lens_type'] = $obj['lens_type'] . ';Mắt đặt';
                            }else{
                                $obj['lens_type'] = $obj['lens_type'] . ';';
                            }

                            $obj['type'] =  0;
                            if(!is_numeric(trim($data[32])))
                            {
                                $obj['duration'] = 0;
                            } else {
                                $obj['duration'] = trim($data[32]);
                            }
                            $obj['employeer_id'] = 1;
                            $obj['contact_lens_type'] = '';

                            //get customer_id via account_number
                            $customer = $this->Customer->get_info_by_account_number($data[4]);

                            $invalidated = FALSE;
                            if(!$customer)
                            {
                                $invalidated = TRUE;
                            }else {

                                $obj['customer_id'] = $customer->person_id;
                                $obj['code'] = $data[1]; // just only create new
                                $obj['test_time'] = strtotime($data[2]);
                            }

                            if(!$invalidated && $this->Testex->save($obj))
                            //if(!$invalidated)
                            {
                                $failCodes[$i] = $data[4];
                                $message = "$i,".$data[4].',OK';
                                fwrite($_flog, $message.PHP_EOL);
                                echo 	$message .PHP_EOL;
                            }
                            else //insert or update item failure
                            {
                                $failCodes[$i] = $data[4];
                                $message = "$i,".$data[4].',ERR';
                                fwrite($_flog, $message.PHP_EOL);
                                echo 	$message .PHP_EOL;
                            }
                        }
					}
					else
					{
						$invalidated = TRUE;
					}

					//var_dump($obj);
					//Kiểm tra xem đã tồn tại đơn kính chưa?
					// if($this->Testex->exists_by_code($data[1]))
					// {
					// 	$invalidated = TRUE; //do Nothing
					// }

					// if(!$invalidated && $this->Testex->save($obj))
					// //if(!$invalidated)
					// {
                    //     $failCodes[$i] = $data[4];
                    //     $message = "$i,".$data[4].',OK';
                    //     fwrite($_flog, $message.PHP_EOL);
                    //     echo 	$message .PHP_EOL;
					// }
					// else //insert or update item failure
					// {
                    //     $failCodes[$i] = $data[4];
                    //     $message = "$i,".$data[4].',ERR';
                    //     fwrite($_flog, $message.PHP_EOL);
                    //     echo 	$message .PHP_EOL;
					// }

					++$i;
				}

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

					fwrite($_flog, $message.PHP_EOL);
                    echo 	$message .PHP_EOL;
				}
				else
				{
					$message = $this->lang->line('items_excel_import_success');
                    fwrite($_flog, $message.PHP_EOL);
                    echo 	$message .PHP_EOL;
				}
        } else {
            $message = ' Lỗi đọc file sp.csv';
            echo 	$message .PHP_EOL;
        }
        // 3. 
        fclose($_flog);
    }
    // Import Khách hàng
    public function import_kh()
	{
	    $_file = "/home/dev.thiluc2020.com/kh.csv";
	    if(($handle = fopen($_file, 'r')) !== FALSE)
		{
                // Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();

				while(($data = fgetcsv($handle)) !== FALSE) 
				{
					// XSS file data sanity check
					//$data = $this->xss_clean($data);
                    echo '.';
					if(sizeof($data) >= 16)
					{
						$fullname = $data[3];
                        $names = explode(' ', $fullname);
                        $firstname = $names[count($names) - 1];
                        unset($names[count($names) - 1]);
                        $lastname = join(' ', $names);
                        $firstname = mb_convert_case($firstname, MB_CASE_TITLE, "UTF-8");
                        $lastname = mb_convert_case($lastname, MB_CASE_TITLE, "UTF-8");

					    $person_data = array(
							'first_name'	=> $firstname,
							'last_name'		=> $lastname,
							'gender'		=> 0,
							'email'			=> $data[10],
							'phone_number'	=> $data[8],
							'address_1'		=> $data[7],
							'address_2'		=> '',
							'city'			=> 'HN',
							'state'			=> 'HN',
							'zip'			=> '100000',
							'country'		=> 'VN',
							'comments'		=> '',
                            'age'           => 0
						);
						
						$customer_data = array(
							'company_name'		=> '',
							'discount_percent'	=> 0,
							'taxable'			=> 1
						);
						
						$account_number = $data[1];
						$invalidated = FALSE;
						if($account_number != '') 
						{
							$customer_data['account_number'] = $account_number;
							$invalidated = $this->Customer->account_number_exists($account_number);
						}
					}
					else 
					{
						$invalidated = TRUE;
					}

					if($invalidated || !$this->Customer->save_customer($person_data, $customer_data))
					{	
						$failCodes[] = $i;
					}
					
					++$i;
				}
				
				if(count($failCodes) > 0)
				{
					$message = 'So Luong Loi: ' . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);
					
					echo 	$message .PHP_EOL;
				}
				else
				{
				    $message = 'Thanh cong';
				    echo 	$message .PHP_EOL;
				}
			}
			else 
			{
                $message = 'Loi, khong tim thay file';
				echo 	$message .PHP_EOL;
			}
	}

	public function index(){
		if(!$this->input->is_cli_request()){
			//echo "This script can only be accessed via the command line" . PHP_EOL;
			//return;
		}
		//1. Get list tests to reminder

        $tests = $this->Testex->get_reminders();
        //var_dump($test->result());
        $data_rows = array();
        $ids = array();

        foreach ($tests->result() as $test) {
            $data_rows[$test->test_id] = $test;
            $ids[] = $test->test_id;
        }
        //var_dump($data_rows);
        $exits_reminders = $this->Reminder->get_reminders_in($ids);
        if($exits_reminders->result())
        {
            foreach ($exits_reminders->result() as $reminder)
            {
                //remove it from $data_rows
                unset($data_rows[$reminder->test_id]);
            }
        }
        if($data_rows) {
            foreach ($data_rows as $row) {
                $item['created_date'] = time();
                $item['test_id'] = $row->test_id;
                $item['name'] = $row->last_name . ' ' . $row->first_name;
                $item['tested_date'] = $row->test_time;
                $item['duration'] = $row->duration;
                $item['status'] = 0;
                $item['remain'] = 1;
                $item['des'] = $row->note;
                $item['customer_id'] = $row->customer_id;
                $item['action'] = '';
                $item['expired_date'] = $row->expired_date;
                $item['phone'] = $row->phone_number;
                $the_id = $this->Reminder->save($item);
                if($the_id)
                {
                    echo "Đã đồng bộ thành công " . $the_id . PHP_EOL;
                }
            }

        }
        else{
            echo "Không có bản ghi nào được đồng bộ" . PHP_EOL;
        }

        $reminders = $this->Reminder->get_reminders_sms();

		//$reminder = $this->Model_main->get_days_request_reminders($timestamp);

        // send sms
        if($this->sms_lib->init()) {
            $status = $this->sms_lib->send('0904991997', 'hello world');
            $content = "KINH MAT VIET HAN: Da den han kiem tra mat ban $reminder->name. 91 Truong Dinh, HBT, HN. LH:0969864555";
            foreach ($reminders->result() as $reminder)
            {
                if($reminder->phone) {
                    $status = $this->sms_lib->send($reminder->phone, $content);
                    if ($status) {
                        //update reminder with is_sms = 1
                        //$reminder->is_sms = 1;
                        $data['is_sms'] = 1;
                        $this->Reminder->update($reminder->id, $data);
                        $item['created_date'] = time();
                        $item['to'] = $reminder->phone;
                        $item['content'] = $content;
                        $item['type'] = 1;
                        $item['employee_id'] = 0;
                        $item['name'] = $reminder->name;
                        $this->Messages->save($item);

                        echo "Đã gửi sms thành công đến $reminder->name " . PHP_EOL;
                        sleep(30); //wait 30s before active to next task
                    }else{
                        $item['created_date'] = time();
                        $item['to'] = $reminder->phone;
                        $item['content'] = $content;
                        $item['type'] = 1;
                        $item['employee_id'] = 0;
                        $item['name'] = $reminder->name;
                        $item['status'] = 1;
                        $this->Messages->save($item);
                    }
                }
            }
            $this->sms_lib->close();
        }

	}

	public function send_sms_client()
    {
        $smses = $this->SmsSale->get_sms_sales();
        if($smses->result() == null)
        {
            echo "Không có bản ghi để gửi " . PHP_EOL;
            return;
        }
        //var_dump($smses->result());
        $content = "KINH MAT VIET HAN: Cam on quy khach da mua San Pham tai cua hang, quy khach can chinh sua gi vui long mang kinh den cua hang. Chinh sua MIEN PHI. LH:0969864555";
        if($this->sms_lib->init())
        {
            foreach ($smses->result() as $sms)
            {
                if(strlen($sms->phone) > 1) {
                    if((time() - $sms->saled_date) >= 600 ) {
                        $status = $this->sms_lib->send($sms->phone, $content);
                        if ($status) {
                            //update reminder with is_sms = 1
                            //$reminder->is_sms = 1;
                            $data['is_sms'] = 1;
                            $this->SmsSale->update($sms->id, $data);
                            $item['created_date'] = time();
                            $item['to'] = $sms->phone;
                            $item['content'] = $content;
                            $item['type'] = 0; //Gửi cảm ơn
                            $item['employee_id'] = 0;
                            $item['name'] = $sms->name;
                            $this->Messages->save($item);

                            echo "Đã gửi sms thành công đến $sms->name " . PHP_EOL;
                            sleep(30); //wait 30s before active to next task
                        } else {
                            $item['created_date'] = time();
                            $item['to'] = $sms->phone;
                            $item['content'] = $content;
                            $item['type'] = 0; //Gửi cảm ơn
                            $item['employee_id'] = 0;
                            $item['name'] = $sms->name;
                            $item['status'] = 1;//lỗi
                            $this->Messages->save($item);
                        }
                    }
                }else{
                    echo "$sms->name không có số điện thoại" . PHP_EOL;
                }
            }
        }else{
            echo "Chưa khởi tạo đc SMS MODEM" . PHP_EOL;
        }
    }

    public function generate_report_detail_sale()
    {
        ini_set('memory_limit', '-1');
        $sale_type = 'sales';
        $start_date = '2010-01-01';
        $end_date = date('Y-m-d');
        $location_id = 'all';
        $code = 0;
        $this->load->model('reports/Reports_detailed_sales');
        $report_detail = $this->Reports_detailed_sales;
        $code = $report_detail->getMax_code();
        //echo $code;die();
        $inputs = array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'location_id' => $location_id, 'code'=>$code);

        $this->load->model('reports/Detailed_sales');
        $model = $this->Detailed_sales;

        $model->create($inputs);

        $report_data = $model->getData($inputs);

        foreach($report_data['summary'] as $key => $row)
        {
            $summary_data = array(
                'code' => $row['sale_id'],
                'sale_time' => $row['sale_time'],
                'amount' => to_quantity_decimals($row['items_purchased']),
                'saler' => $row['employee_name'],
                'buyer' => $row['customer_name'],
                'subtotal' => $row['subtotal'],
                'tax' => $row['tax'],
                'total' => $row['total'],
                'cost' => $row['cost'],
                'profit' => $row['profit'],
                'paid_customer' => $row['payment_type'],
                'kind' => $row['kind'],
                'sale_type' => $sale_type,
                'comment' => $row['comment']
            );

            //var_dump($summary_data); die();

            foreach($report_data['details'][$key] as $drow)
            {
                $quantity_purchased = to_quantity_decimals($drow['quantity_purchased']);

                //$quantity_purchased .= ' [' . $this->Stock_location->get_location_name($drow['item_location']) . ']';

                $details_data[$key][] = array(
                                    'name'     =>$drow['name'],
                                    'category' => $drow['category'],
                                    'serialnumber' => $drow['serialnumber'],
                                    'description' => $drow['description'],
                                    'quantity_purchased'=>$quantity_purchased,
                                    'subtotal'=>to_currency($drow['subtotal']),
                                    'tax'=>to_currency($drow['tax']),
                                    'total'=>to_currency($drow['total']),
                                    'cost'=>to_currency($drow['cost']),
                                    'profit'=>to_currency($drow['profit']),
                                    'item_location'=>$drow['item_location'],
                                    'discount_percent'=>$drow['discount_percent'].'%');
            }
            $summary_data['items'] = json_encode($details_data[$key]);



            $rs = $report_detail->insert($summary_data);
            if($rs == 2)
            {
                echo '-';
            }elseif($rs == 1){
                echo "Exist: {$summary_data['code']} \n";
            }else{
                echo "Error: {$summary_data['code']} \n";
                break;
            }

        }




    }
}
