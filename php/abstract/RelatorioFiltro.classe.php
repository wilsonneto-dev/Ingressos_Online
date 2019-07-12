<?php

class RelatorioFiltro{

	public $id;
	public $ordem;
	public $filtro;
	public $cod_relatorio_admin;

	private $bd;
	
	public function RelatorioFiltro(){
		$this->id = '';
		$this->ordem = '';
		$this->cod_relatorio_admin = "";
		$this->filtro = "";
		$this->bd = new RelatorioFiltroDAO();
	}
	
	public function getLista(){
		return $this->bd->getLista( $this );
	}

	public static function _getLista( $p_cod_relatorio_admin ){
		$obj = new RelatorioFiltro();
		$obj->cod_relatorio_admin = $p_cod_relatorio_admin;
		return ( $obj->getLista() ); 
	}



}

class RelatorioFiltroDAO extends BaseDAO{
	
	public function getLista( $p ){
		try {
			if( $this->abreConexao() ){
				$str_q = "
					SELECT 
						id, 
						ordem, 
						filtro, 
						cod_relatorio_admin
					FROM 
						relatorio_filtro 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND cod_relatorio_admin = '" . $this->con->real_escape_string( $p->cod_relatorio_admin ) . "'
					ORDER BY
						ordem ASC;";					
				if($q = $this->con->query($str_q)){
					$lista = array();
					if( $q->num_rows > 0)
					{
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$item = new RelatorioFiltro();
							$item->id = $obj->id;
							$item->ordem = $obj->ordem;
							$item->filtro = $obj->filtro;
							$item->cod_relatorio_admin = $obj->cod_relatorio_admin;
							$lista[] = $item;
						}
					}
					return $lista;	
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch ( Exception $e ) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

}

?>