<?php
/*
	classe para gerar links amigáveis a SEO
	20 de Junho de 2012
	
	referência: http://cgoncalves.com/php-gerar-urls-amigaveis/

	ex:
	// echo gerar_link('Outro dia a Programar com PHP');
	// Resultado :: outro-dia-programar-php
	
*/


class SEO{
	
	static $substitui = '-';
	static $remover_palavras = true;
	static $array_palavras = array();//array('a','um','de','o','é','à','com','pode','da','porque','não');
		
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
		$string = str_replace("õ","o",$string);
	    $string = str_replace("ó","o",$string);
	    $string = str_replace("ò","o",$string);
	    $string = str_replace("ô","o",$string);
	    $string = str_replace("á","a",$string);
	    $string = str_replace("à","a",$string);
	    $string = str_replace("â","a",$string);
	    $string = str_replace("ã","a",$string);
	    $string = str_replace("é","e",$string);
	    $string = str_replace("è","e",$string);
	    $string = str_replace("ê","e",$string);
	    $string = str_replace("í","i",$string);
	    $string = str_replace("ì","i",$string);
	    $string = str_replace("ú","u",$string);
	    $string = str_replace("ù","u",$string);
	    $string = str_replace("ç","c",$string);
	    $string = str_replace("ª","",$string);
	    return $string;
	}
	
	/* Obtém o input, e desfaz-se dos caracteres indesejados */
	static public function gerar_link($_input)
	{
		//Colocar em minúsculas, remover a pontuação
		$input = strtolower(utf8_decode($_input));
		//echo "passo1: $input<br />";
		$input = SEO::remover_caracter($input);
		//echo "passo2: $input<br />";
		$resultado = utf8_encode(trim(preg_replace('[ +]',' ',preg_replace('/[^a-zA-Z0-9\s]-/','',$input))));
		//echo "passo2.9: $resultado<br />";
		//Remover as palavras que não ajudam no SEO
		//Coloco as palavras por defeito no remover_palavras(), assim eu não esse array
		if(SEO::$remover_palavras) { $resultado = SEO::remover_palavras($resultado,SEO::$substitui,SEO::$array_palavras); }
		//echo "passo3: $resultado<br />";
		
		//Converte os espaços para o que o utilizador quiser
		//Normalmente um hífen ou um underscore
		return str_replace(' ',Seo::$substitui,$resultado);
	}
	
	static private function remover_palavras($input,$substitui,$array_palavras = array(),$palavras_unicas = true)
	{
		//Separar todas as palavras baseadas em espaços
		$array_entrada = explode(' ',$input);
	 
		//Criar o array de saída
		$resultado = array();
	 
		//Faz-se um loop às palavras, remove-se as palavras indesejadas e mantém-se as que interessam
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