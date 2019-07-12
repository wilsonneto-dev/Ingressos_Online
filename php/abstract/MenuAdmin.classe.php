<?php

class MenuAdmin{

	public $id;
	public $icone;
	public $texto;
	public $ordem;
	private $bd;
	
	public function MenuAdmin(){
		$this->id = "";
		$this->texto = "";
		$this->icone = "";
		$this->ordem = "";
		$this->bd = new MenuAdminDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getListaByGrupoAdmin( $p ){
		return $this->bd->getListaByGrupoAdmin($p);
	}

	public function getByUrl(){
		return $this->bd->getByUrl($this);
	}

	public function atualizar(){
		return $this->bd->atualizar($this);
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new MenuAdmin();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new MenuAdmin();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

	public static function _getListaByGrupoAdmin( $p_cod_grupo ){
		$obj = new MenuAdmin();
		return ( $obj->getListaByGrupoAdmin( $p_cod_grupo ) ); 
	}

}

class MenuAdminDAO extends BaseDAO{
	
	public function cadastrar(MenuAdmin $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						menu_admin( icone, texto, ordem, codprojeto )
					VALUES('". $this->con->real_escape_string($p->icone) ."'
						,'". $this->con->real_escape_string($p->texto) ."'
						,'". $this->con->real_escape_string($p->ordem) ."'
						, ". $this->codProjeto.
					");";
				if($q = $this->con->query($str_q))
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

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						icone, 
						texto, 
						ordem
					FROM 
						menu_admin 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->icone = $obj->icone;
						$p->texto = $obj->texto;
						$p->ordem = $obj->ordem;
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

	public function getListaByGrupoAdmin( $p_cod_grupo ){
		try {
			if($this->abreConexao()){
				$str_q = "
				select distinct 
					m.id,
					m.icone,
					m.texto,
					m.ordem 
				from 
					grupo_pagina_admin gp
					inner join pagina_admin p on p.id = gp.cod_pagina_admin and p.ativo = 1 and p.bloqueado = 0
					inner join menu_admin m on p.cod_menu_admin = m.id and m.ativo = 1
				where
					gp.cod_grupo_admin = ".$this->con->real_escape_string( $p_cod_grupo )."
					and m.codprojeto = " . $this->codProjeto ." 
				order by ordem asc, texto asc";

				if( $q = $this->con->query( $str_q ) ){
					$lista = array();
					if( $q->num_rows > 0 ){
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new MenuAdmin();
							$p->id = $obj->id;
							$p->icone = $obj->icone;
							$p->texto = $obj->texto;
							$p->ordem = $obj->ordem;
							$lista[] = $p;
						}
					}
					return $lista;
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

	public function atualizar($p){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						menu_admin
					SET 
						icone = '". $this->con->real_escape_string($p->icone) ."'
						, texto = '". $this->con->real_escape_string($p->texto) ."'
						, ordem = '". $this->con->real_escape_string($p->ordem) ."'
					WHERE 
						id = '".$this->con->real_escape_string($p->id)."' 
						AND ativo = 1
						AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
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
	
	public function deletar(MenuAdmin $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE menu_admin
						SET ativo = 0
						WHERE 
							id = '".$this->con->real_escape_string($obj->id)."' 
							AND ativo = 1
							AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
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
}

?>