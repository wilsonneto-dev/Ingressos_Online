<?php 

class SqlSelect{
	public $html;
	public $valorSelecionado;
	public $campoValor;
	public $campoTexto;
	public $nome;
	public $classe;
	public $bd;
	public $contador;
	public $extra;
	public $sql;
	
	public function SqlSelect($sql = "") {
		$this->html = "";
		$this->nome = "";
		$this->classe = "";
		$this->valorSelecionado = "";
		$this->campoValor = "valor";
		$this->campoTexto = "texto";
		$this->contador = 0;
		$this->extra = "";
		$this->sql = $sql;
		$this->bd = new SqlSelectDAO();
	}

	public function exec() {
		return $this->bd->exec($this);
	}
	
}

class SqlSelectDAO extends BaseDAO{
	public function exec(SqlSelect $select) {
 		try {
			if($this->abreConexao()){
				if($q = $this->con->query($select->sql)){
					if(!$q->num_rows == 0 || 1){
						$select->html = "		<select class=\" form-control ".$select->classe."\" name=\"".$select->nome."\" id=\"".$select->nome."\">";
						$select->html .= $select->extra;
						$select->contador = 0;
						
						while($obj = $q->fetch_array()){
							$txtItem = "<option value=\"#valor\" #selecionado>#texto</optinon>";
							$select->contador++;
							$txtItem = str_replace("#valor",htmlspecialchars($obj[$select->campoValor]),$txtItem);
							if($obj[$select->campoValor] == $select->valorSelecionado)
								$txtItem = str_replace("#selecionado","selected=\"\selected\"",$txtItem);
							else
								$txtItem = str_replace("#selecionado","",$txtItem);
							$txtItem = str_replace("#texto",htmlspecialchars($obj[$select->campoTexto]),$txtItem);
							$select->html .= stripslashes($txtItem);
						}
						$select->html .= "</select>";
						return true;
					}
					return false;	
				}
				else
					throw new Exception("erro ao executar query<br />".$select->sql);
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch (Exception $e) {
			$errMsg = "exceção: ". $e->getMessage()."\n<br />error: ".
					$this->con->error ."\n<br />data:". time().
					"\n<br />ip: ". $_SERVER["REMOTE_ADDR"] ;
			echo "$errMsg";
			return false;
		}
 	}
}

?>
