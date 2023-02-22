<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//use chriskacerguis\RestServer\RestController;
require APPPATH . 'libraries/REST_Controller.php';
class Phone extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('admin/Sms_model', 'sms_model');
        $this->load->model('admin/setting_model','setting_model');
    }

    public function index_get()
    {
        $this->response(['GET ,,,.'], REST_Controller::HTTP_OK);
    }
    // Thực hiện put danh sách số điện thoại lên
    public function index_post()
    {
        $input = $this->input->post('list');
        $aLocalPhoneNumber = $this->setting_model->get_all_phone_number();
        if($input != '')
        {
            $aPhoneNunber = explode(',',$input);
            $aExistPhoneNumber = array();
            //So sánh nếu trùng thì lấy ra các phần tử trùng.
            foreach($aLocalPhoneNumber as $k=>$LocalPhoneNumber)
            {
                foreach($aPhoneNunber as $j=>$sPhoneNUmber)
                {
                    if($LocalPhoneNumber['number'] == $sPhoneNUmber)
                    {
                        $aExistPhoneNumber[] = $LocalPhoneNumber;
                        unset($aLocalPhoneNumber[$k]);
                        unset($aPhoneNunber[$j]);
                    }
                }
            }
            //Thêm mới
            if(!empty($aPhoneNunber))
            {
                $time = time();
                $new_data = array();
                foreach($aPhoneNunber as $sPhoneNUmber)
                {
                    if($sPhoneNUmber != '')
                    {
                        $prefix = substr($sPhoneNUmber,0,3);
                        $aPhone['number'] = $sPhoneNUmber;
                        $aPhone['dauso'] = $prefix;
                        $aPhone['status'] = 1;
                        $aPhone['working'] = 0;
                        $aPhone['created_date'] =$time;
                        $aPhone['updated_date'] = 0;
                        $aPhone['used_date'] = 0;
                        $aPhone['username'] = '';
                        $aPhone['user_id'] = '';
                        $new_data[] = $aPhone;
                    }
                }
                $this->setting_model->add_multi_numbers($new_data);
            }

            //update
            if(!empty($aLocalPhoneNumber))
            {
                $time = time();
                $new_data1 = array();
                foreach($aLocalPhoneNumber as $PhoneNUmber)
                {
                    $aPhone1['id'] = $PhoneNUmber['id'];
                    $aPhone1['dauso'] = $PhoneNUmber['number'];
                    $aPhone1['status'] = 0;
                    $aPhone1['working'] = 0;
                    $aPhone1['updated_date'] = $time;
                    $aPhone1['used_date'] = 0;
                    $aPhone1['username'] = '';
                    $aPhone1['user_id'] = '';
                    $new_data1[] = $aPhone1;
                }
                $this->setting_model->update_multi_numbers($new_data1);
               // $this->db->get_compiled_insert();
            }
            //update
            if(!empty($aExistPhoneNumber))
            {
                $time = time();
                $new_data2 = array();
                foreach($aExistPhoneNumber as $PhoneNUmber)
                {
                    $aPhone2['id'] = $PhoneNUmber['id'];
                    //$aPhone['dauso'] = $PhoneNUmber['number'];
                    $aPhone2['status'] = 1;
                    //$aPhone['working'] = 0;
                    $aPhone2['updated_date'] = $time;
                    //$aPhone['used_date'] = 0;
                    //$aPhone['username'] = '';
                    //$aPhone['user_id'] = '';
                    $new_data2[] = $aPhone2;
                }
                //var_dump($aExistPhoneNumber);
                $this->setting_model->update_multi_numbers($new_data2);
            }

            $this->response(['0'], REST_Controller::HTTP_OK);

        } else {
            $this->response(['1'], REST_Controller::HTTP_OK);
        }
    }
}