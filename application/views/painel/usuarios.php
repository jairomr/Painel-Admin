<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
switch ($tela) {
	case 'login':
        if(esta_logado(FALSE)){redirect('painel');}else{
		echo'<div class="col-lg-3" style="margin: 10% 37.5% 0 37.5%; background: #cecece; border-radius: 10px;" >'."\n";
		echo form_open('usuarios/login', array('role'=>'form'))."\n";
		echo form_fieldset('Indetifique-se')."\n";
		erros_validacao();
        get_msg('logoffok');
        get_msg('errologin');
        echo '<div class="form-group">'."\n";
        echo form_label('Usuario')."\n";
        echo form_input(array('name'=>'usuario','class'=>'form-control'), set_value('usuario'), 'autofocus')."\n";                        
        echo '</div>'."\n";
        echo '<div class="form-group">'."\n";
        echo form_label('Senha')."\n";
        echo form_password(array('name'=>'senha','class'=>'form-control'), set_value('senha'))."\n";                        
        echo '</div>'."\n";
        echo form_hidden('redir', $this->session->userdata('redir_para'));
        echo form_submit(array('name'=>'logar','class'=>'btn btn-sn btn-success'),'Login')."\n";
        echo '<p>'.anchor('usuarios/nova_senha', 'Esqueci Minha Senha').'</p>'."\n";
        echo form_fieldset_close()."\n";
        echo form_close();
        echo'</div>'."\n";}
		break;
    case 'nova_senha':
        echo'<div class="col-lg-3" style="margin: 10% 37.5% 0 37.5%; background: #cecece; border-radius: 10px;" >'."\n";
        echo form_open('usuarios/nova_senha', array('role'=>'form'))."\n";
        echo form_fieldset('Nova senha')."\n";
        erros_validacao();
        get_msg('msgok');
        get_msg('msgerro');
        echo '<div class="form-group">'."\n";
        echo form_label('E-Mail')."\n";
        echo form_input(array('name'=>'email','class'=>'form-control'), set_value('email'), 'autofocus')."\n";                        
        echo '</div>'."\n";
        echo form_submit(array('name'=>'novasenha','class'=>'btn btn-sn btn-success'),'Enviar')."\n";
        echo '<p>'.anchor('usuarios/login', 'Vazer Login').'</p>'."\n";
        echo form_fieldset_close()."\n";
        echo form_close();
        echo'</div>'."\n";
        break;
    case 'cadastro':
        echo bt_row(array(
            form_open('usuarios/cadastro', array('role'=>'form')),
            form_fieldset(),
            bt_col(12,array(get_msg('msgok'),get_msg('msgerro'),erros_validacao())),
            bt_col(12,bt_input('Nome','nome',4)),
            bt_col(12,bt_input('Login','login',2)),
            bt_col(12,bt_input('E-Mail','email',4)),
            bt_col(12,bt_password('Senha','senha',2)),
            bt_col(12,bt_password('Repita Senha','senha2',2)),
            bt_col(12,bt_form(6,bt_checkbox('adm','1','Dar porder de ADM'))),
            bt_col(12,array(anchor('usuarios/gerenciar', 'Canselar', array('class'=>'btn btn-danger espaco')),bt_submit('Salvar','cadastrar',4,3))),
            form_fieldset_close(),
            form_close()
            ));
        break;
    case 'gerenciar':       
        bt_row(bt_col(12,array(get_msg('msgok'),get_msg('msgerro'))));
        ?>
       
         <div class="table-responsive">
            <table class="table table-hover table-striped data-table">
                <thead>
                    <tr>
                       <th>Nome<i class="fa"></i></th>
                       <th>Login<i class="fa"></i></th>
                       <th>E-Mail<i class="fa"></i></th>
                       <th >Ativo/Admin<i class="fa"></i></th>
                       <th class="text-center">Açãos<i class="fa"></i></th>
                    </tr>
                </thead>
                <?php  
                $query= $this->usuarios->get_all()->result();
                foreach ($query as $linha) {
                    echo'<tr>';
                    printf('<td>%s</td>',$linha->nome);
                    printf('<td>%s</td>',$linha->login);
                    printf('<td>%s</td>',$linha->email);
                    printf('<td>%s/%s</td>',($linha->ativo==0)?'Não':'Sim',($linha->adm==0)?'Não':'Sim');
                    printf('<td class="text-center">%s%s%s</td>', anchor('usuarios/editar/'.$linha->id, bt_icone('pencil'), array('title'=>'Editar')),anchor('usuarios/alterar_senha/'.$linha->id, bt_icone('key'), array('title'=>'Alterar senha')),anchor('usuarios/exclurir/'.$linha->id, bt_icone('trash-o'), array('class'=>'confDelete','title'=>'Exclurir')));
                    echo'</tr>';
                }
                ?>
                <tbody>
                </tbody>
            </table>
        </div>
        <?php
        break;
    case 'alterar_senha':
        $iduser=$this->uri->segment(3);
        if($iduser==NULL){set_msg('msgerro','Não foi informado um usuario valido.', 'aviso');
        redirect('usuarios/gerenciar');}
        if(is_admin()||$iduser==$this->session->userdata('user_id')){
        $query=$this->usuarios->get_byid($iduser)->row();
        echo bt_row(array(
            form_open(current_url(), array('role'=>'form')),
            form_fieldset(),
            bt_col(12,array(get_msg('msgok'),get_msg('msgerro'),erros_validacao())),
            bt_col(12,bt_input_disabled('Nome','nome',4,$query->nome)),
            bt_col(12,array(bt_input_disabled('Login','loginoff',2,$query->login),form_hidden('login', $query->login))),
            bt_col(12,bt_input_disabled('E-Mail','email',4,$query->email)),
            bt_col(12,bt_password('Senha','senha',2)),
            bt_col(12,bt_password('Repita Senha','senha2',2)),
            bt_col(12,array(anchor('usuarios/gerenciar', 'Canselar', array('class'=>'btn btn-danger espaco')),bt_submit('Salvar','alterarsenha',4,3))),
            form_hidden('idusuario', $iduser),
            form_fieldset_close(),
            form_close()
            ));
         }else{
            set_msg('msgerro','Não tem permição para alterar a senha.', 'aviso');
            redirect('usuarios/gerenciar'); 
         }
        break;
    case 'editar':
        $iduser=$this->uri->segment(3);
        if($iduser==NULL){set_msg('msgerro','Não foi informado um usuario valido.', 'aviso');
        redirect('usuarios/gerenciar');}
        if(is_admin()||$iduser==$this->session->userdata('user_id')){
        $query=$this->usuarios->get_byid($iduser)->row();
        echo bt_row(array(
            form_open(current_url(), array('role'=>'form')),
            form_fieldset(),
            bt_col(12,array(get_msg('msgok'),get_msg('msgerro'),erros_validacao())),
            bt_col(12,bt_input_cont('Nome','nome',4,$query->nome)),
            bt_col(12,array(bt_input_disabled('Login','loginoff',2,$query->login),form_hidden('login', $query->login))),
            bt_col(12,bt_input_disabled('E-Mail','email',4,$query->email)),
            ($iduser==1)?'<div class=col-md-12><br/> </div>':bt_col(12,bt_form(6,array(
                bt_checkbox('ativo','1','Ativa um usuario', ($query->ativo==1)?TRUE:FALSE),
                (is_admin())?bt_checkbox('adm','1','Dar porder de ADM',($query->adm==1)?TRUE:FALSE):'',
                ))),
            
            bt_col(12,array(anchor('usuarios/gerenciar', 'Canselar', array('class'=>'btn btn-danger espaco')),bt_submit('Salvar','alterarsenha',4,3))),
            form_hidden('idusuario', $iduser),
            form_fieldset_close(),
            form_close()
            ));
         }else{
            set_msg('msgerro','Não tem permição para alterar este usuario.', 'aviso');
            redirect('usuarios/gerenciar'); 
         }
        break;
	default:
		echo'<div class="alert alert-warning">
                    <strong>Warning!</strong> A tela solicitada não foi encontrada!!
                </div>';
		break;
}