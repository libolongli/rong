<?php
/**
 * model基类，主要定义了基本的db操作
 * @version 1.0
 * @license 
 * @copyright 
 * @link 
 * @author  2013-12-5 下午9:59:44
 */
class Base_Model extends CI_Model {
	
	private $_tableName;
	
	/**
	 * 获取tableName
	 *
	 * @author 2013-12-5 下午9:54:24
	 */
	public function getTableName() {
		return $this->_tableName;
	}
	
	/**
	 * 设置tableName
	 *
	 * @param unknown $tableName        	
	 *
	 * @author 2013-12-5 下午9:55:06
	 */
	public function setTableName($tableName) {
		$this->_tableName = $tableName;
	}
	
	/**
	 * 构造函数
	 *
	 * @author 2013-12-5 下午9:18:15
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 开始事务(PASSED)
	 */
	public function trans_begin() {
		$this->db->trans_begin();
	}
	
	/**
	 * 事务提交(PASSED)
	 */
	public function trans_commit() {
		$this->db->trans_commit();
	}
	
	/**
	 * 事务回滚(PASSED)
	 */
	public function trans_rollback() {
		$this->db->trans_rollback();
	}
	
	/**
	 * 获取唯一的字段列表
	 * return_type
	 *
	 * @author 2013-12-8 下午8:19:17
	 */
	public function distinct($filed, $where = array()) {
		return $this->db->from($this->_tableName)->where($where)->distinct($filed)->result_array();
	}
	
	/**
	 * 插入数据(PASSED)
	 *
	 * @param unknown $insertArray        	
	 *
	 * @author 2013-12-5 下午9:09:44
	 */
	public function insert($insertArray = NULL) {
		$this->db->set($insertArray)->insert($this->_tableName);
		return $this->db->insert_id();
	}
	
	/**
	 * 更新数据记录(PASSED)
	 *
	 * @param unknown $where        	
	 * @param unknown $update        	
	 *
	 * @author 2013-12-5 下午9:16:24
	 */
	public function update($where = NULL, $update = NULL) {
		$this->db->update($this->_tableName, $update, $where);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows;
	}
	
	/**
	 * 删除数据(PASSED)
	 *
	 * @param unknown $where        	
	 *
	 * @author 2013-12-5 下午9:34:23
	 */
	public function delete($where = NULL) {
		return $this->db->delete($this->_tableName, $where);
	}
	
	/**
	 * 清理表数据（PASSED）
	 *
	 * @author 2013-12-5 下午9:36:21
	 */
	public function truncate() {
		return $this->db->truncate($this->_tableName);
	}
	
	/**
	 * 数据替换(PASSED)
	 *
	 * @param unknown $set        	
	 *
	 * @author 2013-12-5 下午9:38:16
	 */
	public function replace($sets = NULL) {
		return $this->db->from($this->_tableName)->set($sets)->replace();
	}
	
	/**
	 * 获取全表数据(PASSED)
	 */
	public function selectAll() {
		return $this->db->get($this->_tableName)->result_array();
	}
	
	/**
	 * 获取某一条件下的所有数据(PASSED)
	 * return_type
	 *
	 * @author 2013-12-11 下午11:26:14
	 */
	public function selectAllByWhere($where = NULL, $fields = "*", $order = array()) {
		$db = $this->db->from($this->_tableName)->where($where);
		if(is_array($order) && ! empty($order)) {
			if(is_array($order[0])) {
				foreach($order as $od) {
					$db->order_by($od[0], $od[1]);
				}
			} else {
				$db->order_by($order[0], $order[1]);
			}
		}
		return $db->get()->result_array();
	}
	
	/**
	 * 通过ID获取某一条记录(PASSED)
	 *
	 * @param unknown $id        	
	 *
	 * @author 2013-12-5 下午9:11:14
	 */
	public function selectById($id) {
		return $this->db->from($this->_tableName)->where('id', $id)->get()->row_array();
	}
	
	/**
	 * 根据指定的Cons搜索(PASSED)
	 */
	public function selectByCons($cons = array()) {
		if(empty($cons)) {
			return false;
		}

		$rs = $this->db->from($this->_tableName)->where($cons)->get()->result_array();
		if(count($rs) >= 1)
			return $rs[0];
		else
			return null;
	}
	
	/**
	 * JOIN查询（PASSED）
	 * $join = array(
	 * array(
	 * 'table' => 'xxx', 连接的表
	 * 'on' => '', 条件
	 * 'join' => '' 连接类型，可选项包括：left, right, outer, inner, left outer, 以及 right outer.
	 *)
	 *)
	 * return_type
	 *
	 * @author 2013-12-6 下午11:43:54
	 */
	public function selectJoinById($id, $joins = array()) {
		$id = intval($id);
		if($id < 0)
			return false;
		$joins = "";
		if(is_array($joins)) {
			foreach($joins as $join) {
				if(isset($join['join'])) {
					$joins .= "{$join['join']} JOIN `{$join['table']}` ON {$join['on']} ";
				} else {
					$joins .= "LEFT JOIN `{$join['table']}` ON {$join['on']} ";
				}
			}
		}
		$sql = "SELECT * FROM `{$this->_tableName}` {$joins}  WHERE {$this->_tableName}.id={$id}";
		return $this->fetchRow($sql);
	}
	
