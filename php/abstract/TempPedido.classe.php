<?php

//Classe TempPedido e TempPedidoDAO
//06 de Maio de 2012

class TempPedido{

	public $id;
	public $evento;
	public $nome;
	public $sobrenome;
	public $email;
	public $telefone;
	public $uf;
	public $cidade;
	public $cpf;
	public $status;
	public $cod_status;
	public $ingresso_entregue;
	public $cod_pagseguro;
	public $cod_pedido;
	public $data_cadastro;
	
	public $hash;
	public $codigo_seguranca;

	public $valor_ingressos;
	public $valor_desconto;
	public $valor_acrescimo;
	public $valor_taxa_gateway;
	public $valor_liquido;
	public $valor_total_pago;
	public $valor_pedido;

	public $ref;

	private $bd;
	
	public function TempPedido(){
		$this->id = '';
		$this->hash = '';
		$this->codigo_seguranca = '';
		$this->evento = '';
		$this->nome = '';
		$this->sobrenome = "";
		$this->email = "";
		$this->telefone = "";
		$this->uf = "";
		$this->cidade = "";
		$this->cpf = "";
		$this->status = "";
		$this->cod_status = "";
		$this->ingresso_entregue = "";
		$this->cod_pagseguro = "";
		$this->cod_pedido = "";
		$this->data_cadastro = "";
		$this->valor_ingressos = 0;
		$this->valor_desconto = 0;
		$this->valor_acrescimo = 0;
		$this->valor_taxa_gateway = 0;
		$this->valor_liquido = 0;
		$this->valor_total_pago = 0;
		$this->valor_pedido = 0;
		$this->ref = "";
		$this->hash = "";
		$this->codigo_seguranca = "";
		$this->bd = new TempPedidoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get_by_id(){
		return $this->bd->get_by_id($this);
	}

	public function get_by_cod_pedido(){
		return $this->bd->get_by_cod_pedido($this);
	}
	public function get_by_cod_pedido_email(){
		return $this->bd->get_by_cod_pedido_email( $this );
	}

	public function get_by_cod_pagseguro(){
		return $this->bd->get_by_cod_pagseguro($this);
	}

	public function atualizar_cod_pagseguro() {
		return $this->bd->atualizar_cod_pagseguro($this);
	}

	public function atualizar_status() {
		return $this->bd->atualizar_status($this);
	}

	public static function _proximo_codigo (  ) {
		$obj = new TempPedido();
		$obj->proximo_codigo();
		return $obj->cod_pedido;
	}

	public function proximo_codigo(){
		return $this->bd->proximo_codigo($this);
	}

	public function enviou_ao_pagseguro(){
		return $this->bd->enviou_ao_pagseguro( $this );
	}

}

class TempPedidoDAO extends BaseDAO {

