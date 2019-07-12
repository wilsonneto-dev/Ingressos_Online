<?php

class Nota{

	public $id;
	public $titulo;
	public $cod_nota_tipo;
	public $texto;
	public $foto;
	public $data;
	public $intro;
	private $bd;
	
	public function Nota(){
		$this->id = '';
		$this->cod_nota_tipo = '';
		$this->titulo = "";
		$this->texto = "";
		$this->data = "";
		$this->bd = new NotaDAO();
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
		$obj = new Nota();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new Nota();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

}

class NotaDAO extends BaseDAO{
	
	public function cadastrar(Nota $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					nota( 
						titulo, 
						cod_nota_tipo, 
						texto, 
						foto, 
						data, 
						intro,
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->titulo) ."'
						,'". $this->con->real_escape_string($p->cod_nota_tipo) ."'
						,'". $this->con->real_escape_string($p->texto) ."'
						,'". $this->con->real_escape_string($p->foto) ."'
						,str_to_date('". $this->con->real_escape_string( $p->data->format('d/m/Y') ) ."','%d/%m/%Y')
						,'". $this->con->real_escape_string($p->intro) ."'
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
						titulo, 
						cod_nota_tipo, 
						texto, 
						data 
					FROM 
						nota 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->titulo = $obj->titulo;
						$p->cod_nota_tipo = $obj->cod_nota_tipo;
						$p->texto = $obj->texto;
						if( is_null( $obj->data) ) $p->data = null;
						else $p->data = new DateTime( $obj->data );
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
						nota
					SET 
						titulo = '". $this->con->real_escape_string($p->titulo) ."'
						, cod_nota_tipo = '". $this->con->real_escape_string($p->cod_nota_tipo) ."'
						, texto = '". $this->con->real_escape_string($p->texto) ."'
						, data = str_to_date('". $this->con->real_escape_string( $p->data->format('d/m/Y') ) ."','%d/%m/%Y')
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
	
	public function deletar(Nota $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE nota
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