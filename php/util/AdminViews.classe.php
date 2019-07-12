<?php

class AdminViews{

	public static function alert( $titulo, $mensagem, $tipo ){
		$icon = "";
		switch ( $tipo ) {
			case 'success':
				$icon = "check";
				break;
			case 'danger':
				$icon = "ban";
				break;
			case 'warning':
				$icon = "warning";
				break;
			case 'info':
				$icon = "info";
				break;
		}
		$html = "
			<div class=\"alert alert-$tipo alert-dismissable\">
                <i class=\"fa fa-$icon\"></i>
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                <b>$titulo!</b> $mensagem.
            </div>";
		return $html;
	}

	public static function gerar_menu( $cod_grupo_admin ){
		$html = '<ul class="sidebar-menu">';
		$menus = MenuAdmin::_getListaByGrupoAdmin( $cod_grupo_admin );
		foreach ( $menus as $k => $menu ) {
			$html .= 
'						<li class="treeview">
                            <a href="#">
                                <i class="fa '.$menu->icone.'"></i>
                                <span>'.$menu->texto.'</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
';
			$itens = PaginaAdmin::_getListaByGrupoMenu( $cod_grupo_admin, $menu->id );
			foreach ($itens as $k => $item) {
				$html .= 
'								<li class="li-menu '.$item->permissao.'"><a href="/'.$item->url.'"><i class="fa fa-angle-double-right"></i> '.$item->descricao.'</a></li>';
			}
			$html .= 
'                            </ul>
                        </li>';
		}
		$html .= "</ul>";
		return $html;
 	}


	public static function gerar_notificacoes_gerais( $p_cod_adm ){
		
		// recupera ultima vez que o adm viu as notificacoes
		$ultima_notificacao_vizualizada = NotificacaoVisualizacao::_get( $p_cod_adm, 1 /* tipo = 1 é geral */ );
	    
	    // se não existe visualização ainda, cadastra agora
	    if( $ultima_notificacao_vizualizada == null ){
	        $n = new NotificacaoVisualizacao();
	        $n->cod_admin = $p_cod_adm;
	        $n->cod_notificacao_tipo = 1;
	        $n->cadastrar(); 
	        $ultima_notificacao_vizualizada = NotificacaoVisualizacao::_get( $p_cod_adm, 1 /* tipo = 1 é geral */ );
	    }

	    // recupera as notificações que o adm ainda não viu
	    $lista_notificacoes = Notificacao::_getListaByData( $ultima_notificacao_vizualizada->data , 1 /* tipo = 1 é geral */ );
		
	    NotificacaoVisualizacao::_atualizar( $p_cod_adm, 1 );

		// os icones ara cada tipo de notificação geral
	    $notificacao_icones = array(
			'erro' => 'fa fa-warning danger',
			'alerta' => 'fa fa-warning warning',
			'pedidos' => 'ion ion-ios7-cart success',
			'usuarios' => 'ion ion-ios7-person info', 
			'newsletter' => 'ion ion-ios7-people warning'
		);


		$html = '
					<li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-warning"></i>
                            <span class="label label-warning">'.count( $lista_notificacoes ).'</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">'.count( $lista_notificacoes ).' Notifica&ccedil;&otilde;es</li>
                            <li>
                            	<ul class="menu">
';
		foreach ( $lista_notificacoes as $k => $notif ) {
			$html .= 
'								<li>
	                                <a href="/'.$notif->link.'">
	                                    <i class="'.$notificacao_icones[$notif->icone].'"></i> '.$notif->texto.'
	                                </a>
	                            </li>  
';
			
		}
		$html .= "
							</ul>
						</li>
                        <li class=\"footer\"><a href=\"#\">Ver Todas</a></li>
                    </ul>
                </li>";
		return $html;
 	}


}

		

?>