<?php

class Imagem{

	public $imgPath;
	public $altura;
	public $largura;

	public function Imagem($imgPath,$altura = 0,$largura = 0){
		$this->imgPath = $imgPath;
		$this->altura = $altura==0 ? MINIATURA_ALTURA : $altura;
		$this->largura = $largura==0 ? MINIATURA_LARGURA : $largura;
	}

	public function mostrar(){
		try {
			$ext = strtolower(strrchr($this->imgPath,"."));
			if($ext == ".jpg"||$ext == ".jpeg")
				$img = imagecreatefromjpeg($this->imgPath);
			else if($ext == ".gif")
				$img = imagecreatefromgif($this->imgPath);
			else if($ext == ".png")
				$img = imagecreatefrompng($this->imgPath);
			else throw new Exception("Formato desconhecido...");
			$thumb = imagecreatetruecolor($this->largura, $this->altura);
			imagecopyresampled($thumb, $img, 0, 0, 0, 0, $this->largura, $this->altura, imagesX($img),imagesY($img));
			imagegif($thumb);
			imagedestroy($img);
			imagedestroy($thumb);
		} catch (Exception $e) {
			$strErro = "erro ao tratar imagem: " + $e->getMessage();
			echo $strErro;
		}
	}
	
	public function salvar($str){
		try {
			/*echo "entrou";*/
			$ext = strtolower(strrchr($this->imgPath,"."));
			if($ext == ".jpg"||$ext == ".jpeg")
				$img = imagecreatefromjpeg($this->imgPath);
			else if($ext == ".gif")
				$img = imagecreatefromgif($this->imgPath);
			else if($ext == ".png")
				$img = imagecreatefrompng($this->imgPath);
			else throw new Exception("Formato desconhecido...");
			$thumb = imagecreatetruecolor($this->largura, $this->altura);
			imagecopyresampled($thumb, $img, 0, 0, 0, 0, $this->largura, $this->altura, imagesX($img),imagesY($img));
			
			//$ext = strrchr($str,".");
			if($ext == ".jpg"||$ext == ".jpeg")
				imagejpeg($thumb,$str,100);
			else if($ext == ".gif")
				imagegif($thumb,$str);
			else if($ext == ".png")
				imagepng($thumb,$str,9);
			else throw new Exception("Formato desconhecido (para salvar)...");

			imagedestroy($img);
			imagedestroy($thumb);
			return str_replace("../", "", $str);
		} catch (Exception $e) {
			$strErro = "erro ao tratar imagem: " + $e->getMessage();
			echo $strErro;
		}
	}
	
	public function salvarProporcional($str){
		try {
			$ext = strtolower(strrchr($this->imgPath,"."));
			if($ext == ".jpg"||$ext == ".jpeg")
				$img = imagecreatefromjpeg($this->imgPath);
			else if($ext == ".gif")
				$img = imagecreatefromgif($this->imgPath);
			else if($ext == ".png"){
				$img = imagecreatefrompng($this->imgPath);
				imagealphablending( $img , false);
				imagesavealpha( $img , true);
			}else 
				throw new Exception("Formato desconhecido...");

			$altura = imagesY($img);
			$largura = imagesX($img);

			if($this->largura > $largura) $this->largura = $largura;
			if($this->altura > $altura) $this->altura = $altura;
			
			if($largura > $altura)
				$this->altura = (int)(($this->largura * $altura)/$largura);
			else
				$this->largura = (int)(($largura * $this->altura)/$altura);
			
			$thumb = imagecreatetruecolor($this->largura, $this->altura);
			imagecopyresampled($thumb, $img, 0, 0, 0, 0, $this->largura, $this->altura, $largura,$altura);

			$ext = strtolower(strrchr($str,"."));
			if($ext == ".jpg"||$ext == ".jpeg")
				imagejpeg($thumb,$str,100);
			else if($ext == ".gif")
				imagegif($thumb,$str);
			else if($ext == ".png")
				imagepng($thumb,$str,9);
			else throw new Exception("Formato desconhecido (para salvar)...");

			imagedestroy($img);
			imagedestroy($thumb);
			
			return str_replace("../", "", $str);
		} catch (Exception $e) {
			$strErro = "erro ao tratar imagem: " + $e->getMessage();
			echo $strErro;
		}
	}
	
	public function salvarLarguraFixa($str){
		try {
			$ext = strtolower(strrchr($this->imgPath,"."));
			if($ext == ".jpg"||$ext == ".jpeg")
				$img = imagecreatefromjpeg($this->imgPath);
			else if($ext == ".gif")
				$img = imagecreatefromgif($this->imgPath);
			else if($ext == ".png")
				$img = imagecreatefrompng($this->imgPath);
			else throw new Exception("Formato desconhecido...");

			$altura = imagesY($img);
			$largura = imagesX($img);

			// só mecher na altura
			$this->altura = (int)(($this->largura * $altura)/$largura);
	
			$thumb = imagecreatetruecolor($this->largura, $this->altura);
			imagecopyresampled($thumb, $img, 0, 0, 0, 0, $this->largura, $this->altura, $largura,$altura);

			$ext = strtolower(strrchr($str,"."));
			if($ext == ".jpg"||$ext == ".jpeg")
				imagejpeg($thumb,$str,100);
			else if($ext == ".gif")
				imagegif($thumb,$str);
			else if($ext == ".png")
				imagepng($thumb,$str,9);
			else throw new Exception("Formato desconhecido (para salvar)...");

			imagedestroy($img);
			imagedestroy($thumb);
			
			return str_replace("../", "", $str);
		} catch (Exception $e) {
			$strErro = "erro ao tratar imagem: " + $e->getMessage();
			echo $strErro;
		}
	}
	
	public static function MiniaturaProporcional($strImagem, $h = 0, $w = 0){
		$img = new Imagem($strImagem, $h, $w);
		$dst = substr($strImagem,0,strrpos($strImagem,"/")+1) . "thumb_" . substr($strImagem,strrpos($strImagem,"/")+1,strrpos($strImagem,"."));
		//echo "<h1>$dst</h1>";
		$nome = $img->salvarProporcional($dst);
		unset($img);
		return $nome;
	}
	
	public static function MiniaturaNaProporcao($strImagem, $altura, $largura){
		$img = new Imagem($strImagem, $altura, $largura);
		$dst = substr($strImagem,0,strrpos($strImagem,"/")+1) . "thumb_" . substr($strImagem,strrpos($strImagem,"/")+1,strrpos($strImagem,"."));
		//echo "<h1>$dst</h1>";
		$nome = $img->salvarProporcional($dst);
		unset($img);
		return $nome;
	}
	
	public static function MiniaturaNaLargura($strImagem, $altura, $largura, $pre = "thumb_" ){
		$img = new Imagem($strImagem, $altura, $largura);
		$dst = substr($strImagem,0,strrpos($strImagem,"/")+1) . $pre . substr($strImagem,strrpos($strImagem,"/")+1,strrpos($strImagem,"."));
		//echo "<h1>$dst</h1>";
		$nome = $img->salvarLarguraFixa($dst);
		unset($img);
		return $nome;
	}
	
	public static function MiniaturaNaMedida($strImagem, $altura, $largura, $pre = "thumb_" ){
		$img = new Imagem($strImagem, $altura, $largura);
		$dst = substr($strImagem,0,strrpos($strImagem,"/")+1) . $pre . substr($strImagem,strrpos($strImagem,"/")+1,strrpos($strImagem,"."));
		//echo "<h1>$dst</h1>";
		$nome = $img->salvar($dst);
		unset($img);
		return $nome;
	}
	
}

?>