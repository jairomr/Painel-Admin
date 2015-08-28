<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function bt_col($i, $conteudo){
	if(is_array($conteudo)){
		$c='';
		foreach ($conteudo as $key){
			$c.=$key;
		}
		return '<div class="col-md-'.$i.'">'.$c.'</div>';
	}else{return '<div class="col-md-'.$i.'">'.$conteudo.'</div>';}
}
function bt_row($conteudo){
	if(is_array($conteudo)){
		$c='';
		foreach ($conteudo as $key){
			$c.=$key;
		}
		return '<div class="row">'.$c.'</div>';
	}else{return '<div class="row">'.$conteudo.'</div>';}

}
function bt_input($n,$name,$i=12, $a=FALSE){
	if($a){
	return'<div class="form-group col-md-'.$i.'">'."\n"
      .form_label($n)."\n".
     form_input(array('name'=>$name,'class'=>'form-control' ), set_value($name), 'autofocus')."\n"                        
      .'</div>'."\n";}
      else{
      	return'<div class="form-group col-md-'.$i.'">'."\n"
      .form_label($n)."\n".
     form_input(array('name'=>$name,'class'=>'form-control'), set_value($name))."\n"                       
      .'</div>'."\n";
      }
}
function bt_input_cont($n,$name,$i=12, $a=NULL){
   	return'<div class="form-group col-md-'.$i.'">'."\n"
      .form_label($n)."\n".
     form_input(array('name'=>$name,'class'=>'form-control'), set_value($name, $a))."\n"                       
      .'</div>'."\n";
    
}
function bt_input_disabled($n,$name,$i=12,$query){
	
    return'<div class="form-group col-md-'.$i.'">'."\n"
      .form_label($n)."\n".
     form_input(array('name'=>$name,'class'=>'form-control', 'disabled'=>'disabled'), set_value($name,$query))."\n"                       
      .'</div>'."\n";

}
function bt_password($n,$name,$i=12, $a=FALSE){
	if($a){
	return'<div class="form-group col-md-'.$i.'">'."\n"
      .form_label($n)."\n".
     form_password(array('name'=>$name,'class'=>'form-control' ), set_value($name), 'autofocus')."\n"                        
      .'</div>'."\n";}
      else{
      	return'<div class="form-group col-md-'.$i.'">'."\n"
      .form_label($n)."\n".
     form_password(array('name'=>$name,'class'=>'form-control'), set_value($name))."\n"                       
      .'</div>'."\n";
      }
}
function bt_submit($n,$name,$t=0,$tipo=NULL){
	$tm='';
	$tp='';
	if($t==1){$tm='btn-xs';}elseif($t==2){$tm='btn-sm';}elseif($t==3){$tm='btn-lg';}else{$tm='';}
	if($tipo==NULL){$tp='default';}elseif(is_int($tipo)){
		switch ($tipo) {
			case 1:
				$tp='info';
				break;
			case 2:
				$tp='primary';
				break;
			case 3:
				$tp='success';
				break;
			case 4:
				$tp='warning';
				break;
			case 5:
				$tp='danger';
				break;
			case 6:
				$tp='link';
				break;
			default:
				$tp='default';
				break;
			}

	}else{
		switch ($tipo) {
			case 'info':
				$tp='info';
				break;
			case 'primario':
				$tp='primary';
				break;
			case 'sucesso':
				$tp='success';
				break;
			case 'aviso':
				$tp='warning';
				break;
			case 'perigo':
				$tp='danger';
				break;
			case 'link':
				$tp='link';
				break;
			default:
				$tp='default';
				break;
		}
	}
	
	return form_submit(array('name'=>$name,'class'=>'btn '.$tm.' btn-'.$tp),$n)."\n";

}
function bt_form($i, $conteudo){
	if(is_array($conteudo)){
		$c='';
		foreach ($conteudo as $key){
			$c.=$key;
		}
		return '<div class="form-group col-md-'.$i.'">'."\n".$c.'</div>';
	}else{return '<div class="form-group col-md-'.$i.'">'."\n".$conteudo.'</div>';}

}
function bt_checkbox($n,$v,$c,$l=FALSE){
	return '<div class="checkbox">
        <label>'.
            form_checkbox($n, $v,$l).$c.' 
        </label>
    </div>';
}
function bt_titulo($s=NULL){
	$CI =& get_instance();
	$t = ucfirst(str_replace('_', ' ', $CI->router->method));
	if($s==NULL){$sub='';}else{
     $sub='<small>'.$s.'</small>';
                            }
    if($t=='Index'){$t='Painel';}
	return '<div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                       '.$t.' '.$sub.'
                    </h1>
                    
                    '.breadcrumb().'
                    </div>
                </div>';

}
function breadcrumb(){
		$CI =& get_instance();
		$class = ucfirst($CI->router->class);
		$metodo = ucfirst(str_replace('_', ' ', $CI->router->method));
		if($class=='Painel'){
			$class = '<li>'.anchor($CI->router->class, 'Inicio')."</li>\n";
		}else{
			$class = '<li>'.anchor($CI->router->class, $class)."</li>\n";
		}
		if($metodo=='Index'){
			$metodo = '';
		}else{
			$metodo = '<li>'.anchor($CI->router->class.'/'.$CI->router->method, $metodo)."</li>\n";
		}
		
             
        return "<ol class='breadcrumb'>\n<li>".anchor('painel', 'Painel')."</li>\n".$class.$metodo."\n</ol>";
        }
function bt_icone($name){
	return '<i class="fa fa-'.$name.'"></i>';
}