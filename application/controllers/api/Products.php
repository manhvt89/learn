<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//use chriskacerguis\RestServer\RestController;


require APPPATH . 'libraries/RestController.php';

use chriskacerguis\RestServer\RestController;

class Products extends RESTController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('api/product','product');
    }

    public function products_get($id=null)
    {
        if($id== null)
        {
            $id = 0;
        }
        $items = $this->product->get_list_items_from_id($id);
        if(!empty($items)){
            //set the response and exit
            $this->response([
                'status' => TRUE,
                'data'=>$items,
                'message' => ''
            ], RestController::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'data'=>'',
                'message' => 'Không có sản phẩm nào phù hợp'
            ], RestController::HTTP_NOT_FOUND);
        } 
    }

    public function productscategory_get($cate=null)
    {
        if($cate== null)
        {
            $cate = "";
        }
        //echo html_entity_decode($cate);
        $items = $this->product->get_list_items_by_category_code($cate);
        if(!empty($items)){
            //set the response and exit
            $this->response($items, RestController::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No Item were found.'
            ], RestController::HTTP_NOT_FOUND);
        } 
    }

    public function the_product_get($uid=null)
    {
        if($uid== null)
        {
            $uid = 0;
        }
        $items = $this->product->get_the_product_by_uuid($uid);
        if(!empty($items)){
            //set the response and exit
            $this->response($items, RestController::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No Item were found.'
            ], RestController::HTTP_NOT_FOUND);
        } 
    }

    public function item_post()
    {
        echo 'Posst item: '. $this->input->post('uuid');
    }
}