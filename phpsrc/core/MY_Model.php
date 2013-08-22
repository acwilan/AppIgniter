<?php

/**
 *
 * CI / MySQL Closure Table Model
 *
 * @link  http://www.slideshare.net/billkarwin/models-for-hierarchical-data
 * @TODO  improve
 *
 * sql default schema:
 * CREATE TABLE `closures` (
 *   `id` int(11) NOT NULL AUTO_INCREMENT,
 *   `ancestor` int(11) NOT NULL,
 *   `descendant` int(11) NOT NULL,
 *   `lvl` int(11) NOT NULL,
 *   PRIMARY KEY (`id`)
 * );
*/
class MY_Model extends CI_Model {

	public $table;
	public $closure_table = 'closures';
	public $clusure_schema = null;

	public function __construct($table_name = NULL, $closure_table = NULL, $closure_schema = NULL){

		parent::__construct();

		$this->table = $table_name;

		if ($closure_table !== NULL) {
			$this->closure_table = $closure_table;
		}

		$this->closure_schema = array('id' => 'id',
									'ancestor' => 'ancestor',
									'descendant' => 'descendant',
									'lvl' => 'lvl');

		if (is_array($closure_schema)) {
			foreach ($closure_schema as $key => $value)
				$this->closure_schema[$key] = $value;	
		}
	}



	/**
	 * Fetch children.
	 *
	 * Example to generate nested tree:
	 *
	 *   $data = $this->get_children(1, TRUE, FALSE, TRUE);
	 *   print_r($data);
	 *
	 * If level/depth specified then self will be ignore.
	 *
	 * @param  int      node id
	 * @param  boolean  include self
	 * @param  mixed    node level/depth (e.g direct children = 1)
	 * @param  boolean  nestify the result
	 * @return mixed    array if query true
	 */
	public function get_children($node_id = 1, $self = FALSE, $level = FALSE, $nested = FALSE)
	{

		$this->db->select('t.*, c1.' . $this->closure_schema['ancestor'] . ' as parent, c1.' . $this->closure_schema['ancestor'] . ' as parent, c1.' . $this->closure_schema['lvl'] . ' as level');
		$this->db->from($this->closure_table." c1");
		$this->db->join($this->table.' t','t.' . $this->closure_schema['id'] . ' = c1.' . $this->closure_schema['descendant'] );
		$this->db->where('c1.' . $this->closure_schema['ancestor'],$node_id);
		$this->db->where('c1.' . $this->closure_schema['ancestor'] . ' !=', 'c1.' . $this->closure_schema['descendant']);
		
		if ( ! $self) {
			$this->db->where('c1.' . $this->closure_schema['descendant'] . ' <>', $node_id);
		}

		if ($level) {
			$this->db->where('c1.' . $this->closure_schema['lvl'] . ' = ', $level);
		}

		$query = $this->db->get();

		if ( ! $query->num_rows()) {
			return FALSE;
		}

		$result = $query->result_array();

		if ($nested AND ! $level) {

			$trees = array();
			$root = null;

			foreach ($result as $row) {
				$trees[$row[$this->closure_schema['id']]] = $row;
			}

			foreach ($trees as $key => $row) {
				if( ! $root) {
					$root = $row['parent'];
				}

				$trees[$row['parent']]['children'][$key] =& $trees[$key];
			}

			$result = $trees[$root];

			if ( ! $self) {
				return $result['children'];
			}

			return isset($result[$this->closure_schema['id']]) ? $result : array_shift($result['children']);
		}

		return $result;


	}

	/**
	 * Add a node (as last child).
	 *
	 * @param  int    node id
	 * @param  int    target id
	 * @return boolean
	 */
	public function add($node_id, $target_id = 0) {

		$sql = 'SELECT ' . $this->closure_schema['ancestor'] . ', '.$node_id.', ' . $this->closure_schema['lvl'] . '+1
				FROM '.$this->closure_table.'
				WHERE ' . $this->closure_schema['descendant'] . ' = '.$target_id.'
				UNION
				SELECT '.$node_id.','.$node_id.',0';

		$query = 'INSERT INTO '.$this->closure_table . ' (' . $this->closure_schema['ancestor'] . ', ' . $this->closure_schema['descendant'] . ',' . $this->closure_schema['lvl'] . ') ' . $sql;

		$result = $this->db->query($query);

		return $result;

	}

	/**
	 * Check if current node has children.
	 *
	 * @param   int       node id
	 * @return  boolean
	 */
	public function has_children($node_id)
	{
		$this->db->select($this->closure_schema['descendant'])
			->from($this->closure_table)
			->where($this->closure_schema['ancestor'], $node_id);

		$descendants = $this->db->get()->result_array();

		foreach ($descendants as $k => $v) {
			$descendants[$k] = $v[$this->closure_schema['descendant']];
		}


		$query = $this->db->select('COUNT(*) as total')
						->from($this->closure_table)
						->where_in($this->closure_schema['ancestor'], implode(',', $descendants))
						->where($this->closure_schema['descendant'] . ' <>',$node_id)
						->get();

		return (bool) $query->row()->total;


	}

