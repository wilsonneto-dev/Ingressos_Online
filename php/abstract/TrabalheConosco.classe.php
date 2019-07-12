<?php

//Classe TrabalheConosco e TrabalheConoscoDAO

class TrabalheConosco{

	public $id;
	public $nome;
	public $email;
	public $telefone;
	public $curriculum;
	public $sobre;
	public $linkedin;
	public $especialidade;
	public $pagina;
	public $ip;
	public $cod_usuario;
	private $bd;
	
	public function TrabalheConosco(){
		$this->nome = "";
		$this->email = '';
		$this->telefone = "";
		$this->curriculum = "";
		$this->sobre = "";
		$this->linkedin = "";
		$this->especialidade = "";
		$this->bd = new TrabalheConoscoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

}

class TrabalheConoscoDAO extends BaseDAO{
	
	public function cadastrar(TrabalheConosco $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO trabalhe_conosco( 
						nome, 
						email, 
						telefone, 
						curriculum, 
						sobre,
						linkedin, 
						especialidade, 
						codprojeto
					)VALUES(
						'". $this->con->real_escape_string($p->nome) ."'
						,'". $this->con->real_escape_string($p->email) ."'
						,'". $this->con->real_escape_string($p->telefone) ."'
						,'". $this->con->real_escape_string( $p->curriculum ) ."'
						,'". $this->con->real_escape_string($p->sobre) ."'
						,'". $this->con->real_escape_string($p->linkedin) ."'
						,'". $this->con->real_escape_string($p->especialidade) ."'
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
	
}

?>