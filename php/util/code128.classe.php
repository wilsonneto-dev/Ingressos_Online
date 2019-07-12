<?php

	//// Code By Dan Billeci
	//// dabilleci@hotmail.com
	
	//// USAGE:
	
	//// 	$code128 = new code128();
	////	print $code128->produceHTML("MyBarCode123"); /// PRINTS HTML IMG TABLE OF THE CODE
	
	////	Arguments:  produceHTML("TEXT","0 OR 1 HERE FOR ALIGNMENT HORIZONTAL OR VERTICAL","HEIGHT IN PIXELS","MULTIPLIER IN PIXELS (INTEGERS ONLY 1-5~)","BLACK IMAGE PATH", "WHITE IMAGE PATH")
	////	Assumes the files "b.gif" - black image, and "w.gif" - white image are in root directory
	////	To Just get the array of values, use produceArray("MyBarCode123")
	
	////	Where I got the Info: 
	
			//http://www.barcode-1.net/pub/russadam/128table.html
			//http://www.barcode-1.net/pub/russadam/128code.html

/////////////////////////////////////////////

	class code128 {
	
		/// VARIABLES
		var $arr128, $ascii;
	
		////////////////////////////////////////////////////////////
	
		function code128() {	
			
			$this->ascii		= " !\"#\$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";
			
			$this->arr128[0]		= "212222";
			$this->arr128[1]		= "222122";
			$this->arr128[2]		= "222221";
			$this->arr128[3]		= "121223";
			$this->arr128[4]		= "121322";
			$this->arr128[5]		= "131222";
			$this->arr128[6]		= "122213";
			$this->arr128[7]		= "122312";
			$this->arr128[8]		= "132212";
			$this->arr128[9]		= "221213";
			$this->arr128[10]		= "221312";
			$this->arr128[11]		= "231212";
			$this->arr128[12]		= "112232";
			$this->arr128[13]		= "122132";
			$this->arr128[14]		= "122231";
			$this->arr128[15]		= "113222";
			$this->arr128[16]		= "123122";
			$this->arr128[17]		= "123221";
			$this->arr128[18]		= "223211";
			$this->arr128[19]		= "221132";
			$this->arr128[20]		= "221231";
			$this->arr128[21]		= "213212";
			$this->arr128[22]		= "223112";
			$this->arr128[23]		= "312131";
			$this->arr128[24]		= "311222";
			$this->arr128[25]		= "321122";
			$this->arr128[26]		= "321221";
			$this->arr128[27]		= "312212";
			$this->arr128[28]		= "322112";
			$this->arr128[29]		= "322211";
			$this->arr128[30]		= "212123";
			$this->arr128[31]		= "212321";
			$this->arr128[32]		= "232121";
			$this->arr128[33]		= "111323";
			$this->arr128[34]		= "131123";
			$this->arr128[35]		= "131321";
			$this->arr128[36]		= "112313";
			$this->arr128[37]		= "132113";
			$this->arr128[38]		= "132311";
			$this->arr128[39]		= "211313";
			$this->arr128[40]		= "231113";
			$this->arr128[41]		= "231311";
			$this->arr128[42]		= "112133";
			$this->arr128[43]		= "112331";
			$this->arr128[44]		= "132131";
			$this->arr128[45]		= "113123";
			$this->arr128[46]		= "113321";
			$this->arr128[47]		= "133121";
			$this->arr128[48]		= "313121";
			$this->arr128[49]		= "211331";
			$this->arr128[50]		= "231131";
			$this->arr128[51]		= "213113";
			$this->arr128[52]		= "213311";
			$this->arr128[53]		= "213131";
			$this->arr128[54]		= "311123";
			$this->arr128[55]		= "311321";
			$this->arr128[56]		= "331121";
			$this->arr128[57]		= "312113";
			$this->arr128[58]		= "312311";
			$this->arr128[59]		= "332111";
			$this->arr128[60]		= "314111";
			$this->arr128[61]		= "221411";
			$this->arr128[62]		= "431111";
			$this->arr128[63]		= "111224";
			$this->arr128[64]		= "111422";
			$this->arr128[65]		= "121124";
			$this->arr128[66]		= "121421";
			$this->arr128[67]		= "141122";
			$this->arr128[68]		= "141221";
			$this->arr128[69]		= "112214";
			$this->arr128[70]		= "112412";
			$this->arr128[71]		= "122114";
			$this->arr128[72]		= "122411";
			$this->arr128[73]		= "142112";
			$this->arr128[74]		= "142211";
			$this->arr128[75]		= "241211";
			$this->arr128[76]		= "221114";
			$this->arr128[77]		= "413111";
			$this->arr128[78]		= "241112";
			$this->arr128[79]		= "134111";
			$this->arr128[80]		= "111242";
			$this->arr128[81]		= "121142";
			$this->arr128[82]		= "121241";
			$this->arr128[83]		= "114212";
			$this->arr128[84]		= "124112";
			$this->arr128[85]		= "124211";
			$this->arr128[86]		= "411212";
			$this->arr128[87]		= "421112";
			$this->arr128[88]		= "421211";
			$this->arr128[89]		= "212141";
			$this->arr128[90]		= "214121";
			$this->arr128[91]		= "412121";
			$this->arr128[92]		= "111143";
			$this->arr128[93]		= "111341";
			$this->arr128[94]		= "131141";
			$this->arr128[95]		= "114113";
			$this->arr128[96]		= "114311";
			$this->arr128[97]		= "411113";
			$this->arr128[98]		= "411311";
			$this->arr128[99]		= "113141";
			$this->arr128[100]		= "114131";
			$this->arr128[101]		= "311141";
			$this->arr128[102]		= "411131";
			$this->arr128[103]		= "211412";
			$this->arr128[104]		= "211214";
			$this->arr128[105]		= "211232";
			$this->arr128[106]		= "2331112";
	
		}
	
///////////////////////////////////////////////////////////////
	
		function produceHTML($text="",$alignment=0,$height=60,$width=1,$bImg="b.gif",$wImg="w.gif") {
			
			$ticketArray = $this->produceArray($text);
			
			$html ="<table class=\"cod_barras\" cellpadding=3 cellspacing=0 border=0 bgcolor=white><tr><td>";
			
			/// ARRAY OF IMAGES
			$color[0]	= $bImg;
			$color[1]	= $wImg;
			
			//// OUTPUT HTML
			if ( is_array($ticketArray) ) {
			
				foreach ( $ticketArray as $k=>$v ) {
				
					$on = 0;
				
					$info = $this->arr128[intval($v)];								
					
					for ( $qq=0; $qq < strlen($info); $qq++ ) {
							
						if ( !$alignment ) {
							$html .= "<img src=\"imgs/". $color[intval($on)] ."\" height=\"$height\" width = \"".floor( $info[$qq] * $width )."\">";
						}
						else {
							$html .= "<img src=\"imgs/". $color[intval($on)] ."\" width=\"$height\" height = \"".floor( $info[$qq] * $width )."\"><br>";
						}
						
						$on = !intval($on);
						
					}
					
				}
			
			}
			
			$html .= "</td></tr></table>";
			
			return $html;
			
		}
	
///////////////////////////////////////////////////////////////
	
		function produceArray($text="") {
					
			$total = 104;
			$onChar = 0;
					
			//// START
			$ticketArray[]	= 104;
					
			//// GO THRU ARRAY GET LETTERS
			for( $xx=0; $xx < strlen($text); $xx++ ) {						
				
				$pos	= strpos($this->ascii,$text[$xx]);
				
				/// SKIP NOT FOUND CHARS
				if ( $pos === false ) { continue; }
				
				$onChar = 0;
				
				$ticketArray[]	= $pos;	
				
				$total += ( $onChar * $pos );
					
			}
			
			/// CHECK CHAR
			$ticketArray[]	= ( $total % 103 );
			
			//// END
			$ticketArray[]	= 106;
		
			return $ticketArray;
			
		}
	
///////////////////////////////////////////////////////////////
	
	}

?>