<?php
class Module extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
    }

	public function get_the_module($id)
	{
		$query = $this->db->get_where('modules', array('module_key' => $id), 1);
		$row = null;
		
		if ($query->num_rows() == 1) {
			$row = $query->row();
		}

		return $row; // return obj

	}

	public function get_the_module_by_key($module_key)
	{
		$query = $this->db->get_where('modules', array('module_key' => $module_key), 1);
		$row = null;
		
		if ($query->num_rows() == 1) {
			$row = $query->row();
		}

		return $row; // return obj
	}
	
	public function get_module_name($module_key)
	{
		$query = $this->db->get_where('modules', array('module_key' => $module_key), 1);
		
		if($query->num_rows() == 1)
		{
			$row = $query->row();

			return $this->lang->line($row->name_lang_key);
		}
		
		return $this->lang->line('error_unknown');
	}
	
	public function get_module_desc($module_key)
	{
		$query = $this->db->get_where('modules', array('module_key' => $module_key), 1);

		if($query->num_rows() == 1)
		{
			$row = $query->row();

			return $this->lang->line($row->desc_lang_key);
		}
	
		return $this->lang->line('error_unknown');	
	}
	
	public function get_all_permissions()
	{
		$this->db->from('permissions');

		return $this->db->get();
	}
	
	public function get_all_subpermissions()
	{
		$this->db->from('permissions');
		$this->db->join('modules', 'modules.id = permissions.module_id1');
		// can't quote the parameters correctly when using different operators..
		$this->db->where($this->db->dbprefix('modules') . '.module_id!=', 'permission_id', FALSE);

		return $this->db->get();
	}
	
	public function get_all_modules()
	{
		$this->db->from('modules');
		$this->db->order_by('sort', 'asc');
		return $this->db->get();
	}
	
	public function get_allowed_modules($person_id)
	{
		$this->db->select('modules.*, permissions.permission_key');
		$this->db->from('modules');
		$this->db->join('permissions', 'permissions.module_id = modules.module_key');
		$this->db->join('grants', 'permissions.id = grants.permission_id');
		$this->db->join('roles', 'roles.id = grants.role_id');
		$this->db->join('user_roles', 'user_roles.role_id = roles.id');
		$this->db->where('user_id', $person_id);
		//$this->db->distinct();
		$this->db->order_by('sort', 'asc');
		return $this->db->get();		
	}

	public function get_grants_of_the_user($user_id)
	{
		$this->db->from('permissions');
		$this->db->join('grants', 'permissions.id = grants.permission_id');
		$this->db->join('roles', 'roles.id = grants.role_id');
		$this->db->join('user_roles', 'user_roles.role_id = roles.id');
		$this->db->where('user_id', $user_id);
		//$this->db->distinct();
		return $this->db->get();		
	}
	/* 
	FUNCTION NAME: get_roles_of_the_user
	INPUT PARAM: user id
	OUTPUT PARAM: 
	This funtion to get all roles of the use is logined. This call after logined
	*/
	public function get_roles_of_the_user( $user_id)
	{

		$this->db->from('roles');
		$this->db->join('user_roles', 'user_roles.role_id = roles.id');
		$this->db->where('user_id', $user_id);
		return $this->db->get();
	}

	/* 
	FUNCTION NAME: get_roles_of_the_user
	@output mixed 
	This funtion to get all roles of the use is logined. This call after logined
	*/
	public function get_roles()
	{
		$this->db->from('roles');
		$this->db->where('status', 0);
		return $this->db->get();
	}

}
?>
