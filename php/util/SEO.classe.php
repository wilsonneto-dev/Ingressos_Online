<?php
/*
	classe para gerar links amig�veis a SEO
	20 de Junho de 2012
	
	refer�ncia: http://cgoncalves.com/php-gerar-urls-amigaveis/

	ex:
	// echo gerar_link('Outro dia a Programar com PHP');
	// Resultado :: outro-dia-programar-php
	
*/


class SEO{
	
	static $substitui = '-';
	static $remover_palavras = true;
	static $array_palavras = array();//array('a','um','de','o','�','�','com','pode','da','porque','n�o');
		
	static public function id_url( $_nome, &$obj ){
		$obj->id_url = SEO::gerar_link( $_nome );
		$cl = clone $obj;
		$cont = 0;
		while( $cl->getByUrl() ){
			// se buscou e retornou o mesmo, nem meche
			if( $obj->id == $cl->id  ){
				break;
			}
			// senao traz ele com um numeral sequencial na frente
			else{
				$cl->id_url = $obj->id_url ."-". ( ++$cont );
			}
		}
		return ( $obj->id_url = $cl->id_url );
	}
	
	static public function remover_caracter($string){
		$string = str_replace("&","e",$string);
		$string = str_replace("�","o",$string);
	    $string = str_replace("�","o",$string);
	    $string = str_replace("�","o",$string);
	    $string = str_replace("�","o",$string);
	    $string = str_replace("�","a",$string);
	    $string = str_replace("�","a",$string);
	    $string = str_replace("�","a",$string);
	    $string = str_replace("�","a",$string);
	    $string = str_replace("�","e",$string);
	    $string = str_replace("�","e",$string);
	    $string = str_replace("�","e",$string);
	    $string = str_replace("�","i",$string);
	    $string = str_replace("�","i",$string);
	    $string = str_replace("�","u",$string);
	    $string = str_replace("�","u",$string);
	    $string = str_replace("�","c",$string);
	    $string = str_replace("�","",$string);
	    return $string;
	}
	
	/* Obt�m o input, e desfaz-se dos caracteres indesejados */
	static public function gerar_link($_input)
	{
		//Colocar em min�sculas, remover a pontua��o
		$input = strtolower(utf8_decode($_input));
		//echo "passo1: $input<br />";
		$input = SEO::remover_caracter($input);
		//echo "passo2: $input<br />";
		$resultado = utf8_encode(trim(preg_replace('[ +]',' ',preg_replace('/[^a-zA-Z0-9\s]-/','',$input))));
		//echo "passo2.9: $resultado<br />";
		//Remover as palavras que n�o ajudam no SEO
		//Coloco as palavras por defeito no remover_palavras(), assim eu n�o esse array
		if(SEO::$remover_palavras) { $resultado = SEO::remover_palavras($resultado,SEO::$substitui,SEO::$array_palavras); }
		//echo "passo3: $resultado<br />";
		
		//Converte os espa�os para o que o utilizador quiser
		//Normalmente um h�fen ou um underscore
		return str_replace(' ',Seo::$substitui,$resultado);
	}
	
	static private function remover_palavras($input,$substitui,$array_palavras = array(),$palavras_unicas = true)
	{
		//Separar todas as palavras baseadas em espa�os
		$array_entrada = explode(' ',$input);
	 
		//Criar o array de sa�da
		$resultado = array();
	 
		//Faz-se um loop �s palavras, remove-se as palavras indesejadas e mant�m-se as que interessam
		foreach($array_entrada as $palavra)
		{
			if(!in_array($palavra,$array_palavras) && ($palavras_unicas ? !in_array($palavra,$resultado) : true))
			{
				$resultado[] = $palavra;
			}
		}
		return implode($substitui,$resultado);
	}

}

?>