<?php

class PaginaAdmin{

	public $id;
	public $descricao;
	public $posicao;
	public $url;
	public $target;
	public $cod_menu_admin;
	public $bloqueado;
	public $permissao;
	private $bd;
	
	public function PaginaAdmin(){
		$this->id = '';
		$this->posicao = '';
		$this->descricao = "";
		$this->url = "";
		$this->target = "";
		$this->cod_menu_admin = "";
		$this->bloqueado = "";
		$this->permissao = "";
		$this->bd = new PaginaAdminDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getListaByGrupoMenu( $p_cod_grupo, $p_cod_menu ){
		return $this->bd->getListaByGrupoMenu( $p_cod_grupo, $p_cod_menu );
	}

	public function getListaPermissoesByGrupoMenu( $p_cod_grupo ){
		return $this->bd->getListaPermissoesByGrupoMenu( $p_cod_grupo );
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
		$obj = new PaginaAdmin();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getListaByGrupoMenu( $p_cod_grupo, $p_cod_menu )
	{
		$obj = new PaginaAdmin();
		return $obj->getListaByGrupoMenu( $p_cod_grupo, $p_cod_menu );
	}

	public static function _deletar( $p_id ){
		$obj = new PaginaAdmin();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

	public static function _getListaPermissoesByGrupoMenu( $p_cod_grupo )
	{
		$obj = new PaginaAdmin();
		return $obj->getListaPermissoesByGrupoMenu( $p_cod_grupo );
	}

}

class PaginaAdminDAO extends BaseDAO{
	
	public function cadastrar(PaginaAdmin $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					pagina_admin( 
						descricao, 
						posicao, 
						url, 
						target, 
						cod_menu_admin, 
						bloqueado,
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->descricao) ."'
						,'". $this->con->real_escape_string($p->posicao) ."'
						,'". $this->con->real_escape_string($p->url) ."'
						,'". $this->con->real_escape_string($p->target) ."'
						,'". $this->con->real_escape_string($p->cod_menu_admin) ."'
						,'". $this->con->real_escape_string($p->bloqueado) ."'
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
						descricao, 
						posicao, 
						url, 
						target, 
						cod_menu_admin, 
						bloqueado 
					FROM 
						pagina_admin 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->descricao = $obj->descricao;
						$p->posicao = $obj->posicao;
						$p->url = $obj->url;
						$p->target = $obj->target;
						$p->cod_menu_admin = $obj->cod_menu_admin;
						$p->bloqueado = $obj->bloqueado;
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

	public function getListaPermissoesByGrupoMenu( $p_cod_grupo ){
		try {
			if($this->abreConexao()){
				$str_q = "
					select distinct
						p.permissao
					from 
						grupo_pagina_admin gp
						inner join pagina_admin p 
							on p.id = gp.cod_pagina_admin 
							and p.ativo = 1 
							and p.bloqueado = 0
					where
						gp.cod_grupo_admin = ".$p_cod_grupo."	
						and p.codprojeto = 1 
					order by 
						p.permissao asc";
				if($q = $this->con->query($str_q)){
					$lista = array();
					if($q->num_rows > 0){
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$lista[] = $obj->permissao;
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

	public function getListaByGrupoMenu( $p_cod_grupo, $p_cod_menu ){
		try {
			if($this->abreConexao()){
				$str_q = "
					select 
						p.id,
						p.descricao,
						p.posicao,
						p.url,
						p.target,
						p.cod_menu_admin,
						p.bloqueado,
						p.permissao
					from 
						grupo_pagina_admin gp
						inner join pagina_admin p on p.id = gp.cod_pagina_admin and p.ativo = 1 and p.bloqueado = 0
					where
						gp.cod_grupo_admin = ".$this->con->real_escape_string( $p_cod_grupo  )."	
						and p.cod_menu_admin = ".$this->con->real_escape_string( $p_cod_menu  )."
						and p.codprojeto = " . $this->codProjeto ." 
					order by 
						p.posicao asc, 
						p.descricao asc";
				if($q = $this->con->query($str_q)){
					$lista = array();
					if($q->num_rows > 0){
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new PaginaAdmin();
							$p->id = $obj->id;
							$p->descricao = $obj->descricao;
							$p->posicao = $obj->posicao;
							$p->url = $obj->url;
							$p->target = $obj->target;
							$p->cod_menu_admin = $obj->cod_menu_admin;
							$p->bloqueado = $obj->bloqueado;
							$p->permissao = $obj->permissao;
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

	public function getByUrl($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						descricao, 
						posicao, 
						url, 
						target, 
						cod_menu_admin, 
						bloqueado
					FROM 
						pagina_admin 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND descricao = '" . $this->con->real_escape_string( $p->descricao ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->descricao = $obj->descricao;
						$p->posicao = $obj->posicao;
						$p->url = $obj->url;
						$p->target = $obj->target;
						$p->cod_menu_admin = $obj->cod_menu_admin;
						$p->bloqueado = $obj->bloqueado;
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

	public function atualizar($p){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						pagina_admin
					SET 
						descricao = '". $this->con->real_escape_string($p->descricao) ."'
						, posicao = '". $this->con->real_escape_string($p->posicao) ."'
						, url = '". $this->con->real_escape_string($p->url) ."'
						, target = '". $this->con->real_escape_string($p->target) ."'
						, cod_menu_admin = '". $this->con->real_escape_string($p->cod_menu_admin) ."'
						, bloqueado = '". $this->con->real_escape_string($p->bloqueado) ."'
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
	
	public function deletar(PaginaAdmin $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE pagina_admin
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