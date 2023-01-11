<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_lib
{
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function get_quantity()
	{
		if(!$this->CI->session->userdata('purchase_quantity'))
		{
			$this->set_quantity(0);
		}

		return $this->CI->session->userdata('purchase_quantity');
	}

	public function set_quantity($quantity)
	{
		$this->CI->session->set_userdata('purchase_quantity', $quantity);
	}

	public function clear_quantity()
	{
		$this->CI->session->unset_userdata('purchase_quantity');
	}

	public function get_cart()
	{
		if(!$this->CI->session->userdata('purchase_cart'))
		{
			$this->set_cart(array());
		}

		return $this->CI->session->userdata('purchase_cart');
	}

	public function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('purchase_cart', $cart_data);
	}

	public function empty_cart()
	{
		$this->CI->session->unset_userdata('purchase_cart');
		$this->set_quantity(0);
	}

	public function get_supplier()
	{
		if(!$this->CI->session->userdata('purchase_supplier'))
		{
			$this->set_supplier(-1);
		}

		return $this->CI->session->userdata('purchase_supplier');
	}

	public function set_supplier($supplier_id)
	{
		$this->CI->session->set_userdata('purchase_supplier', $supplier_id);
	}

	public function remove_supplier()
	{
		$this->CI->session->unset_userdata('purchase_supplier');
	}

	public function get_stock_source()
	{
		if(!$this->CI->session->userdata('purchase_stock_source'))
		{
			$this->set_stock_source($this->CI->Stock_location->get_default_location_id());
		}

		return $this->CI->session->userdata('purchase_stock_source');
	}
	
	public function get_name()
	{
		// avoid returning a NULL that results in a 0 in the comment if nothing is set/available
		$comment = $this->CI->session->userdata('purchase_name');

		return empty($comment) ? '' : $comment;
	}
	
	public function set_name($comment)
	{
		$this->CI->session->set_userdata('purchase_name', $comment);
	}
	
	public function clear_name()
	{
		$this->CI->session->unset_userdata('purchase_name');
	}
   
	public function get_reference()
	{
		return $this->CI->session->userdata('purchase_reference');
	}
	
	
	public function set_stock_source($stock_source)
	{
		$this->CI->session->set_userdata('purchase_stock_source', $stock_source);
	}
	
	public function clear_stock_source()
	{
		$this->CI->session->unset_userdata('purchase_stock_source');
	}
	
	public function get_stock_destination()
	{
		if(!$this->CI->session->userdata('purchase_stock_destination'))
		{
			$this->set_stock_destination($this->CI->Stock_location->get_default_location_id());
		}

		return $this->CI->session->userdata('purchase_stock_destination');
	}

	public function set_stock_destination($stock_destination)
	{
		$this->CI->session->set_userdata('purchase_stock_destination', $stock_destination);
	}
	
	public function clear_stock_destination()
	{
		$this->CI->session->unset_userdata('purchase_stock_destination');
	}
	/** END PROPERITES */
	/**
	 * Summary of calculate_quantity
	 * @return bool
	 */
	public function calculate_quantity()
	{
		$items = $this->get_cart();
		$quantity = 0;
		foreach ($items as $item)
		{
			$quantity = $quantity + $item['quantity'];
		}
		$this->set_quantity($quantity);

		return false;
	}

	/**
	 * Summary of add_item
	 * @param mixed $item_number = barcode
	 * @param mixed $item_name
	 * @param mixed $unit_price
	 * @param mixed $cost_price
	 * @param mixed $quantity
	 * @param mixed $category
	 * @return bool
	 */
	public function add_item($item_number, $item_name,$cost_price = 0, $quantity = 1, $category='')
	{
		//Get items in the receiving so far.
		
		$_cItems = $this->get_cart();
		$insertkey = count($_cItems);
		$item = array(
				'item_number' => $item_number,
				'item_name' => $item_name,
				'item_quantity' => $quantity,
				'item_price' => $cost_price,
				'item_category' => $category,
				'line' => $insertkey,
				'total' => $this->get_item_total($quantity, $cost_price,0)
		);


		$_cItems[$insertkey] = $item;
		$this->set_cart($_cItems);
		$this->calculate_quantity();
		return TRUE;
	}

	public function edit_item($line, $item_number, $item_name,$cost_price = 0, $quantity = 1, $category='')
	{
		$_cItems = $this->get_cart();
		if(isset($items[$_cItems]))
		{
			$_theItem = &$_cItems[$line];
			$_theItem['item_number'] = $item_number;
			$_theItem['item_name'] = $item_name;
			$_theItem['item_quantity'] = $quantity;
			$_theItem['item_price'] = $cost_price;
			$_theItem['item_category'] = $category;
			$_theItem['total'] = $this->get_item_total($quantity, $cost_price, 0);
			$this->set_cart($_cItems);
		}
		$this->calculate_quantity();
		return FALSE;
	}

	public function delete_item($line)
	{
		$_cItems = $this->get_cart();
		unset($_cItems[$line]);
		$this->set_cart($_cItems);
		$this->calculate_quantity();
	}
	/*
	public function return_entire_receiving($receipt_receiving_id)
	{
		//RECV #
		$pieces = explode(' ', $receipt_receiving_id);
		if(preg_match("/(RECV|KIT)/", $pieces[0]))
		{
			$receiving_id = $pieces[1];
		} 
		else 
		{
			$receiving_id = $this->CI->Receiving->get_receiving_by_reference($receipt_receiving_id)->row()->receiving_id;
		}

		$this->empty_cart();
		$this->remove_supplier();
		$this->clear_comment();

		foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)
		{
			$this->add_item($row->item_id, -$row->quantity_purchased, $row->item_location, $row->discount_percent, $row->item_unit_price, $row->description, $row->serialnumber, $row->receiving_quantity, TRUE);
		}

		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);
	}

	public function add_item_kit($external_item_kit_id, $item_location)
	{
		//KIT #
		$pieces = explode(' ',$external_item_kit_id);
		$item_kit_id = $pieces[1];
		
		foreach($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
		{
			$this->add_item($item_kit_item['item_id'],$item_kit_item['quantity'], $item_location);
		}
	}
		*/
	public function copy_entire_purchase($purchase_id)
	{
		$this->empty_cart();
		$this->remove_supplier();

		foreach($this->CI->Purchase->get_purchase_items($purchase_id)->result() as $row)
		{
			$this->add_item($row->item_number, $row->item_name, $row->item_price, $row->item_quantity, $row->item_category);
		}

		$this->set_supplier($this->CI->Purchase->get_supplier($purchase_id)->person_id);

	}

	public function clear_all()
	{
		$this->empty_cart();
		$this->remove_supplier();
		$this->clear_name();
	}

	public function get_item_total($quantity, $price, $discount_percentage)
	{
		$total = bcmul($quantity, $price);
		$discount_fraction = bcdiv($discount_percentage, 100);
		$discount_amount = bcmul($total, $discount_fraction);

		return bcsub($total, $discount_amount);
	}

	public function get_total()
	{
		$total = 0;
		foreach($this->get_cart() as $item)
		{
			$total = bcadd($total, $this->get_item_total($item['quantity'], $item['price'], $item['discount']));
		}
		
		return $total;
	}
}

?>
