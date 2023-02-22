<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//use chriskacerguis\RestServer\RestController;
require APPPATH . 'libraries/REST_Controller.php';
class Phones extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('admin/Sms_model', 'sms_model');
        $this->load->model('api/product_model', 'product_model');
        $this->load->model('admin/setting_model','setting_model');
    }

	public function banlace_get($token='')
    {
        $token = $this->input->get('token');
        
        if($token != '')
        {
            $theUser = $this->product_model->get_the_user_by_token($token);
            if($theUser)
            {
               
                $this->response(['result'=>'0','status'=>'0', 'balance'=>$theUser['balance']], REST_Controller::HTTP_OK);
                            
                
            } else{
                $this->response(['result'=>'9','error'=>'Error, token is invalid'], REST_Controller::HTTP_OK);
            }
        } else{
            $this->response(['result'=>'8','error'=>'Error, please check token and ref_id'], REST_Controller::HTTP_OK);
        }
    }


    public function getsms_get($token='',$ref_id='')
    {
        $ref_id = $this->input->get('ref_id');
        $token = $this->input->get('token');
        
        if($token != '' && $ref_id != '')
        {
            $theUser = $this->product_model->get_the_user_by_token($token);
            if($theUser)
            {
                $record = $this->product_model->get_the_sale($ref_id,$theUser);
                if(!empty($record))
                {
                    //status = 0: hoàn thành; 1: đang chờ tin nhắn; 3: đã hết hạn; refun tiền
                    if($record['status'] == 0)
                    {
                        $this->response(['result'=>$record['status'],'status'=>$record['status'], 'content'=>$record['sms']==''?null:$record['sms'],'created_date'=>$record['created_date']], REST_Controller::HTTP_OK);
                    } else {
                        $this->response(['result'=>$record['status'],'status'=>$record['status'], 'content'=>"",'created_date'=>$record['created_date']], REST_Controller::HTTP_OK);
                            
                    }
                } else{
                    $this->response(['result'=>'10','error'=>'Error, ref_id is invalid'], REST_Controller::HTTP_OK);
                }
            } else{
                $this->response(['result'=>'9','error'=>'Error, token is invalid'], REST_Controller::HTTP_OK);
            }
        } else{
            $this->response(['result'=>'8','error'=>'Error, please check token and ref_id'], REST_Controller::HTTP_OK);
        }
    }
    // Thực hiện put danh sách số điện thoại lên
    public function request_post()
    {
        $token = $this->input->post('token');
        $service_id = $this->input->post('service_id');

        if($token != '' && $service_id != '')
        {
            $theUser = $this->product_model->get_the_user_by_token($token);
            if($theUser)
            {
               //var_dump($theUser); die();
                $service_ids = explode(',',$theUser['api_service_ids']);
                if(in_array($service_id, $service_ids))
                {
                    $theService = $this->product_model->get_the_product($service_id);
                    if($theService){
                        //doing here
                        //Create a service
                        $isp = "";
                        $prefix = "";
                        $exceptPrefix = "";
                        $product_id = $service_id;
                        $aTheNumbber = $this->product_model->get_random_number($isp, $prefix, $exceptPrefix, $product_id); 
                        if($aTheNumbber == null)
                        {
                            //$data['result'] = 4;
                            //$data['msg'] = "Đã hết số dành cho dịch vụ này, bạn vui lòng chờ 24h sau thử tiếp hoặc dùng dịch vụ khác";
                            $this->response(['result'=>'4','error'=>'Error, The number is out of stock'], REST_Controller::HTTP_OK);
                        } else{
                            $user = $theUser;		
                            $count_today = $this->product_model->count_renting_today($theUser); 
                            if($count_today < $theUser['api_counter'])
                            {                       
                                $count = $this->product_model->count_renting_numbers($theUser);
                                $data = array();
                                if($count < 10) // Uupdate từ 10 lần, hiện chỉ còn tối đa 2 lần
                                {
                                    $result = $this->product_model->create_service($aTheNumbber,$theService,$user);
                                    if($result == 1)
                                    {
                                        //$data['result'] = 1;
                                        //$data['msg'] = "The balance in the account is not enough, please deposit money";
                                        $this->response(['result'=>'5','error'=>'Error, The balance in the account is not enough, please deposit money'], REST_Controller::HTTP_OK);
                                    } elseif($result == 0)
                                    {
                                        //$data['result'] = 2;
                                        //$data['msg'] = "Có lỗi xảy ra, vui lòng thử lại";
                                        $this->response(['result'=>'6','error'=>'Error, There is a error, please try again'], REST_Controller::HTTP_OK);
                                    } else {
                                        //$data['result'] = 0;
                                        $data['msg'] = "Đã thuê dịch vụ thành công 9, hãy thao tác để nhận tin nhắn. CHÚ Ý: Đây là nhà mạng CAMPUCHIA, Bạn hãy bỏ 855 đi, nếu bạn đã chọn quốc gia Campuchia";
                                        $ref_id = $result;
                                        $this->response(['result'=>'0','ref_id'=>$ref_id,'number'=>$aTheNumbber['number'],'today_cc'=>$count_today], REST_Controller::HTTP_OK);
                                    }
                                } else{
                                    //$data['result'] = 3;
                                    //$data['msg'] = "Bạn đang order quá 10 dịch vụ, hãy thao tác để nhận tin nhắn hoặc chờ 15 phút thuê tiếp";
                                    $this->response(['result'=>'7','error'=>'You are ordering more than 10 services, please manipulate to receive a message or wait 15 minutes to rent again '], REST_Controller::HTTP_OK);
                                }
                            } else{
                                $this->response(['result'=>'8','error'=>'You have used the maximum number of times a day, please wait until the next day or contact the administrator for support.'], REST_Controller::HTTP_OK);
                            }
                            
                        }
                    }
                    else{
                        $this->response(['result'=>'3','error'=>'Error, ID of Service is invalid'], REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response(['result'=>'4','error'=>'Error, ID of Service do not allow, please check again'], REST_Controller::HTTP_OK);
                }
            } else{
                $this->response(['result'=>'2','error'=>'Error, token is invalid'], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response(['result'=>'1','error'=>'Error, please check token and service_id'], REST_Controller::HTTP_OK);
        }
    }

    public function request_get($token = '',$service_id = '')
    {
        $token = $this->input->get('token');
        $service_id = $this->input->get('service_id');
        //var_dump($token);
        if($token != '' && $service_id != '')
        {
            $theUser = $this->product_model->get_the_user_by_token($token);
            if($theUser)
            {
               //var_dump($theUser); die();
                $service_ids = explode(',',$theUser['api_service_ids']);
                if(in_array($service_id, $service_ids))
                {
                    $theService = $this->product_model->get_the_product($service_id);
                    if($theService){
                        //doing here
                        //Create a service
                        $isp = "";
                        $prefix = "";
                        $exceptPrefix = "";
                        $product_id = $service_id;
                        $aTheNumbber = $this->product_model->get_random_number($isp, $prefix, $exceptPrefix, $product_id); 
                        if($aTheNumbber == null)
                        {
                            //$data['result'] = 4;
                            //$data['msg'] = "Đã hết số dành cho dịch vụ này, bạn vui lòng chờ 24h sau thử tiếp hoặc dùng dịch vụ khác";
                            $this->response(['result'=>'4','error'=>'Error, The number is out of stock'], REST_Controller::HTTP_OK);
                        } else{
                            $user = $theUser;		
                            $count_today = $this->product_model->count_renting_today($theUser); 
                            if($count_today < $theUser['api_counter'])
                            {                       
                                $count = $this->product_model->count_renting_numbers($theUser);
                                $data = array();
                                if($count < 10) // Uupdate từ 10 lần, hiện chỉ còn tối đa 2 lần
                                {
                                    $result = $this->product_model->create_service($aTheNumbber,$theService,$user);
                                    if($result == 1)
                                    {
                                        //$data['result'] = 1;
                                        //$data['msg'] = "The balance in the account is not enough, please deposit money";
                                        $this->response(['result'=>'5','error'=>'Error, The balance in the account is not enough, please deposit money'], REST_Controller::HTTP_OK);
                                    } elseif($result == 0)
                                    {
                                        //$data['result'] = 2;
                                        //$data['msg'] = "Có lỗi xảy ra, vui lòng thử lại";
                                        $this->response(['result'=>'6','error'=>'Error, There is a error, please try again'], REST_Controller::HTTP_OK);
                                    } else {
                                        //$data['result'] = 0;
                                        $data['msg'] = "Đã thuê dịch vụ thành công 9, hãy thao tác để nhận tin nhắn. CHÚ Ý: Đây là nhà mạng CAMPUCHIA, Bạn hãy bỏ 855 đi, nếu bạn đã chọn quốc gia Campuchia";
                                        $ref_id = $result;
                                        $this->response(['result'=>'0','ref_id'=>$ref_id,'number'=>$aTheNumbber['number'],'today_cc'=>$count_today,'balance'=>$theUser['balance']], REST_Controller::HTTP_OK);
                                    }
                                } else{
                                    //$data['result'] = 3;
                                    //$data['msg'] = "Bạn đang order quá 10 dịch vụ, hãy thao tác để nhận tin nhắn hoặc chờ 15 phút thuê tiếp";
                                    $this->response(['result'=>'7','error'=>'You are ordering more than 10 services, please manipulate to receive a message or wait 15 minutes to rent again '], REST_Controller::HTTP_OK);
                                }
                            } else{
                                $this->response(['result'=>'8','error'=>'You have used the maximum number of times a day, please wait until the next day or contact the administrator for support.'], REST_Controller::HTTP_OK);
                            }
                            
                        }
                    }
                    else{
                        $this->response(['result'=>'3','error'=>'Error, ID of Service is invalid'], REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response(['result'=>'4','error'=>'Error, ID of Service do not allow, please check again'], REST_Controller::HTTP_OK);
                }
            } else{
                $this->response(['result'=>'2','error'=>'Error, token is invalid'], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response(['result'=>'1','error'=>'Error, please check token and service_id'], REST_Controller::HTTP_OK);
        }
    }

}
