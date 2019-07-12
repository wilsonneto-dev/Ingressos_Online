<?php

//Classe BannerTopo e BannerTopoDAO
//06 de Maio de 2012

class BannerTopo{

	public $id;
	public $imagem;
	public $link;
	public $data_sair;
	public $entrar_em;
	public $visivel;
	public $descricao;
	public $botao;
	public $ordem;
	public $quantidade_click;
	public $cod_banner_tipo;
	private $bd;
	
	public function BannerTopo(){
		$this->link = '';
		$this->imagem = "";
		$this->data_sair = "";
		$this->entrar_em = "";
		$this->visivel = "";
		$this->descricao = "";
		$this->botao = "";
		$this->ordem = "";
		$this->quantidade_click = "";
		$this->cod_banner_tipo = "";
		$this->bd = new BannerTopoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function atualizar(){
		return $this->bd->atualizar($this);
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	public function click(){
		return $this->bd->click($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new BannerTopo();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}


}

class BannerTopoDAO extends BaseDAO{
	
	public function cadastrar(BannerTopo $p){
		try {
			if($this->abreConexao()){
				$str_q = "INSERT INTO 
						banner_topo( descricao, botao, ordem, link, data_sair, entrar_em, visivel, imagem, codprojeto)
						VALUES('". $this->con->real_escape_string($p->descricao) ."'
						,'". $this->con->real_escape_string($p->botao) ."'
						,'". $this->con->real_escape_string($p->ordem) ."'
						,'". $this->con->real_escape_string($p->link) ."'
						, str_to_date('". $this->con->real_escape_string( $p->data_sair->format('d/m/Y') ) ."','%d/%m/%Y')
						, str_to_date('". $this->con->real_escape_string($p->entrar_em->format('d/m/Y')) ."','%d/%m/%Y')
						,'". $this->con->real_escape_string( ( $p->visivel == '1' ) ? '1' : '0' ) ."'
						,'". $this->con->real_escape_string($p->imagem) ."'
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
						descricao, 
						botao, 
						ordem, 
						link,
						data_sair, 
						entrar_em, 
						imagem, 
						visivel,
						quantidade_click
					FROM 
						banner_topo 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->descricao = $obj->descricao;
						$p->botao = $obj->botao;
						if( is_null( $obj->data_sair) ) $p->data_sair = null;
						else $p->data_sair = new DateTime( $obj->data_sair );
						if( is_null( $obj->entrar_em) ) $p->entrar_em = null;
						else $p->entrar_em = new DateTime( $obj->entrar_em );
						$p->visivel = $obj->visivel;
						$p->ordem = $obj->ordem;
						$p->link = $obj->link;
						$p->imagem = $obj->imagem;
						$p->quantidade_click = $obj->quantidade_click;
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
						banner_topo
					SET 
						descricao = '". $this->con->real_escape_string($p->descricao) ."'
						, botao = '". $this->con->real_escape_string($p->botao) ."'
						, ordem = '". $this->con->real_escape_string($p->ordem) ."'
						, link = '". $this->con->real_escape_string($p->link) ."'
						, data_sair = str_to_date('". $this->con->real_escape_string( $p->data_sair->format('d/m/Y') ) ."','%d/%m/%Y')
						, entrar_em = str_to_date('". $this->con->real_escape_string($p->entrar_em->format('d/m/Y')) ."','%d/%m/%Y')
						, imagem = '". $this->con->real_escape_string($p->imagem) ."'
						, visivel = '". $this->con->real_escape_string( ( $p->visivel == '1' ) ? '1' : '0'  ) ."'
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

	public function click( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						banner_topo
					SET 
						quantidade_click = ( quantidade_click + 1 )
					WHERE 
						id = '".$this->con->real_escape_string( $p->id )."' 
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
	
	public function deletar(BannerTopo $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE 
							banner_topo
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