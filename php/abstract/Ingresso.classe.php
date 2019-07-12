<?php

class Ingresso{

	public $id;
	public $titulo;
	public $descricao;
	public $data_entrar;
	public $data_sair;
	public $visivel;
	public $venda_suspensa;
	public $valor;
	public $cor_legenda;
	public $cod_evento;
	public $cod_controle_lote;
	public $valida_quantidade;
	public $quantidade_disponivel;

	public $taxa_percentual;
	public $taxa_fixa;
	public $ordem;
	
	private $bd;
	
	public function Ingresso(){
		$this->id = '';
		$this->descricao = '';
		$this->titulo = "";
		$this->data_entrar = "";
		$this->data_sair = "";
		$this->visivel = "";
		$this->venda_suspensa = 0;
		$this->valor = "";
		$this->cor_legenda = "";
		$this->cod_evento = "";
		$this->cod_controle_lote = 0;
		$this->valida_quantidade = 0;
		$this->quantidade_disponivel = 0;
		$this->taxa_percentual = "";
		$this->taxa_fixa = "";
		$this->ordem = "";
		$this->bd = new IngressoDAO();
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

	// estáticos 
	public static function _get( $p_id ){
		$obj = new Ingresso();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new Ingresso();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

}

class IngressoDAO extends BaseDAO{
	
	public function cadastrar(Ingresso $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					ingresso( 
						descricao, 
						data_entrar, 
						data_sair, 
						visivel, 
						valor, 
						cod_evento, 
						taxa_percentual,
						taxa_fixa,
						ordem,
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->descricao) ."'
						, str_to_date('". $this->con->real_escape_string( $p->data_entrar->format('d/m/Y') ) ."','%d/%m/%Y')
						, str_to_date('". $this->con->real_escape_string( $p->data_sair->format('d/m/Y') ) ."','%d/%m/%Y')
						,'". $this->con->real_escape_string($p->visivel) ."'
						,'". $this->con->real_escape_string($p->valor) ."'
						,'". $this->con->real_escape_string($p->cod_evento) ."'
						,'". $this->con->real_escape_string($p->taxa_percentual) ."'
						,'". $this->con->real_escape_string($p->taxa_fixa) ."'
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						titulo, 
						descricao, 
						data_entrar, 
						data_sair, 
						visivel, 
						venda_suspensa, 
						valor, 
						cor_legenda, 
						cod_evento, 
						cod_controle_lote, 
						valida_quantidade,
						quantidade_disponivel,
						taxa_percentual,
						taxa_fixa,
						ordem,
						codprojeto 
					FROM 
						ingresso 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0) {
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->titulo = $obj->titulo;
						$p->descricao = $obj->descricao;
						$p->data_entrar = new DateTime( $obj->data_entrar );
						$p->data_sair = new DateTime( $obj->data_sair );
						$p->visivel = $obj->visivel;
						$p->venda_suspensa = $obj->venda_suspensa;
						$p->valor = $obj->valor;
						$p->cor_legenda = $obj->cor_legenda;
						$p->cod_evento = $obj->cod_evento;
						$p->cod_controle_lote = $obj->cod_controle_lote;
						$p->valida_quantidade = $obj->valida_quantidade;
						$p->quantidade_disponivel = $obj->quantidade_disponivel;
						$p->taxa_percentual = $obj->taxa_percentual;
						$p->taxa_fixa = $obj->taxa_fixa;
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function atualizar($p){
		try {
			if( $this->abreConexao() ) {
				$str_q = 
					"UPDATE 
						ingresso
					SET 
						titulo = '". $this->con->real_escape_string($p->titulo) ."'
						, descricao = '". $this->con->real_escape_string($p->descricao) ."'
						, data_entrar = str_to_date('". $this->con->real_escape_string( $p->data_entrar->format('d/m/Y') ) ."','%d/%m/%Y')
						, data_sair = str_to_date('". $this->con->real_escape_string( $p->data_sair->format('d/m/Y') ) ."','%d/%m/%Y')
						, visivel = '". $this->con->real_escape_string($p->visivel) ."'
						, valor = '". $this->con->real_escape_string($p->valor) ."'
						, cod_evento = '". $this->con->real_escape_string($p->cod_evento) ."'
						, taxa_percentual = '". $this->con->real_escape_string($p->taxa_percentual) ."'
						, taxa_fixa = '". $this->con->real_escape_string($p->taxa_fixa) ."'
						, ordem = '". $this->con->real_escape_string($p->ordem) ."'
					WHERE 
						id = '".$this->con->real_escape_string($p->id)."' 
						AND ativo = 1
						AND codprojeto = ".$this->codProjeto.";";
				if ( $q = $this->con->query($str_q) )
					return true;
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao");	
		} catch (Exception $e) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
	public function deletar(Ingresso $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE
						ingresso
					SET 
						ativo = 0
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}
}

?>