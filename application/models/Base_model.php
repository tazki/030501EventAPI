<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// FROM: http://www.thephpcode.com/blog/view/a-smart-codeigniter-model.html
class Base_Model extends CI_Model {

	public $table;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function count($table, $search_query='')
    {
        $search_query = (!empty($search_query)) ? $search_query : 1;
        
        $query = $this->db->query(
            'SELECT COUNT(*) as `count`
            FROM `'.$table.'`
            WHERE '.$search_query
        );
        $row = $query->result_array();
        $query->free_result();  // The $query result object will no longer be available            
        return $row[0]['count'];
    }

    function list_all($table, $limit='', $current_page='', $sort_field='', $order='', $search_query='', $return_fields='')
    {
    	$return_fields = (!empty($return_fields)) ? $return_fields : '*';
        
        $search_query = (!empty($search_query)) ? $search_query : 1;
        
        $order = ($order=='asc') ? 'ASC' : 'DESC';
        $order_by = (!empty($sort_field)) ? ' ORDER BY `'.$sort_field.'` '.$order : '';

        if(!empty($current_page) && is_numeric($current_page))
        {
        	$current_page = ($current_page - 1) * $limit;
        }
        else
        {
        	$current_page = 0;
        }
        $limit_query = (!empty($limit)) ? ' LIMIT '.$current_page.', '.$limit : '';

        $query = $this->db->query(
            'SELECT '.$return_fields.'
            FROM `'.$table.'`
            WHERE '.$search_query.$order_by.$limit_query
        );
        $rows = $query->result_array();
        if($query->num_rows() > 0)
        {
            foreach($rows as $key => $value)
            {
                $rows[$key] = $value;
            }

            return $rows;
        }
    }

    function list_all_by_field($table, $field_key, $limit='', $current_page='', $sort_field='', $order='', $search_query='', $return_fields='')
    {
    	$return_fields = (!empty($return_fields)) ? $return_fields : '*';

        $search_query = (!empty($search_query)) ? $search_query : 1;
        
        $order = ($order=='asc') ? 'ASC' : 'DESC';
        $order_by = (!empty($sort_field)) ? ' ORDER BY `'.$sort_field.'` '.$order : '';

        if(!empty($current_page) && is_numeric($current_page))
        {
        	$current_page = ($current_page - 1) * $limit;
        }
        else
        {
        	$current_page = 0;
        }
        $limit_query = (!empty($limit)) ? ' LIMIT '.$current_page.', '.$limit : '';

        $query = $this->db->query(
            'SELECT '.$return_fields.'
            FROM `'.$table.'`
            WHERE '.$search_query.$order_by.$limit_query
        );
        $rows = $query->result_array();

        if($query->num_rows() > 0)
        {
        	$tmp_rows = array();
            foreach($rows as $key => $value)
            {
                $tmp_rows[$value[$field_key]] = $value;
            }

            return $tmp_rows;
        }
    }

	public function search_one($conditions=NULL,$tablename="",$orderby=NULL,$order='DESC',$limit=1,$offset=0)
	{
		if($tablename=="")
		{
			$tablename = $this->table;
		}
		if($conditions != NULL)
		{
			if(is_array($conditions) && isset($conditions[0]))
			{
				foreach($conditions as $val)
				{
					$this->db->where($val);
				}
			}
			else
			{
				$this->db->where($conditions);
			}
		}
			
		if($orderby != NULL)
			$this->db->order_by($orderby, $order);

		$query = $this->db->get($tablename,$limit,$offset=0);
		if($query->num_rows() > 0)
		{
			 $row = $query->result_array();
			 return $row[0];
		}

		return false;
	}

	public function search($conditions=NULL,$tablename="",$limit=500,$offset=0)
	{
		if($tablename=="")
		{
			$tablename = $this->table;
		}
		if($conditions != NULL)
			$this->db->where($conditions);

		$query = $this->db->get($tablename,$limit,$offset=0);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return false;
	}

	public function save($data,$tablename="")
	{
		if($tablename=="")
		{
			$tablename = $this->table;
		}
		$op = 'update';
		$keyExists = FALSE;
		$fields = $this->db->field_data($tablename);

		foreach ($fields as $field)
		{
			if($field->primary_key==1)
			{
				$keyExists = TRUE;
				if(isset($data[$field->name]))
				{
					$this->db->where($field->name, $data[$field->name]);
				}
				else
				{
					$op = 'insert';
				}
			}
		}
		if($keyExists && $op=='update')
		{
			$this->db->set($data);
			$this->db->update($tablename);
			if($this->db->affected_rows()==1)
			{
				return $this->db->affected_rows();
			}
		}
		$this->db->insert($tablename,$data);
		return $this->db->affected_rows();
	}

	public function insert($data,$tablename="")
	{
		if($tablename=="")
			$tablename = $this->table;

		$this->db->insert($tablename, $this->field_check($data,$tablename));
		return $this->db->insert_id();
	}

	public function update($data,$conditions,$tablename="")
	{
		if($tablename=="")
			$tablename = $this->table; $this->db->where($conditions);

		$this->db->update($tablename, $this->field_check($data,$tablename));
		return $this->search_one($conditions, $tablename);
	}

	public function delete($conditions,$tablename="")
	{
		if($tablename=="")
			$tablename = $this->table;
		$this->db->where($conditions);
		$this->db->delete($tablename);
		return $this->db->affected_rows();
	}

	public function field_check($data,$tablename)
	{	
		foreach($data as $key => $value)
		{
			#this will check if all the data fields are present on the table
			if ($this->db->field_exists($key, $tablename))
			{
			   $clean_data[$key] = $value;
			}
		}

		return $clean_data; 
	}
}

/* End of file MY_model.php */
/* Location: ./application/models/MY_model.php */