	/**
	 * 直接执行SQL(PASSED)
	 *
	 * @param string $sql        	
	 * @return boolean multitype:unknown
	 */
	public function fetchRow($sql = NULL) {
		if(empty($sql)) {
			return false;
		}
		return $this->db->query($sql)->row_array();
	}
	
	/**
	 * 直接执行SQL(PASSED)
	 *
	 * @param string $sql        	
	 * @return boolean multitype:unknown
	 */
	public function querySql($sql = NULL) {
		if(empty($sql)) {
			return false;
		}
		return $this->db->query($sql)->result_array();
	}
	
	/**
	 * 直接执行SQL 更新/删除/插入(PASSED)
	 *
	 * @param string $sql        	
	 * @return affecterow
	 */
	public function executeSql($sql = NULL) {
		$result = $this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	/**
	 * 获取多条记录(PASSED)
	 *
	 * @param unknown $where        	
	 * @param unknown $limit        	
	 * @param unknown $order        	
	 *
	 * @author 2013-12-5 下午9:15:02
	 */
	public function selectByWhereIn($where_in_field = NULL, $where_in_array = array(), $filed = "*", $limit = NULL, $order = array()) {
		$db = $this->db->from($this->_tableName);
		$db->where_in($where_in_field, $where_in_array);
		if(is_array($order) && ! empty($order)) {
			if(is_array($order[0])) {
				foreach($order as $od) {
					$db->order_by($od[0], $od[1]);
				}
			} else {
				$db->order_by($order[0], $order[1]);
			}
		}
		if($limit) {
			if(is_array($limit)) {
				$db->limit($limit[1], $limit[0]);
			} else {
				$db->limit($limit);
			}
		}
		return $db->get()->result_array();
	}
	
	/**
	 * 获取多条记录(PASSED)
	 *
	 * @param unknown $where        	
	 * @param unknown $limit        	
	 * @param unknown $order        	
	 *
	 * @author 2013-12-5 下午9:15:02
	 */
	public function selectByWhereAndIn($where = array(), $where_in_field = NULL, $where_in_array = array(), $filed = "*", $limit = NULL, $order = array()) {
		$db = $this->db->from($this->_tableName)->where($where)->select($filed);
		$db->where_in($where_in_field, $where_in_array);
		if(is_array($order) && ! empty($order)) {
			if(is_array($order[0])) {
				foreach($order as $od) {
					$db->order_by($od[0], $od[1]);
				}
			} else {
				$db->order_by($order[0], $order[1]);
			}
		}
		if($limit) {
			if(is_array($limit)) {
				$db->limit($limit[1], $limit[0]);
			} else {
				$db->limit($limit);
			}
		}
		return $db->get()->result_array();
	}
	/**
	 * 获取多条记录(PASSED)
	 *
	 * @param unknown $where        	
	 * @param unknown $limit        	
	 * @param unknown $order        	
	 *
	 * @author 2013-12-5 下午9:15:02
	 */
	public function selectByWhere($where = array(), $fileds = "*", $limit = NULL, $order = array()) {
		$db = $this->db->from($this->_tableName)->where($where)->select($fileds);
		if(is_array($order) && ! empty($order)) {
			if(is_array($order[0])) {
				foreach($order as $od) {
					$db->order_by($od[0], $od[1]);
				}
			} else {
				$db->order_by($order[0], $order[1]);
			}
		}
		if($limit) {
			if(is_array($limit)) {
				$db->limit($limit[1], $limit[0]);
			} else {
				$db->limit($limit);
			}
		}
		return $db->get()->result_array();
	}
	
	/**
	 * 查询(PASSED)
	 * $join = array(
	 * array(
	 * 'table' => 'xxx', 连接的表
	 * 'on' => '', 条件
	 * 'join' => '' 连接类型，可选项包括：left, right, outer, inner, left outer, 以及 right outer.
	 *)
	 *)
	 * return_type
	 *
	 * @author 2013-12-6 下午11:36:21
	 */
	public function selectJoinByWhere($where, $fileds = "*", $joins = array(), $limit = null, $order = array()) {
		$db = $this->db->from($this->_tableName)->where($where)->select($fileds);
		if($joins) {
			foreach($joins as $join) {
				if(isset($join['join'])) {
					$db->join($join['table'], $join['on'], $join['join']);
				} else {
					$db->join($join['table'], $join['on']);
				}
			}
		}
		if($order) {
			if(stripos($order[0], ".") === false) {
				$order_by_field = $this->_tableName . "." . $order[0];
			} else {
				$order_by_field = $order[0];
			}
			$db->order_by($order_by_field, $order[1]);
		}
		if($limit) {
			if(is_array($limit)) {
				$db->limit($limit[1], $limit[0]);
			} else {
				$db->limit($limit);
			}
		}
		return $db->get()->result_array();
	}
	
	/**
	 * 查询(PASSED)
	 * $join = array(
	 * array(
	 * 'table' => 'xxx', 连接的表
	 * 'on' => '', 条件
	 * 'join' => '' 连接类型，可选项包括：left, right, outer, inner, left outer, 以及 right outer.
	 *)
	 *)
	 * return_type
	 *
	 * @author 2013-12-6 下午11:36:21
	 */
	public function selectJoinByWhereGroup($where, $fileds = "*", $joins = array(), $group = null, $limit = null, $order = array()) {
		$db = $this->db->from($this->_tableName)->where($where);
		if($joins) {
			foreach($joins as $join) {
				if(isset($join['join'])) {
					$db->join($join['table'], $join['on'], $join['join']);
				} else {
					$db->join($join['table'], $join['on']);
				}
			}
		}
		if($order) {
			if(stripos($order[0], ".") === false) {
				$order_by_field = $this->_tableName . "." . $order[0];
			} else {
				$order_by_field = $order[0];
			}
			$db->order_by($order_by_field, $order[1]);
		}
		if($group) {
			if(is_array($group)) {
				$xx = array();
				foreach($group as $k => $group_xx) {
					if(strripos($group_xx, ".") === false) {
						$xx[] = $this->_tableName . "." . $group_xx;
					} else {
						$xx[] = $group_xx;
					}
				}
				$group_by = implode(",", $xx);
			} else {
				if(strripos($group, ".") === false) {
					$group_by = $this->_tableName . "." . $group;
				} else {
					$group_by = $group;
				}
			}
			$db->group_by($group_by);
		}
		if($limit) {
			if(is_array($limit)) {
				$db->limit($limit[1], $limit[0]);
			} else {
				$db->limit($limit);
			}
		}
		return $db->get()->result_array();
	}
	
	/**
	 * 获取总记录数(PASSED)
	 *
	 * @param unknown $where        	
	 *
	 * @author 2013-12-5 下午9:12:29
	 */
	public function count($where,$column=NULL,$arr=array()) {
		if($column==NULL){
			return $this->db->from($this->_tableName)->where($where)->count_all_results();
		}else{
			return $this->db->from($this->_tableName)->where($where)->where_in($column, $arr)->count_all_results();
		}		
	}

	/**
	 * 获取总记录数(PASSED)
	 *
	 * @param unknown $where        	
	 *
	 * @author 2013-12-5 下午9:12:29
	 */
	public function distinct_count($where,$column='id') {

		$this->db->select($column);
		$this->db->distinct();
		$this->db->from($this->_tableName)->where($where);
		$query = $this->db->get();

		return $query->num_rows();		
	}
	
	/**
	 * 初始判断Page|PageInfo
	 *
	 * @param number $num        	
	 * @param number $default_value        	
	 * @return number
	 */
	protected function initPageInfo($num = 1, $default_value = 1) {
		return intval($num) < 1 || intval($num) > 65535 ? $default_value : intval($num);
	}
	
	/**
	 * 计算分页信息
	 *
	 * @param number $page        	
	 * @param number $pageSize        	
	 * @return boolean
	 */
	public function processLimitInfo($page = 1, $pageSize = 25) {
		$page = $this->initPageInfo($page, 1);
		$pageSize = $this->initPageInfo($pageSize, 25);
		$limitStart =($page - 1) * $pageSize;
		if($page == 1) {
			$limitStart =($page - 1) * $pageSize;
		}
		return array(
				$limitStart,
				$pageSize 
		);
	}
	
	/**
	 *[insert_batch 批量插入]
	 * @return[type][description]
	 */
	public function insert_batch($data){
		return $this->db->insert_batch($this->_tableName, $data); 
	}

	/**
	 *[update_field_by_exp 通过表单时更改某字段的值]
	 * @return[type][description]
	 */
	public function update_field_by_exp($where,$fields){

		$this->db->where($where);
		foreach($fields as $field => $op) {
			// $this->db->set('balance','balance + 100',FALSE);
			$this->db->set($field,$op,FALSE);
		}
		$this->db->update($this->_tableName);
		
		return $this->db->affected_rows();
	}

	

	
	/**
	 * 获取请求ip
	 * boolean
	 * @author  2013-12-10 下午11:04:05
	 */
	public function getRequestIP(){

		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}else{
			$ip = $remote;
		}

		return $ip;
	}

}