<?php

class NewsletterEvento{

	public $id;
	public $email;
	public $nome;
	public $evento;
	public $data_cancelamento;
	public $data_cadastro;
	public $chave;
	private $bd;
	
	public function NewsletterEvento(){
		$this->id = "";
		$this->nome = "";
		$this->email = "";
		$this->evento = "";
		$this->data_cancelamento = "";
		$this->data_cadastro = "";
		$this->chave = "";
		$this->bd = new NewsletterEventoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getByEmailChave(){
		return $this->bd->getByEmailChave($this);
	}

	public function getByEmail(){
		return $this->bd->getByEmail($this);
	}

	public function atualizar(){
		return $this->bd->atualizar($this);
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	public function cancelarByEmail(){
		return $this->bd->cancelarByEmail( $this );
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new NewsletterEvento();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByEmailChave( $p_email, $p_chave ){
		$obj = new NewsletterEvento();
		$obj->chave = $p_chave;
		$obj->email = $p_email;
		if ( $obj->getByEmailChave() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new NewsletterEvento();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}


}

class NewsletterEventoDAO extends BaseDAO{
	
	public function cadastrar(NewsletterEvento $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO newsletter_evento( 
						email, 
						nome, 
						evento, 
						chave,
						codprojeto
					) VALUES(
						'". $this->con->real_escape_string($p->email) ."'
						,'". $this->con->real_escape_string($p->nome) ."'
						,'". $this->con->real_escape_string($p->evento) ."'
						,'". $this->con->real_escape_string($p->chave) ."'
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
						email, 
						nome, 
						data_cancelamento, 
						chave ,
						datacadastro
					FROM 
						newsletter_evento 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->email = $obj->email;
						$p->nome = $obj->nome;
						$p->chave = $obj->chave;
						if( is_null( $obj->data_cancelamento) ) $p->data_cancelamento = null;
						else $p->data_cancelamento = new DateTime( $obj->data_cancelamento );
						if( is_null( $obj->datacadastro) ) $p->data_cadastro = null;
						else $p->data_cadastro = new DateTime( $obj->datacadastro );
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

	public function getByEmailChave($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						email, 
						nome, 
						evento, 
						data_cancelamento, 
						chave 
					FROM 
						newsletter_evento 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND email = '" . $this->con->real_escape_string( $p->email ) . "' 
						AND chave = '" . $this->con->real_escape_string( $p->chave ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->email = $obj->email;
						$p->evento = $obj->evento;
						$p->nome = $obj->nome;
						$p->chave = $obj->chave;
						if( is_null( $obj->data_cancelamento) ) $p->data_cancelamento = null;
						else $p->data_cancelamento = new DateTime( $obj->data_cancelamento );
						if( is_null( $obj->datacadastro) ) $p->data_cadastro = null;
						else $p->data_cadastro = new DateTime( $obj->datacadastro );
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

	public function getByEmail($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						email, 
						nome, 
						evento, 
						data_cancelamento, 
						chave 
					FROM 
						newsletter_evento 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND email = '" . $this->con->real_escape_string( $p->email ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->email = $obj->email;
						$p->evento = $obj->evento;
						$p->nome = $obj->nome;
						$p->chave = $obj->chave;
						if( is_null( $obj->data_cancelamento) ) $p->data_cancelamento = null;
						else $p->data_cancelamento = new DateTime( $obj->data_cancelamento );
						if( is_null( $obj->datacadastro) ) $p->data_cadastro = null;
						else $p->data_cadastro = new DateTime( $obj->datacadastro );
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
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						newsletter_evento
					SET 
						email = '". $this->con->real_escape_string($p->email) ."'
						, nome = '". $this->con->real_escape_string($p->nome) ."'
						, chave = '". $this->con->real_escape_string($p->chave) ."'
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
	public function deletar(NewsletterEvento $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE 
							newsletter_evento
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}
	
	public function cancelarByEmail(NewsletterEvento $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE 
							newsletter_evento
						SET 
							data_cancelamento = CURRENT_TIMESTAMP
						WHERE 
							email = '".$this->con->real_escape_string($obj->email)."'
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