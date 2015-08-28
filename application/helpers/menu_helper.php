<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	 /**
	 *Criar menus do site
	 *
	 ****/
	 function menu_painel_open(){
	 	return '<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">';	 	
	 }
	 function menu_painel_close(){
	 	 return '</ul>
            </div>
            <!-- /.navbar-collapse -->';
	 }
	 function menu_painel_li($link, $name, $icone=NULL){
	 		if($icone==NULL){
	 		return	'<li '.ativar_li($link).'>
                        '.anchor($link,$name).'
                    </li>';
	 		}else{
	 			$name_icone = '<i class="fa fa-fw '.$icone.'"></i> '.$name;
	 		return	'<li '.ativar_li($link).'>
                        '.anchor($link,$name_icone).'
                    </li>';
	 		}
	 }

	 function dropp_menu_painel_open($name, $icone=NULL){
	 	if ($icone==NULL) {
	 		return '<li> 
                 <a href="javascript:;" data-toggle="collapse" data-target="#'.$name.'" class="" aria-expanded="true"> '.$name.' <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="'.$name.'" class="collapse">';
	 	} else {
	 		return '<li>
                 <a href="javascript:;" data-toggle="collapse" data-target="#'.$name.'" class="" aria-expanded="true"><i class="fa fa-fw '.$icone.'"></i> '.$name.' <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="'.$name.'" class="collapse">';
	 	}
	 }//.dropp_menu_painel_open
	 function dropp_menu_painel_close(){
	 	return '</ul></li>';
	 }//.dropp_menu_painel_close
	 function ativar_li($link){
	 	$CI =& get_instance();
		$i=$CI->uri->uri_string();
		if($i==$link){return 'class="ativo"';}else{return '';}
	





	}
	 //Fim do create menu
	//Intro do painte titulo e sub titulo

	//Menu lateral painel
	function menu_painel_l(){
		echo menu_painel_open();
        echo menu_painel_li('painel', 'Home', 'fa-dashboard');
        echo dropp_menu_painel_open('Usuarios','fa-users');
        echo menu_painel_li('usuarios/cadastro', 'Cadastro', 'fa-user-plus');
        echo menu_painel_li('usuarios/gerenciar', 'Gerenciar', 'fa-user-plus');
        echo dropp_menu_painel_close();
        echo dropp_menu_painel_open('Midia','fa-users');
        echo menu_painel_li('midia/cadastrar', 'Cadastro', 'fa-user-plus');
        echo menu_painel_li('midia/gerenciar', 'Gerenciar', 'fa-user-plus');
        echo dropp_menu_painel_close();
        echo menu_painel_li('auditoria/logs', 'Auditoria', 'fa-dashboard');
        echo menu_painel_close();

	}