	/**
	 * Get parent(s) of current node.
	 *
	 * @param  int    current node id
	 * @param  mixed  level up (e.g direct parent = 1)
	 * @return mixed  array if succeed
	 */
	public function get_parent($node_id, $level = NULL)
	{

		$this->db->select('t.*')
				->from($this->table.' t')
				->join($this->closure_table.' c','t.' . $this->closure_schema['id'] . ' = c.' . $this->closure_schema['ancestor'])
				->where('c.' . $this->closure_schema['descendant'],$node_id)
				->where('c.' . $this->closure_schema['ancestor'] . ' <>',$node_id);

		if ($level) {
			$this->db->where('c.' . $this->closure_schema['lvl'], $level);
		}

		$this->db->order_by('t.' . $this->closure_schema['id']);

		$query = $this->db->get();

		if ($query->num_rows()) {
			if ($level) {
				return $query->row();
			}

			return $query->result();
		}

		return FALSE;
	}

	/**
	 * TODO: optional recursion
	 *
	 * Delete node.
	 *
	 * @param  int      node id
	 * @param  boolean  if TRUE, it will also delete from reference table
	 * @return mixed
	 */
	public function delete($node_id, $delete_reference = TRUE)
	{

		function pluck($input, $key) { 
			if (is_array($key) || !is_array($input)) return array(); 
			$array = array(); 
			foreach($input as $v) { 
				if(property_exists($v, $key)) $array[]=$v->{$key}; 
			} 
			return $array; 
		} 

		$operand = 'select ' . $this->closure_schema['descendant'] . ' as ' . $this->closure_schema['id'] . ' from '.$this->closure_table.' where ' . $this->closure_schema['ancestor'] . ' = '.$node_id;

		$query = 'select ' . $this->closure_schema['ancestor'] . ' as ' . $this->closure_schema['id'] . ', ' . $this->closure_schema['descendant'] . ' from '.$this->table.' where ' . $this->closure_schema['descendant'] . ' IN ('.$operand.')';


		$result_start = $this->db->query($query);


		if ( $result_start->num_rows() > 0 ) {

			$descendants = pluck($result_start->result(), $this->closure_schema['id']);
			$result_delete = $this->db->where_in($this->closure_schema['id'], implode(',', $descendants))->delete($this->closure_table);

			if ($delete_reference) {
				$descendants = pluck($result_start->result(), $this->closure_schema['descendant']);
				$delete_refs = $this->db->where_in($this->closure_schema['id'], implode(',', $descendants))->delete($this->table);
			}

			return $result_delete;
		}

		return FALSE;
	}

	/**
	 * Move node with its children to another node.
	 *
	 * @link  http://www.mysqlperformanceblog.com/2011/02/14/moving-subtrees-in-closure-table/
	 *
	 * @param  int  node to be moved
	 * @param  int  target node
	 * @return void
	 */
	public function move($node_id, $target_id)
	{
		// MySQL’s multi-table DELETE
		$query1 = 'DELETE a FROM '.$this->closure_table.' AS a ';
		$query1 .= 'JOIN '.$this->closure_table.' AS d ON a.' . $this->closure_schema['descendant'] . ' = d.' . $this->closure_schema['descendant'] . ' ';
		$query1 .= 'LEFT JOIN '.$this->closure_table.' AS x ';
		$query1 .= 'ON x.' . $this->closure_schema['ancestor'] . ' = d.' . $this->closure_schema['ancestor'] . ' AND x.' . $this->closure_schema['descendant'] . ' = a.' . $this->closure_schema['ancestor'] . ' ';
		$query1 .= 'WHERE d.' . $this->closure_schema['ancestor'] . ' = '.$node_id.'  AND x.' . $this->closure_schema['ancestor'] . ' IS NULL';

		$res1 = $this->db->query($query1);

		$query2 = 'INSERT INTO '.$this->closure_table.' (' . $this->closure_schema['ancestor'] . ', ' . $this->closure_schema['descendant'] . ', ' . $this->closure_schema['lvl'] . ') ';
		$query2 .= 'SELECT a.' . $this->closure_schema['ancestor'] . ', b.' . $this->closure_schema['descendant'] . ', a.' . $this->closure_schema['lvl'] . '+b.' . $this->closure_schema['lvl'] . '+1 ';
		$query2 .= 'FROM '.$this->closure_table.' AS a JOIN '.$this->closure_table.' AS b ';
		$query2 .= 'WHERE b.' . $this->closure_schema['ancestor'] . ' = '.$node_id.' AND a.' . $this->closure_schema['descendant'] . ' = '.$target_id;

		$res2 = $this->db->query($query2);

		return $res1 AND $res2;
	}

	/**
	 * Get (all) root nodes.
	 */
	public function get_root() {
	
		function pluck($input, $key) { 
			if (is_array($key) || !is_array($input)) return array(); 
			$array = array(); 
			foreach($input as $v) { 
				if(property_exists($v, $key)) $array[]=$v->{$key}; 
			} 
			return $array; 
		} 
	
		$this->db->select('r.' . $this->closure_schema['descendant']);
		$this->db->from($this->closure_table." r");
		$this->db->join($this->closure_table." p","r." . $this->closure_schema['descendant'] . ' = p.' . $this->closure_schema['descendant'] . ' AND p.' . $this->closure_schema['ancestor'] . ' <> p.' . $this->closure_schema['descendant'],"LEFT");
		$this->db->where('p.' . $this->closure_schema['descendant'] . ' IS NULL',NULL);

		$result = $this->db->get();

		if ($result) {
			return pluck($result->result(),$this->closure_schema['descendant']);
		} else {
			return false;
		}
	}
}