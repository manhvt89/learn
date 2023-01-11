<?php
class Purchase extends CI_Model
{
	public function get_info($purchase_id)
	{	
		$this->db->from('purchases');
		$this->db->join('people', 'people.person_id = purchases.supplier_id', 'LEFT');
		$this->db->join('suppliers', 'suppliers.person_id = purchases.supplier_id', 'LEFT');
		$this->db->where('id', $purchase_id);

		return $this->db->get();
	}
	/*
	public function get_receiving_by_reference($reference)
	{
		$this->db->from('receivings');
		$this->db->where('reference', $reference);

		return $this->db->get();
	}
	
	public function is_valid_receipt($receipt_receiving_id)
	{
		if(!empty($receipt_receiving_id))
		{
			//RECV #
			$pieces = explode(' ', $receipt_receiving_id);

			if(count($pieces) == 2 && preg_match('/(RECV|KIT)/', $pieces[0]))
			{
				return $this->exists($pieces[1]);
			}
			else 
			{
				return $this->get_receiving_by_reference($receipt_receiving_id)->num_rows() > 0;
			}
		}

		return FALSE;
	}
	*/

	public function exists($id)
	{
		$this->db->from('purchases');
		$this->db->where('id', $id);
		return ($this->db->get()->num_rows() == 1);
	}

	public function exists_by_code($code)
	{
		$this->db->from('purchases');
		$this->db->where('code', $code);
		return ($this->db->get()->num_rows() == 1);
	}
	
	public function update($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('purchases', $data);
	}
	// Save session to DB; create draff
	public function save($items, $supplier_id, $employee_id, $name, $code ='POxxx' ,$comment = '',$completed = 0)
	{
		if(count($items) == 0)
		{
			return -1;
		}

		$_aPurchaseData = array(
			'purchase_time' => date('Y-m-d H:i:s'),
			'supplier_id' => $this->Supplier->exists($supplier_id) ? $supplier_id : 0,
			'employee_id' => $employee_id,
			'name' => $name,
			'code' => $code,
			'completed'=>$completed,
			'comment' => $comment
		);

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->insert('purchases', $_aPurchaseData);
		$_iPurchaseID = $this->db->insert_id();

		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			$_aPurchasesItemsData = array(
				'purchase_id' => $_iPurchaseID,
				'item_id' => $item['item_id'],
				'line' => $line,				
				'item_quantity' => $item['quantity'],
				'item_price' => $item['price'], // Giá nhập 
				'item_name' => $cur_item_info['name'],
				'item_number'=>$cur_item_info['item_number'],
				'item_category' => $cur_item_info['category']
			);
			$this->db->insert('purchases_items', $_aPurchasesItemsData);
		}

		$this->db->trans_complete();
		
		if($this->db->trans_status() === FALSE)
		{
			return -1;
		}

		return $_iPurchaseID;
	}
	/**
	 * Summary of the_purchase: Get purchase with all items
	 * @param mixed $purchase_id
	 * @return mixed
	 */
	public function the_purchase($purchase_id)
	{
		$_aThePurchase = array();
		$this->db->from('purchases');
		$this->db->where('id', $purchase_id);
		$_aThePurchase = $this->db->get()->row_array(); // return a array();
		if(empty($_aThePurchase))
		{
			return array();
		} else {
			$_aThePurchase['items'] = $this->get_purchase_items($purchase_id)->result_array();
			return $_aThePurchase;
		}
	}
	public function get_purchase_items($purchase_id)
	{
		$this->db->from('purchases_items');
		$this->db->where('purchase_id', $purchase_id);
		return $this->db->get();
	}
	
	public function get_supplier($purchase_id)
	{
		$this->db->from('purchases');
		$this->db->where('id', $purchase_id);
		return $this->Supplier->get_info($this->db->get()->row()->supplier_id);
	}

	/*
	We create a temp table that allows us to do easy report/purchase queries
	*/
	public function create_temp_table(array $inputs)
	{
		if(empty($inputs['purchase_id']))
		{
			$where = 'WHERE DATE(purchase_time) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']);
		}
		else
		{
			$where = 'WHERE purchases_items.purchase_id = ' . $this->db->escape($inputs['purchase_id']);
		}
		
		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('purchases_items_temp') . 
			' (INDEX(purchase_date), INDEX(purchase_id))
			(
				SELECT 
					DATE(purchase_time) AS purchase_date,
					purchase_time,
					purchases_items.purchase_id,
					comment,
					name,
					employee_id, 
					items.item_id,
					purchases.supplier_id,
					item_quantity as quantity_purchased,
					item_price,
					purchases_items.line,
					items.category,
					(item_price * quantity_purchased) AS total
				FROM ' . $this->db->dbprefix('purchases_items') . ' AS purchases_items
				INNER JOIN ' . $this->db->dbprefix('purchases') . ' AS purchases
					ON purchases_items.purchase_id = purchases.id
				INNER JOIN ' . $this->db->dbprefix('items') . ' AS items
					ON purchases_items.item_id = items.item_id
				' . "
				$where
				" . '
				GROUP BY purchases_items.purchase_id, items.item_id, purchases_items.line
			)'
		);
	}
}
?>
