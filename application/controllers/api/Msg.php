<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//use chriskacerguis\RestServer\RestController;
require APPPATH . 'libraries/REST_Controller.php';
class Msg extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('api/product_model', 'product_model');
        $this->load->model('admin/setting_model','setting_model');
    }

    public function index_post()
    {
       
       /**   msg=[{"number":"0988837774","msg":"M\u00e3 c\u1ee7a qu\u00fd v\u1ecb 477565 vui l\u00f2ng ko chia s\u1ebd m\u00e3 n\u00e0y, ...","y":"x"},{"number":"0988837774","msg":"M\u00e3 c\u1ee7a qu\u00fd v\u1ecb 477565 vui l\u00f2ng ko chia s\u1ebd m\u00e3 n\u00e0y, ...","y":"x"},{"number":"0988837774","msg":"M\u00e3 c\u1ee7a qu\u00fd v\u1ecb 477565 vui l\u00f2ng ko chia s\u1ebd m\u00e3 n\u00e0y, ...","y":"x"}] */

       $t = array(
           array("number"=>'855712826912', 'msg'=>'G-071349 is your Google verification code', 'ref_id'=>'xxxxxxxxxxxxxx','sender'=>'facebook'),
       );
        $strMsg = $this->input->post('msg');
        //$strMsg = json_encode($t);
        $arrMsg = json_decode($strMsg,true);
       //var_dump($strMsg);
       //var_dump($arrMsg);
        
        //1. Lấy tất cả các bản ghi sale =1; và số điện thoại trong $arrMsg;
        $return = $this->product_model->update_sms_api($arrMsg);
        $this->response([$return], REST_Controller::HTTP_OK);
        //1. update sale ==> status = 0: hoàn thành
        //2. update phone_number (giải phóng số)
    }

}