<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
switch ($tela) {
    case 'logs':
        if($this->uri->segment(3)=='all'){
            $l=0;
        }else{
            $l=50;
            echo '<p>Auditoria limitada nos utimos 50 registros para ver todos '.anchor('auditoria/logs/all', 'clique aqui.');
        }       
        ?>
         <div class="table-responsive">
            <table class="table table-hover table-striped data-table">
                <thead>
                    <tr>
                       <th>User<i class="fa"></i></th>
                       <th>Ip<i class="fa"></i></th>
                       <th>Data<i class="fa"></i></th>
                       <th >Operação:<i class="fa"></i></th>
                       <th >Obs:<i class="fa"></i></th>
                    </tr>
                </thead>
                <?php  
                $query= $this->auditoria->get_all($l)->result();
                foreach ($query as $linha) {
                    echo'<tr>';
                    printf('<td>%s</td>',$linha->usuario);
                    printf('<td>%s</td>',$linha->ip);
                    printf('<td>%s</td>',date('d/m/Y H:i:s', strtotime($linha->data)));
                    printf('<td>%s</td>',$linha->operacao);
                    printf('<td>%s</td>','<span data-toggle="tooltip" data-placement="left" title="'.$linha->query.'">'.$linha->observacao.'</span>');
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