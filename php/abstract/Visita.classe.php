<?php

class Visita{

	public $id;
	public $pagina;
	public $querystring;
	public $ip;
	public $request_uri;
	public $ref;
	public $cod_usuario;
	public $entidade;
	public $codigo;
	
	private $bd;
	
	public function Visita(){
		$this->id = '';
		$this->querystring = '';
		$this->pagina = "";
		$this->ip = "";
		$this->request_uri = "";
		$this->ref = "";
		$this->cod_usuario = "";
		$this->entidade = "";
		$this->codigo = "";
		$this->bd = new VisitaDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public static function _cadastrar( $entidade, $codigo, $get, $server, $cod_usuario, $pagina = "" )
	{
		$obj = new Visita();
		$obj->entidade = $entidade;
		$obj->codigo = $codigo;
		$obj->cod_usuario = $cod_usuario;
		if($pagina == "")
			$obj->pagina = (isset($get["pg"]) ? $get["pg"] : "");
		else
			$obj->pagina = $pagina;
		$obj->ref = (isset($server["HTTP_REFERER"]) ? $server["HTTP_REFERER"] : "");
		$obj->querystring = $server["QUERY_STRING"];
		$obj->ip = $server["REMOTE_ADDR"];
		$obj->request_uri = $server["REQUEST_URI"];
		$obj->cadastrar($obj);
	}

}

class VisitaDAO extends BaseDAO{
	
	public function cadastrar(Visita $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO visita( 
						pagina, 
						querystring, 
						ip, 
						request_uri, 
						ref, 
						cod_usuario, 
						entidade,
						codigo, 
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->pagina) ."'
						,'". $this->con->real_escape_string($p->querystring) ."'
						,'". $this->con->real_escape_string($p->ip) ."'
						,'". $this->con->real_escape_string($p->request_uri) ."'
						,'". $this->con->real_escape_string($p->ref) ."'
						,". $this->con->real_escape_string( ( $p->cod_usuario == "" ? "NULL" : $p->cod_usuario ) ) ."
						,'". $this->con->real_escape_string($p->entidade) ."'
						,'". $this->con->real_escape_string($p->codigo) ."'
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