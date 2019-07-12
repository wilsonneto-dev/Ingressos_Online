<?php

class Evento{

	public $id;
	public $id_url;
	public $titulo;
	public $data_entrar;
	public $data_mostrar;
	public $data;
	public $data_final;
	public $data_encerrar_vendas;
	public $imagem;
	public $imagem_topo;
	public $imagem_facebook;
	public $imagem_mapa;
	public $imagem_flyer;
	public $legenda_mapa;
	public $capa;
	public $descricao;
	public $duvidas;
	public $atracoes;
	public $retirada;
	public $cancelamentos;
	public $quantidade_views;
	public $link_video;
	public $link_site;
	public $link_facebook;
	public $oculto;
	public $visivel;
	public $acertado;
	public $venda_suspensa;
	public $cod_genero;
	public $cod_categoria;
	public $cod_local;
	public $cod_promoter;
	
	private $bd;
	
	public function Evento(){
		$this->id = '';
		$this->id_url = "";
		$this->titulo = '';
		$this->data = "";
		$this->data_entrar = "";
		$this->data_final = "";
		$this->data_encerrar_vendas = "";
		$this->imagem = "";
		$this->imagem_topo = "";
		$this->imagem_facebook = "";
		$this->imagem_mapa = "";
		$this->legenda_mapa = "";
		$this->imagem_flyer = "";
		$this->capa = "";
		$this->descricao = "";
		$this->duvidas = "";
		$this->atracoes = "";
		$this->retirada = "";
		$this->cancelamentos = "";
		$this->quantidade_views = "";
		$this->link_video = "";
		$this->link_site = "";
		$this->link_facebook = "";
		$this->oculto = "";
		$this->visivel = "";
		$this->venda_suspensa = "";
		$this->cod_genero = "";
		$this->cod_categoria = "";
		$this->cod_local = "";
		$this->cod_promoter = "";
		$this->bd = new EventoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getByUrl(){
		return $this->bd->getByUrl($this);
	}

	public function atualizar(){
		return $this->bd->atualizar($this);
	}

	public function view(){
		return $this->bd->view( $this );
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new Evento();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByUrl( $p_idUrl ){
		$obj = new Evento();
		$obj->id_url = $p_idUrl;
		if ( $obj->getByUrl() ) 
			return $obj;
		else 
			return null;
	}

	public static function _view( $p_id ){
		$obj = new Evento();
		$obj->id = $p_id;
		return ( $obj->view() ); 
	}

	public static function _deletar( $p_id ){
		$obj = new Evento();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

	public function getListaEventosAtivos( $p ){
		return $this->bd->getListaEventosAtivos($p);
	}

	public static function _getListaEventosAtivos( $data ){
		$obj = new Evento();
		return ( $obj->getListaEventosAtivos( $data ) ); 
	}

}

class EventoDAO extends BaseDAO{

	public function getListaEventosAtivos( $data_inicial ){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 						
						id, 
						id_url, 
						titulo, 
						data, 
						data_entrar, 
						data_final, 
						data_encerrar_vendas, 
						data_mostrar, 
						imagem, 
						imagem_topo, 
						imagem_facebook, 
						imagem_mapa, 
						imagem_flyer, 
						legenda_mapa, 
						capa, 
						descricao, 
						duvidas, 
						atracoes, 
						retirada, 
						cancelamentos, 
						quantidade_views, 
						link_video, 
						link_site, 
						link_facebook, 
						oculto, 
						visivel, 
						acertado, 
						venda_suspensa, 
						cod_genero, 
						cod_categoria, 
						cod_local, 
						cod_promoter 
					FROM 
						evento as ev 
					WHERE 
						ev.ativo = 1
						AND ev.codprojeto = " . $this->codProjeto ." 
						and ev.data_entrar <= str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
				        and ev.data >= date_add( str_to_date( '".$data_inicial->format("d/m/Y")."' , '%d/%m/%Y' ), interval 1 day )
			        order by
			        	ev.data ASC
					;";
				if($q = $this->con->query($str_q)){
					$lista = array();
					if($q->num_rows > 0){
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new Evento();
							
							$p->id = $obj->id;
							$p->id_url = $obj->id_url;
							$p->titulo = $obj->titulo;
							if( is_null( $obj->data) ) $p->data = null;
							else $p->data = new DateTime( $obj->data );
							if( is_null( $obj->data_final) ) $p->data_final = null;
							else $p->data_final = new DateTime( $obj->data_final );
							if( is_null( $obj->data_entrar) ) $p->data_entrar = null;
							else $p->data_entrar = new DateTime( $obj->data_entrar );
							if( is_null( $obj->data_encerrar_vendas) ) $p->data_encerrar_vendas = null;
							else $p->data_encerrar_vendas = new DateTime( $obj->data_encerrar_vendas );
							$p->data_mostrar = $obj->data_mostrar;
							$p->imagem = $obj->imagem;
							$p->imagem_topo = $obj->imagem_topo;
							$p->imagem_facebook = $obj->imagem_facebook;
							$p->imagem_mapa = $obj->imagem_mapa;
							$p->imagem_flyer = $obj->imagem_flyer;
							$p->legenda_mapa = $obj->legenda_mapa;
							$p->capa = $obj->capa;
							$p->capa = $obj->capa;
							$p->descricao = $obj->descricao;
							$p->duvidas = $obj->duvidas;
							$p->atracoes = $obj->atracoes;
							$p->retirada = $obj->retirada;
							$p->cancelamentos = $obj->cancelamentos;
							$p->quantidade_views = $obj->quantidade_views;
							$p->link_video = $obj->link_video;
							$p->link_site = $obj->link_site;
							$p->link_facebook = $obj->link_facebook;
							$p->oculto = $obj->oculto;
							$p->visivel = $obj->visivel;
							$p->acertado = $obj->acertado;
							$p->venda_suspensa = $obj->venda_suspensa;
							$p->cod_genero = $obj->cod_genero;
							$p->cod_categoria = $obj->cod_categoria;
							$p->cod_local = $obj->cod_local;
							$p->cod_promoter = $obj->cod_promoter;

							$lista[] = $p;
						}
					}
					return $lista;
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch (Exception $exception) {
			erro_bd( $exception, $this->con->error );
			echo $exception->getMessage();
			return false;
		}
	}


	public function cadastrar(Evento $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					evento( 
						id_url, 
						titulo, 
						data, 
						data_entrar, 
						data_final, 
						data_encerrar_vendas, 
						data_mostrar, 
						imagem, 
						imagem_topo, 
						imagem_facebook, 
						imagem_mapa, 
						imagem_flyer, 
						legenda_mapa, 
						capa, 
						descricao, 
						duvidas, 
						atracoes, 
						retirada, 
						cancelamentos, 
						link_video, 
						link_site, 
						link_facebook, 
						oculto, 
						visivel, 
						venda_suspensa, 
						cod_genero, 
						cod_categoria, 
						cod_local, 
						cod_promoter, 
						codprojeto 
					)VALUES(
						'". $this->con->real_escape_string($p->id_url) ."'
						,'". $this->con->real_escape_string($p->titulo) ."'
						, str_to_date('". $this->con->real_escape_string( $p->data->format('d/m/Y') ) ."','%d/%m/%Y')
						, str_to_date('". $this->con->real_escape_string( $p->data_entrar->format('d/m/Y') ) ."','%d/%m/%Y')
						, str_to_date('". $this->con->real_escape_string( $p->data_final->format('d/m/Y') ) ."','%d/%m/%Y')
						, str_to_date('". $this->con->real_escape_string( $p->data_encerrar_vendas->format('d/m/Y') ) ."','%d/%m/%Y')
						,'". $this->con->real_escape_string($p->data_mostrar) ."'
						,'". $this->con->real_escape_string($p->imagem) ."'
						,'". $this->con->real_escape_string($p->imagem_topo) ."'
						,'". $this->con->real_escape_string($p->imagem_facebook) ."'
						,'". $this->con->real_escape_string($p->imagem_mapa) ."'
						,'". $this->con->real_escape_string($p->imagem_flyer) ."'
						,'". $this->con->real_escape_string($p->legenda_mapa) ."'
						,'". $this->con->real_escape_string($p->capa) ."'
						,'". $this->con->real_escape_string($p->descricao) ."'
						,'". $this->con->real_escape_string($p->duvidas) ."'
						,'". $this->con->real_escape_string($p->atracoes) ."'
						,'". $this->con->real_escape_string($p->retirada) ."'
						,'". $this->con->real_escape_string($p->cancelamentos) ."'
						,'". $this->con->real_escape_string($p->link_video) ."'
						,'". $this->con->real_escape_string($p->link_site) ."'
						,'". $this->con->real_escape_string($p->link_facebook) ."'
						,'". $this->con->real_escape_string($p->oculto) ."'
						,'". $this->con->real_escape_string($p->visivel) ."'
						,'". $this->con->real_escape_string($p->venda_suspensa) ."'
						,'". $this->con->real_escape_string($p->cod_genero) ."'
						,'". $this->con->real_escape_string($p->cod_categoria) ."'
						,'". $this->con->real_escape_string($p->cod_local) ."'
						,'". $this->con->real_escape_string($p->cod_promoter) ."'
						, ". $this->codProjeto.
					");";
				if ( $q = $this->con->query( $str_q ) ){
					$p->id = $this->con->insert_id;
					return true;
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao");	
		} catch (Exception $e) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						id_url, 
						titulo, 
						data, 
						data_entrar, 
						data_final, 
						data_encerrar_vendas, 
						data_mostrar, 
						imagem, 
						imagem_topo, 
						imagem_facebook, 
						imagem_mapa, 
						imagem_flyer, 
						legenda_mapa, 
						capa, 
						descricao, 
						duvidas, 
						atracoes, 
						retirada, 
						cancelamentos, 
						quantidade_views, 
						link_video, 
						link_site, 
						link_facebook, 
						oculto, 
						visivel, 
						acertado, 
						venda_suspensa, 
						cod_genero, 
						cod_categoria, 
						cod_local, 
						cod_promoter 
					FROM 
						evento 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->id_url = $obj->id_url;
						$p->titulo = $obj->titulo;
						if( is_null( $obj->data) ) $p->data = null;
						else $p->data = new DateTime( $obj->data );
						if( is_null( $obj->data_final) ) $p->data_final = null;
						else $p->data_final = new DateTime( $obj->data_final );
						if( is_null( $obj->data_entrar) ) $p->data_entrar = null;
						else $p->data_entrar = new DateTime( $obj->data_entrar );
						if( is_null( $obj->data_encerrar_vendas) ) $p->data_encerrar_vendas = null;
						else $p->data_encerrar_vendas = new DateTime( $obj->data_encerrar_vendas );
						$p->data_mostrar = $obj->data_mostrar;
						$p->imagem = $obj->imagem;
						$p->imagem_topo = $obj->imagem_topo;
						$p->imagem_facebook = $obj->imagem_facebook;
						$p->imagem_mapa = $obj->imagem_mapa;
						$p->imagem_flyer = $obj->imagem_flyer;
						$p->legenda_mapa = $obj->legenda_mapa;
						$p->capa = $obj->capa;
						$p->capa = $obj->capa;
						$p->descricao = $obj->descricao;
						$p->duvidas = $obj->duvidas;
						$p->atracoes = $obj->atracoes;
						$p->retirada = $obj->retirada;
						$p->cancelamentos = $obj->cancelamentos;
						$p->quantidade_views = $obj->quantidade_views;
						$p->link_video = $obj->link_video;
						$p->link_site = $obj->link_site;
						$p->link_facebook = $obj->link_facebook;
						$p->oculto = $obj->oculto;
						$p->visivel = $obj->visivel;
						$p->acertado = $obj->acertado;
						$p->venda_suspensa = $obj->venda_suspensa;
						$p->cod_genero = $obj->cod_genero;
						$p->cod_categoria = $obj->cod_categoria;
						$p->cod_local = $obj->cod_local;
						$p->cod_promoter = $obj->cod_promoter;
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function getByUrl($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						id_url, 
						titulo, 
						data_mostrar, 
						data, 
						data_entrar, 
						data_final, 
						data_encerrar_vendas, 
						imagem, 
						imagem_topo, 
						imagem_facebook, 
						imagem_mapa, 
						imagem_flyer, 
						legenda_mapa, 
						capa, 
						descricao, 
						duvidas, 
						atracoes, 
						retirada, 
						cancelamentos, 
						quantidade_views, 
						link_video, 
						link_site, 
						link_facebook, 
						oculto, 
						visivel, 
						venda_suspensa, 
						cod_genero, 
						cod_categoria, 
						cod_local, 
						cod_promoter 
					FROM 
						evento 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id_url = '" . $this->con->real_escape_string( $p->id_url ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->id_url = $obj->id_url;
						$p->titulo = $obj->titulo;
						$p->data_mostrar = $obj->data_mostrar;
						if( is_null( $obj->data) ) $p->data = null;
						else $p->data = new DateTime( $obj->data );
						if( is_null( $obj->data_entrar) ) $p->data_entrar = null;
						else $p->data_entrar = new DateTime( $obj->data_entrar );
						if( is_null( $obj->data_final) ) $p->data_final = null;
						else $p->data_final = new DateTime( $obj->data_final );
						if( is_null( $obj->data_encerrar_vendas) ) $p->data_encerrar_vendas = null;
						else $p->data_encerrar_vendas = new DateTime( $obj->data_encerrar_vendas );
						$p->imagem = $obj->imagem;
						$p->imagem_topo = $obj->imagem_topo;
						$p->imagem_facebook = $obj->imagem_facebook;
						$p->imagem_mapa = $obj->imagem_mapa;
						$p->imagem_flyer = $obj->imagem_flyer;
						$p->legenda_mapa = $obj->legenda_mapa;
						$p->capa = $obj->capa;
						$p->capa = $obj->capa;
						$p->descricao = $obj->descricao;
						$p->duvidas = $obj->duvidas;
						$p->atracoes = $obj->atracoes;
						$p->retirada = $obj->retirada;
						$p->cancelamentos = $obj->cancelamentos;
						$p->quantidade_views = $obj->quantidade_views;
						$p->link_video = $obj->link_video;
						$p->link_site = $obj->link_site;
						$p->link_facebook = $obj->link_facebook;
						$p->oculto = $obj->oculto;
						$p->visivel = $obj->visivel;
						$p->venda_suspensa = $obj->venda_suspensa;
						$p->cod_genero = $obj->cod_genero;
						$p->cod_categoria = $obj->cod_categoria;
						$p->cod_local = $obj->cod_local;
						$p->cod_promoter = $obj->cod_promoter;
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function atualizar($p){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						evento
					SET 
						id_url = '". $this->con->real_escape_string($p->id_url) ."'
						, titulo = '". $this->con->real_escape_string($p->titulo) ."'
						, data_mostrar = '". $this->con->real_escape_string($p->data_mostrar) ."'
						, data = str_to_date('". $this->con->real_escape_string( $p->data->format('d/m/Y') ) ."','%d/%m/%Y')
						, data_entrar = str_to_date('". $this->con->real_escape_string( $p->data_entrar->format('d/m/Y') ) ."','%d/%m/%Y')
						, data_final = str_to_date('". $this->con->real_escape_string( $p->data_final->format('d/m/Y') ) ."','%d/%m/%Y')
						, data_encerrar_vendas = str_to_date('". $this->con->real_escape_string( $p->data_encerrar_vendas->format('d/m/Y') ) ."','%d/%m/%Y')
						, imagem = '". $this->con->real_escape_string($p->imagem) ."'
						, imagem_topo = '". $this->con->real_escape_string($p->imagem_topo) ."'
						, imagem_facebook = '". $this->con->real_escape_string($p->imagem_facebook) ."'
						, imagem_mapa = '". $this->con->real_escape_string($p->imagem_mapa) ."'
						, imagem_flyer = '". $this->con->real_escape_string($p->imagem_flyer) ."'
						, legenda_mapa = '". $this->con->real_escape_string($p->legenda_mapa) ."'
						, capa = '". $this->con->real_escape_string($p->capa) ."'
						, descricao = '". $this->con->real_escape_string($p->descricao) ."'
						, duvidas = '". $this->con->real_escape_string($p->duvidas) ."'
						, atracoes = '". $this->con->real_escape_string($p->atracoes) ."'
						, retirada = '". $this->con->real_escape_string($p->retirada) ."'
						, cancelamentos = '". $this->con->real_escape_string($p->cancelamentos) ."'
						, quantidade_views = '". $this->con->real_escape_string($p->quantidade_views) ."'
						, link_video = '". $this->con->real_escape_string($p->link_video) ."'
						, link_site = '". $this->con->real_escape_string($p->link_site) ."'
						, link_facebook = '". $this->con->real_escape_string($p->link_facebook) ."'
						, oculto = '". $this->con->real_escape_string($p->oculto) ."'
						, visivel = '". $this->con->real_escape_string($p->visivel) ."'
						, acertado = '". $this->con->real_escape_string($p->acertado) ."'
						, venda_suspensa = '". $this->con->real_escape_string($p->venda_suspensa) ."'
						, cod_genero = '". $this->con->real_escape_string($p->cod_genero) ."'
						, cod_categoria = '". $this->con->real_escape_string($p->cod_categoria) ."'
						, cod_local = '". $this->con->real_escape_string($p->cod_local) ."'
						, cod_promoter = '". $this->con->real_escape_string($p->cod_promoter) ."'
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
	public function view( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						evento
					SET 
						quantidade_view = ( quantidade_view + 1 )
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
	public function deletar(Evento $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE 
							evento
						SET 
							ativo = 0
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
			erro_bd( $e, $this->con->error );
			return false;
		}
	}
}

?>