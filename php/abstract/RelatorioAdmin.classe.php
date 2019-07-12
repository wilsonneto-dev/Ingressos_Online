<?php

class RelatorioAdmin{

	public $id;
	public $descricao;
	public $tipo;
	public $url;
	public $posicao;
	public $filtro;

	private $bd;
	
	public function RelatorioAdmin(){
		$this->id = '';
		$this->descricao = '';
		$this->tipo = "";
		$this->url = "";
		$this->posicao = "";
		$this->filtro = "";

		$this->bd = new RelatorioAdminDAO();

	}
	
	public function get(){
		return $this->bd->get($this);
	}

	public function possui_permissao( $cod_grupo_admin ){
		return $this->bd->possui_permissao( $this, $cod_grupo_admin );
	}



	// estáticos 
	public static function _get( $p_id ){
		$obj = new RelatorioAdmin();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

}

class RelatorioAdminDAO extends BaseDAO{
	
	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						descricao, 
						tipo, 
						url, 
						posicao, 
						filtro
					FROM 
						relatorio_admin 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->descricao = $obj->descricao;
						$p->tipo = $obj->tipo;
						$p->url = $obj->url;
						$p->posicao = $obj->posicao;
						$p->filtro = $obj->filtro;
						return true;
					}
					else return false;	
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch (Exception $e) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function possui_permissao( $p, $cod_grupo_admin ){
		try {
			if($this->abreConexao()){
				$str_q = "
						select 
							r.id
						from relatorio_admin r 
						inner join grupo_relatorio_admin gr 
							on gr.cod_relatorio_admin = r.id
						where
							ativo = 1
							AND gr.cod_relatorio_admin = '" . $this->con->real_escape_string( $p->id ) . "'
							AND gr.cod_grupo_admin = '" . $this->con->real_escape_string( $cod_grupo_admin ) . "'
							AND r.codprojeto = " . $this->codProjeto ." 
							AND r.id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						return true;
					}
					else return false;	
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch (Exception $e) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

}

?>