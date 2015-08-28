<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
switch ($tela) {
    case 'cadastrar':
        echo bt_row(array(
            form_open_multipart('midia/cadastrar'),
            form_fieldset(),
            bt_col(12,array(get_msg('msgok'),get_msg('msgerro'),erros_validacao())),
            bt_col(12,bt_input('Titulo midia','nome',4)),
            bt_col(12,bt_input('Descrição','descricao',2)),
            bt_col(12,bt_input('Alt','alt',2)),
            bt_col(12,bt_form(12,array(form_label('Arquivo'),form_upload(array('name'=>'arquivo'),set_value('arquivo'))))),
            bt_col(12,array(anchor('midia/gerenciar', 'Canselar', array('class'=>'btn btn-danger espaco')),bt_submit('Salvar','cadastrar',4,3))),
            form_fieldset_close(),
            form_close()
            ));
        break;
    case 'editar':
        $id=$this->uri->segment(3);
        if($id==NULL){set_msg('msgerro','Não foi informado uma midia valido.', 'aviso');
        redirect('midia/gerenciar');}
        $query=$this->midia->get_byid($id)->row();
        echo bt_row(array(
            form_open(current_url()),
            form_fieldset(),
            bt_col(12,array(get_msg('msgok'),get_msg('msgerro'),erros_validacao())),
            
            bt_col(6,array(
                bt_col(12,bt_input_cont('Titulo midia','nome',12,$query->nome)),
                bt_col(12,bt_input_cont('Descrição','descricao',12,$query->descricao)),
                bt_col(12,bt_input_cont('Alt','alt',12,$query->alt)),
                bt_col(12,array(anchor('midia/gerenciar', 'Canselar', array('class'=>'btn btn-danger espaco')),bt_submit('Salvar','cadastrar',4,3)))
                )),
            bt_col(6,trumb($query->arquivo,300,250,TRUE,$query->alt)),
            form_hidden('id', $id),
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
                       <th>Link<i class="fa"></i></th>
                       <th>Miniaturar<i class="fa"></i></th>
                       <th class="text-center">Ação<i class="fa"></i></th>
                    </tr>
                </thead>
                <?php  
                $query= $this->midia->get_all()->result();
                foreach ($query as $linha) {
                    echo'<tr>';
                    printf('<td>%s</td>',$linha->nome);
                    printf('<td>%s</td>','<input type="text" value="'.base_url('uploads/'.$linha->arquivo).'"/>');
                    printf('<td>%s</td>',anchor("uploads/$linha->arquivo", trumb($linha->arquivo,100,75,TRUE,$linha->alt), array('title'=>'Visualizar')));
                    printf('<td class="text-center">%s%s</td>',anchor('midia/editar/'.$linha->id, bt_icone('pencil'), array('title'=>'Editar')),anchor('midia/exclurir/'.$linha->id, bt_icone('trash-o'), array('class'=>'confDelete','title'=>'Exclurir')));
                    echo'</tr>';
                }
                ?>
                <tbody>
                </tbody>
            </table>
        </div>
        <?php
        break;

	default:
		echo'<div class="alert alert-warning">
                    <strong>Warning!</strong> A tela solicitada não foi encontrada!!
                </div>';
		break;
}