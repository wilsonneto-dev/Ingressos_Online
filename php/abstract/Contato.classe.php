<?php

//Classe Contato e ContatoDAO
//06 de Maio de 2012

class Contato{

	public $id;
	public $nome;
	public $email;
	public $telefone;
	public $assunto;
	public $mensagem;
	public $tipo;
	public $cod_cidade;
	public $pagina;
	public $ip;
	public $cod_usuario;
	private $bd;
	
	public function Contato(){
		$this->nome = "";
		$this->email = '';
		$this->telefone = "";
		$this->assunto = "";
		$this->mensagem = "";
		$this->tipo = "";
		$this->cod_cidade = "";
		$this->pagina = "";
		$this->ip = "";
		$this->cod_usuario = "";
		$this->bd = new ContatoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

}

class ContatoDAO extends BaseDAO{
	
	public function cadastrar(Contato $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO contato( 
						nome, 
						email, 
						telefone, 
						assunto, 
						mensagem,
						tipo, 
						cod_cidade, 
						pagina, 
						ip,
						cod_usuario, 
						codprojeto
					)VALUES(
						'". $this->con->real_escape_string($p->nome) ."'
						,'". $this->con->real_escape_string($p->email) ."'
						,'". $this->con->real_escape_string($p->telefone) ."'
						,'". $this->con->real_escape_string( $p->assunto ) ."'
						,'". $this->con->real_escape_string($p->mensagem) ."'
						,'". $this->con->real_escape_string($p->tipo) ."'
						, ". $this->con->real_escape_string( ( empty( $p->cod_cidade ) ) ?  "NULL" :  ("'".$p->cod_cidade."'")  ) ."
						,'". $_SERVER['REQUEST_URI'] ."'
						,'". $_SERVER['REMOTE_ADDR'] ."'
						, ". $this->con->real_escape_string( ( empty( $p->cod_usuario ) ) ?  "NULL" :  ("'".$p->cod_usuario."'")  ) ."
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
	
}

?>