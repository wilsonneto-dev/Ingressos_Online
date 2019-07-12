<?php

//Classe GrupoPaginaAdmin e GrupoPaginaAdminDAO
//06 de Maio de 2012

class GrupoPaginaAdmin{

	public $id;
	public $cod_grupo_admin;
	public $cod_pagina_admin;
	private $bd;
	
	public function GrupoPaginaAdmin(){
		$this->id = "";
		$this->cod_grupo_admin = "";
		$this->cod_pagina_admin = "";
		$this->bd = new GrupoPaginaAdminDAO();
	}
	
	public function cadastrar(){ return $this->bd->cadastrar($this); }

	public function deletarByGrupo(){
		return $this->bd->deletarByGrupo($this);
	}

	public static function _deletarByGrupo($p_cod_grupo_admin){
		$o = new GrupoPaginaAdmin();
		$o->cod_grupo_admin = $p_cod_grupo_admin;
		return $this->bd->deletarByGrupo($this);
	}

}

class GrupoPaginaAdminDAO extends BaseDAO{
	
	public function cadastrar(GrupoPaginaAdmin $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					grupo_pagina_admin ( 
						cod_grupo_admin, 
						cod_pagina_admin
					) VALUES (
						'" . $this->con->real_escape_string( $p->cod_grupo_admin ) . "'
						,'" . $this->con->real_escape_string( $p->cod_pagina_admin ) . "'
					);";
				if( $q = $this->con->query( $str_q ) )
					return true;
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao");	
		} catch (Exception $e) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function deletarByGrupo(GrupoPaginaAdmin $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						DELETE 
							grupo_pagina_admin
						WHERE 
							cod_grupo_admin = '" . $this->con->real_escape_string( $obj->cod_grupo_admin ) . "' 
							AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

}

?>