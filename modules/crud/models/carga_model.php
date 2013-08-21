<?

class Carga_model extends CRUD_Model {
	
	function __construct() {
		parent::__construct('carga','IdCarga');
	}
	
	function get_articulo_por_bodega($IdArticulo, $IdBodegaOrigen) {
		$query = $this->db->select('ab.IdArticuloBodega',FALSE)
			->from('bodega AS b')
			->join('articulo_bodega AS ab','b.IdBodega = ab.IdBodega','inner')
			->join('tipo_bodega AS tb','b.IdTipoBodega = tb.IdTipoBodega','inner')
			->where('b.IdBodega',$IdBodegaOrigen)
			->where('ab.IdArticulo',$IdArticulo)
			->where('tb.EsExterna','<>1')->get();
			
		return $query->num_rows();
	}
}