<?php

class Upload{
	public $url;
	public $nome;
	public $pastaDestino;
	public $erro;
	public $tipo;
	public $tam;
	public $extensao;
	public $tmp;
	public $fileNome;

	public function Upload($nome,$pastaDestino,$file, $i = -1)
	{
		$this->nome = $nome;
		$this->pastaDestino = $pastaDestino;
		$this->erroCod = $file["error"];
		$this->tam = $file["size"];
		$this->tipo = $file["type"];
		$this->tmp = $file["tmp_name"];
		$this->fileNome = $file["name"];
		if($i == -1)
			$this->extensao = strrchr($this->fileNome,".");
		else
			$this->extensao = strrchr($this->fileNome[$i],".");
		$this->url = $this->pastaDestino."/".$this->nome.$this->extensao;
	}

	public function getErro(){
		$msg = "";
		switch ($erro) {
			case 0:
			$msg = "Nao ha erro";
			break;
			case 1:
			$msg = "Tamanho de arquivo nao permitido";
			break;
			case 2:
			$msg = "Tamanho de arquivo nao permitido";
			break;
			case 3:
			$msg = "Upload feito parcialmente";
			break;
			case 4:
			$msg = "Upload nao efetuado"	;
			break;
			default:
			$msg = "Codigo de erro desconhecido"	;
			break;
		}
		return $msg;
	}

	public function move(){
		try {
			if($this->erro)
				throw new Exception("Ha um erro");
			if(move_uploaded_file(
				$this->tmp,
				$this->url
			))
				return true;
			else
				throw new Exception("Nao foi possivel mover o arquivo");
		} catch (Exception $e) {
			$msg = $e->getMessage();
			echo $msg;
			return false;
		}
	}
	
	public function _move( $i) { // multiple
		try {
			if($this->erro)
				throw new Exception("Ha um erro");
			if(move_uploaded_file(
				$this->tmp[$i],
				$this->url
			))
				return true;
			else
				throw new Exception("Nao foi possivel mover o arquivo");
		} catch (Exception $e) {
			$msg = $e->getMessage();
			echo $msg;
			return false;
		}
	}
	
	public static function salvaArq($nome, $file){
		//echo $nome." " ;
		$up = new Upload($nome,"../uploads",$file);
		//print_r($up);
		if($up->move())
			return "uploads/".$nome.strrchr($up->fileNome,".");
		//else return null;
		//print_r($up);
	}
	
	public static function salvaArqDaRaiz($nome, $file){
		//echo $nome." " ;
		$up = new Upload($nome,"uploads",$file);
		//print_r($up);
		if($up->move())
			return "uploads/".$nome.strrchr($up->fileNome,".");
		//else return null;
		//print_r($up);
	}
	
	public static function salvaArqs($nome, $file, $i){
		//echo $nome." " ;
		$up = new Upload($nome,"../uploads",$file,$i);
		//print_r($up);
		if($up->_move($i))
			return "uploads/".$nome.strrchr($up->fileNome[$i],".");
		//else return null;
		//print_r($up);
	}

}



?>