	public function proximo_codigo(TempPedido $p){
		try {
			if($this->abreConexao()){
				$str_q = "select ( ifnull( max(id), 0 ) + 1 ) as proximo_pedido from temp_pedido;";
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->cod_pedido = $obj->proximo_pedido;
						return true;
					}
					else return false;	
				}
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

	public function cadastrar(TempPedido $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					temp_pedido( 
						evento, 
						nome, 
						sobrenome, 
						email, 
						telefone, 
						uf, 
						cidade, 
						cpf, 
						status, 
						cod_status,
						ingresso_entregue, 
						cod_pagseguro, 
						cod_pedido, 
						valor_ingressos,
						valor_pedido,
						valor_desconto,
						valor_acrescimo,
						hash,
						codigo_seguranca,
						ref,
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->evento) ."'
						,'". $this->con->real_escape_string($p->nome) ."'
						,'". $this->con->real_escape_string($p->sobrenome) ."'
						,'". $this->con->real_escape_string($p->email) ."'
						,'". $this->con->real_escape_string($p->telefone) ."'
						,'". $this->con->real_escape_string($p->uf) ."'
						,'". $this->con->real_escape_string($p->cidade) ."'
						,'". $this->con->real_escape_string($p->cpf) ."'
						,'". $this->con->real_escape_string($p->status) ."'
						,'". $this->con->real_escape_string($p->cod_status) ."'
						,'". $this->con->real_escape_string($p->ingresso_entregue) ."'
						,'". $this->con->real_escape_string($p->cod_pagseguro) ."'
						,'". $this->con->real_escape_string($p->cod_pedido) ."'
						,'". $this->con->real_escape_string($p->valor_ingressos) ."'
						,'". $this->con->real_escape_string($p->valor_pedido) ."'
						,'". $this->con->real_escape_string($p->valor_desconto) ."'
						,'". $this->con->real_escape_string($p->valor_acrescimo) ."'
						,'". $this->con->real_escape_string($p->hash) ."'
						,'". $this->con->real_escape_string($p->codigo_seguranca) ."'
						,'". $this->con->real_escape_string($p->ref) ."'
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

	public function get_by_id($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						evento, 
						nome, 
						sobrenome, 
						email, 
						telefone, 
						uf, 
						cidade, 
						cpf, 
						status, 
						cod_status,
						ingresso_entregue, 
						cod_pagseguro, 
						cod_pedido,
						valor_ingressos,
						valor_pedido,
						valor_desconto,
						valor_acrescimo,
						valor_taxa_gateway,
						valor_liquido,
						valor_total_pago,
						data_cadastro, 
						codprojeto 
					FROM 
						temp_pedido 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->evento = $obj->evento;
						$p->nome = $obj->nome;
						$p->sobrenome = $obj->sobrenome;
						$p->email = $obj->email;
						$p->telefone = $obj->telefone;
						$p->uf = $obj->uf;
						$p->cidade = $obj->cidade;
						$p->cpf = $obj->cpf;
						$p->status = $obj->status;
						$p->cod_status = $obj->cod_status;
						$p->ingresso_entregue = $obj->ingresso_entregue;
						$p->cod_pagseguro = $obj->cod_pagseguro;
						$p->cod_pedido = $obj->cod_pedido;
						$p->valor_ingressos = $obj->valor_ingressos;
						$p->valor_pedido = $obj->valor_pedido;
						$p->valor_desconto = $obj->valor_desconto;
						$p->valor_acrescimo = $obj->valor_acrescimo;
						$p->valor_taxa_gateway = $obj->valor_taxa_gateway;
						$p->valor_liquido = $obj->valor_liquido;
						$p->valor_total_prazo = $obj->valor_total_prazo;
						$p->hash = $obj->hash;
						$p->codigo_seguranca = $obj->codigo_seguranca;
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

	public function get_by_cod_pagseguro($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						evento, 
						nome, 
						sobrenome, 
						email, 
						telefone, 
						uf, 
						cidade, 
						cpf, 
						status, 
						cod_status,
						ingresso_entregue, 
						cod_pagseguro, 
						cod_pedido,
						valor_ingressos,
						valor_pedido,
						valor_desconto,
						valor_acrescimo,
						valor_taxa_gateway,
						valor_liquido,
						valor_total_pago,
						data_cadastro, 
						hash, 
						codigo_seguranca, 
						codprojeto 
					FROM 
						temp_pedido 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND cod_pagseguro = '" . $this->con->real_escape_string( $p->cod_pagseguro ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->evento = $obj->evento;
						$p->nome = $obj->nome;
						$p->sobrenome = $obj->sobrenome;
						$p->email = $obj->email;
						$p->telefone = $obj->telefone;
						$p->uf = $obj->uf;
						$p->cidade = $obj->cidade;
						$p->cpf = $obj->cpf;
						$p->status = $obj->status;
						$p->cod_status = $obj->cod_status;
						$p->ingresso_entregue = $obj->ingresso_entregue;
						$p->cod_pagseguro = $obj->cod_pagseguro;
						$p->cod_pedido = $obj->cod_pedido;
						$p->valor_ingressos = $obj->valor_ingressos;
						$p->valor_pedido = $obj->valor_pedido;
						$p->valor_desconto = $obj->valor_desconto;
						$p->valor_acrescimo = $obj->valor_acrescimo;
						$p->valor_taxa_gateway = $obj->valor_taxa_gateway;
						$p->valor_liquido = $obj->valor_liquido;
						$p->valor_total_prazo = $obj->valor_total_prazo;
						$p->data_cadastro = $obj->data_cadastro;
						$p->hash = $obj->hash;
						$p->codigo_seguranca = $obj->codigo_seguranca;
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

	public function get_by_cod_pedido($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						evento, 
						nome, 
						sobrenome, 
						email, 
						telefone, 
						uf, 
						cidade, 
						cpf, 
						status, 
						cod_status,
						ingresso_entregue, 
						cod_pagseguro, 
						cod_pedido,
						valor_ingressos,
						valor_pedido,
						valor_desconto,
						valor_acrescimo,
						valor_taxa_gateway,
						valor_liquido,
						valor_total_pago,
						hash,
						codigo_seguranca,
						data_cadastro, 
						codprojeto 
					FROM 
						temp_pedido 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND cod_pedido = '" . $this->con->real_escape_string( $p->cod_pedido ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->evento = $obj->evento;
						$p->nome = $obj->nome;
						$p->sobrenome = $obj->sobrenome;
						$p->email = $obj->email;
						$p->telefone = $obj->telefone;
						$p->uf = $obj->uf;
						$p->cidade = $obj->cidade;
						$p->cpf = $obj->cpf;
						$p->status = $obj->status;
						$p->cod_status = $obj->cod_status;
						$p->ingresso_entregue = $obj->ingresso_entregue;
						$p->cod_pagseguro = $obj->cod_pagseguro;
						$p->cod_pedido = $obj->cod_pedido;
						$p->valor_ingressos = $obj->valor_ingressos;
						$p->valor_pedido = $obj->valor_pedido;
						$p->valor_desconto = $obj->valor_desconto;
						$p->valor_acrescimo = $obj->valor_acrescimo;
						$p->valor_taxa_gateway = $obj->valor_taxa_gateway;
						$p->valor_liquido = $obj->valor_liquido;
						$p->valor_total_pago = $obj->valor_total_pago;
						$p->hash = $obj->hash;
						$p->codigo_seguranca = $obj->codigo_seguranca;
						if( is_null( $obj->data_cadastro) ) $p->data_cadastro = null;
						else $p->data_cadastro = new DateTime( $obj->data_cadastro );
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


	public function get_by_cod_pedido_email($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						evento, 
						nome, 
						sobrenome, 
						email, 
						telefone, 
						uf, 
						cidade, 
						cpf, 
						status, 
						cod_status,
						ingresso_entregue, 
						cod_pagseguro, 
						cod_pedido,
						valor_ingressos,
						valor_pedido,
						valor_desconto,
						valor_acrescimo,
						valor_taxa_gateway,
						valor_liquido,
						valor_total_pago,
						data_cadastro, 
						hash, 
						codigo_seguranca, 
						codprojeto 
					FROM 
						temp_pedido 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND email = '" . $this->con->real_escape_string( $p->email ) ."' 
						AND codigo_seguranca = '" . $this->con->real_escape_string( $p->codigo_seguranca ) ."' 
						AND cod_pedido = '" . $this->con->real_escape_string( $p->cod_pedido ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->evento = $obj->evento;
						$p->nome = $obj->nome;
						$p->sobrenome = $obj->sobrenome;
						$p->email = $obj->email;
						$p->telefone = $obj->telefone;
						$p->uf = $obj->uf;
						$p->cidade = $obj->cidade;
						$p->cpf = $obj->cpf;
						$p->status = $obj->status;
						$p->cod_status = $obj->cod_status;
						$p->ingresso_entregue = $obj->ingresso_entregue;
						$p->cod_pagseguro = $obj->cod_pagseguro;
						$p->cod_pedido = $obj->cod_pedido;
						$p->valor_ingressos = $obj->valor_ingressos;
						$p->valor_pedido = $obj->valor_pedido;
						$p->valor_desconto = $obj->valor_desconto;
						$p->valor_acrescimo = $obj->valor_acrescimo;
						$p->valor_taxa_gateway = $obj->valor_taxa_gateway;
						$p->valor_liquido = $obj->valor_liquido;
						$p->valor_total_pago = $obj->valor_total_pago;
						$p->hash = $obj->hash;
						$p->codigo_seguranca = $obj->codigo_seguranca;
						if( is_null( $obj->data_cadastro) ) $p->data_cadastro = null;
						else $p->data_cadastro = new DateTime( $obj->data_cadastro );
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

	public function enviou_ao_pagseguro( $p )
	{
		try {
			if( $this->abreConexao() ){
				$str_q = 
					"UPDATE 
						temp_pedido
					SET 
						enviou_ao_pagseguro = 1
					WHERE 
						cod_pedido = '".$this->con->real_escape_string( $p->cod_pedido )."' 
						AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	

	public function atualizar_cod_pagseguro( $p )
	{
		try {
			if( $this->abreConexao() ){
				$str_q = 
					"UPDATE 
						temp_pedido
					SET 
						cod_pagseguro = '". $this->con->real_escape_string( $p->cod_pagseguro ) ."',
						status = '". $this->con->real_escape_string( $p->status ) ."',
						cod_status = '". $this->con->real_escape_string( $p->cod_status ) ."',
						valor_taxa_gateway = '". $this->con->real_escape_string( $p->valor_taxa_gateway ) ."',
						valor_liquido = '". $this->con->real_escape_string( $p->valor_liquido ) ."',
						valor_total_pago = '". $this->con->real_escape_string( $p->valor_total_pago ) ."'
					WHERE 
						cod_pedido = '".$this->con->real_escape_string( $p->cod_pedido )."' 
						AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
	public function atualizar_status($p){
		try {
			if( $this->abreConexao() ){
				$str_q = 
					"UPDATE 
						temp_pedido
					SET 
						cod_status = '". $this->con->real_escape_string($p->cod_status) ."'
						, status = '". $this->con->real_escape_string($p->status) ."'
					WHERE 
						cod_pedido = '".$this->con->real_escape_string($p->cod_pedido)."' 
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