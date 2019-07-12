<?php 
//mod em 22 de outubro de 2012 -> add: $striptags_intro200
//mod em 12 de janeiro de 2013 -> add: divisors

class Repeater{
	/*classe repeater*/
	public $txtItem;
	public $txtAltItem;
	public $contAlt;
	public $alt;
	public $divisorAtivo;
	public $divisorIntervalo;
	public $txtDivisor;
	public $txtTopo;
	public $txtFim;
	public $html;
	public $txtVazio;
	public $bd;
	public $contador;
	public $sql;
	public $campos;
	
	public function Repeater(
		$txtItem = "",$txtTopo = "", $txtFim = "",$campos = "",$sql = ""
	){
		
		$this->divisorAtivo = false;
		$this->txtDivisor = "";
		$this->divisorIntervalo = "";
		$this->txtItem = $txtItem;
		$this->txtTopo = $txtTopo;
		$this->txtFim = $txtFim;
		$this->campos = $campos;
		$this->sql = $sql;
		$this->html = "";
		$this->txtVazio = "Sem Registros";
		$this->txtAltItem = "";
		$this->contador = 0;
		$this->alt = false;
		$this->bd = new RepeaterDAO();
	}

	public function exec(){
		return $this->bd->repeaterExec($this);
	}
	
}

class RepeaterDAO extends BaseDAO{
	public function repeaterExec(Repeater $repeater) {
 		try {
			if($this->abreConexao()){
				if($q = $this->con->query($repeater->sql)){
					if(!$q->num_rows == 0){
						$repeater->html = "";
						$repeater->contador = 0;
						$repeater->html .= $repeater->txtTopo;
						
						$arrCampos = preg_split("/;/", $repeater->campos);
						
						while($obj = $q->fetch_array()){
							if($repeater->alt == true){
								if(!($repeater->contAlt++ % 2)) 
									$txtItem = $repeater->txtItem;
								else 
									$txtItem = $repeater->txtAltItem;
							}
							else $txtItem = $repeater->txtItem;
							
							// contador
							$repeater->contador++;
							$txtItem = str_replace("#contador",	$repeater->contador, $txtItem);
							
							if($repeater->divisorAtivo)
							{
								if( ( $repeater->contador - 1 != 0 ) && ( ( $repeater->contador - 1 ) % $repeater->divisorIntervalo) == 0 )
								{
									$repeater->html .= stripslashes( $repeater->txtDivisor );
								}
							}
							
							foreach($arrCampos as $campo){
								$txtItem = str_replace(
									"#$campo", 
									htmlspecialchars($obj[$campo]),
									$txtItem
								);
								$txtItem = str_replace(
									"#nl2br_$campo", 
									nl2br(htmlspecialchars($obj[$campo])),
									$txtItem
								);
								$txtItem = str_replace(
									"#url_$campo", 
									SEO::gerar_link(htmlspecialchars($obj[$campo])),
									$txtItem
								);
								$txtItem = str_replace(
									"#html_$campo", 
									$obj[$campo],
									$txtItem
								);
								$txtItem = str_replace(
									"#html_nl2br_$campo", 
									nl2br($obj[$campo]),
									$txtItem
								);
								$txtItem = str_replace(
									"#no_quotes_$campo", 
									str_replace( "\"", "&quot;", $obj[$campo] ),
									$txtItem
								);
								$str_intro = substr($obj[$campo],0,200);
								$txtItem = str_replace(
									"#intro200_$campo", 
									substr($str_intro,0,strrpos($str_intro," "))."...",
									$txtItem
								);
								$str_intro = substr(strip_tags($obj[$campo]),0,200);
								$txtItem = str_replace(
									"#strip_tags_intro200_$campo", 
									substr($str_intro,0,strrpos($str_intro," "))."...",
									$txtItem
								);
								$str_intro = substr($obj[$campo],0,400);
								$txtItem = str_replace(
									"#intro400_$campo", 
									substr($str_intro,0,strrpos($str_intro," "))."...",
									$txtItem
								);
								/*para pegar thumb*/
								$caminho = substr($obj[$campo],0,strrpos($obj[$campo],"/")+1);
								$nome = substr($obj[$campo],strrpos($obj[$campo],"/")+1);
								$thumb = $caminho."thumb_".$nome;
								$txtItem = str_replace(
									"#thumb_$campo", 
									$thumb,
									$txtItem
								);
								
							}
								
							$repeater->html .= stripslashes($txtItem);
						}
						$repeater->html .= $repeater->txtFim; 
					}else{
						$repeater->html = $repeater->txtVazio;
					}
					return true;	
				}
				else
					throw new Exception("erro ao executar query<br />".$repeater->sql);
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
