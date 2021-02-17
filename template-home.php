<?php
/*Template Name: Home*/
$full_path = get_template_directory_uri();
while (have_posts()) : the_post();
    $_content_about = apply_filters('the_content', get_the_content());
    $_tabela_horarios = get_field('tabela_horarios');
    $_todos_os_leads = get_field('todos_os_leads');
    $_vouchers_permitidos = strtoupper(get_field('vouchers_permitidos'));
    $_vouchers_usados = strtoupper(get_field('vouchers_usados'));
endwhile;


//die();

//validar campo choucher
$_vouchers_explode_permitido = explode(", ", $_vouchers_permitidos);
$_vouchers_explode_usado = explode(", ", $_vouchers_usados);
$_img_msg = 'false';



//pegando o dia de hoje
$_dia_agora = date("d");
$_active_premio = 'false';
//definir timezone usei o de recife por nao ter horario de verao
date_default_timezone_set('America/Recife');
if ($_POST) {
    $_cpf_input = (isset($_POST['cpf'])) ? $_POST['cpf'] : '';
    $_aceita_input = (isset($_POST['aceita'])) ? $_POST['aceita'] : '';
    $_name_input = (isset($_POST['nome'])) ? $_POST['nome'] : '';
    $_email_input = (isset($_POST['email'])) ? $_POST['email'] : '';
    $_telefone_input = (isset($_POST['telefone'])) ? $_POST['telefone'] : '';
    $_lead_ganhador = $_POST['nome'].', '.$_POST['cpf'].', '.$_POST['email'].', '.$_POST['telefone'].', '.$_POST['voucher'].', '.$_POST['aceita'].', '.date('Y-m-d H:i:s');
    
    // atualizando os leds cadastrados
    update_field('todos_os_leads', $_lead_ganhador.' | '.$_todos_os_leads);
    
    // fazer um if para o voucher aqui se ele nao estiver na lista nao cadastra colocar no form = required=""
    if (!in_array(strtoupper($_POST['voucher']), $_vouchers_explode_usado)) {
        if (in_array(strtoupper($_POST['voucher']), $_vouchers_explode_permitido)) {
           
            //atualizando vouchers
            $_vouchers_explode_permitido = array_diff($_vouchers_explode_permitido, array(strtoupper($_POST['voucher'])));
            update_field('vouchers_permitidos', implode(', ', $_vouchers_explode_permitido));
            update_field('vouchers_usados', strtoupper($_POST['voucher']).', '.$_vouchers_usados);

            //pegando a hora de agora
            //$agora = strtotime("19:40:00");
            $agora = strtotime('now');

            //trocar o ganhador
            $campo_ganhador = '';

            $_sorteio_H = '';
            $_agora_H = date("H",$agora);
            $_continue_tentando = FALSE;
            if (is_array($_tabela_horarios)) {
                foreach ($_tabela_horarios as $key => $value) {
                    //dia_sorteio
                    //horario_sorteio
 
                    if ($_dia_agora == $value['dia_sorteio']) {
                       

                        $_horario_sorteio = $value['horario_sorteio'];
                        
                        foreach ($_horario_sorteio as $key2 => $value2) {
                            $campo_ganhador = $value2['ganhador_sorteio'];
                            
                            //verificando qual sorteio esta valendo por hora
                            $_sorteio_H = date("H", strtotime($value2['hora_para_sortear']));
                        
                            if ($_agora_H == $_sorteio_H){
                                
                                //echo "Prox Sorteio:".$value2['hora_para_sortear']." <br> HORARIO SORTEIO: ".strtotime($value2['hora_para_sortear'])."<br>HORARIO AGORA: ".$agora."<br>";
                                //verificando se o horario é igual
                                if(strtotime($value2['hora_para_sortear'])==$agora  &&  $campo_ganhador == ''){
                                    //definir ganhador aqui salvar no banco
                                    //
                                    update_sub_field(array('tabela_horarios', ($key+1), 'horario_sorteio', ($key2+1), 'ganhador_sorteio'), $_lead_ganhador);
                                    $_active_premio = 'active';
                                    $_img_msg = 'false';

                                break;
                                //aqui eu verifico se o horario ja passou e se o ganhador esta vazio
                                } else if (strtotime($value2['hora_para_sortear'])<$agora && $campo_ganhador == ''){
                                    //echo "Ganhador por aproximação é quando o campo ganhador está vazio e o horario de agora é maior que o sorteio";
                                    //definir ganhador aqui salvar no banco
                                    //
                                    update_sub_field(array('tabela_horarios', ($key+1), 'horario_sorteio', ($key2+1), 'ganhador_sorteio'), $_lead_ganhador);
                                    $_active_premio = 'active';
                                    $_img_msg = 'false';
                                    
                                break;
                                //Caso nenhum não seja o horario certou ou já tenha uma ganhador dentro do horario ele segue a pagina de tente de novo
                                } else {
                                // header('location: minhapagina.php');
                                alertMsg("Que pena, não foi dessa vez.");
                                $_img_msg = 'active';
                                }
                            }
                        }
                    } else {
                        // header('location: minhapagina.php');
                        //alertMsg("Que pena, não foi dessa vez.");
                    
                        $_img_msg = 'active';
                    
                    }
                }
            }
        }else {
            // header('location: minhapagina.php');
        
            alertMsg("Voucher inválido!");
            $_img_msg = 'active';

        }
    }else{
        // header('location: minhapagina.php');
        
        alertMsg("Voucher já utilizado!");
        $_img_msg = 'active';

    }


function alertMsg($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

function alert($msg) {
   // echo "<script type='text/javascript'>alert('$msg');</script>";
}


get_header();
?>

<style>
.box-ganhador.false, .box-nao-foi-dessa-vez {
    display: none;
}

.box-ganhador.active, .box-nao-foi-dessa-vez.active {
    display: block;
}


.box-formulario.false {
    display: block;
}

.box-formulario.active {
    display: none;
}

.formulario-sorteio .container {
    border: 8px solid #edb267;
}

.container form {
    max-width: 950px;
    margin: 0 auto;
}


</style>

<section class="formulario-sorteio p-5">
    <div class="container">
        <div class="box-formulario <?=$_active_premio?> <?php echo $_img_msg ?>">
            <div class="row">
                <div class="col-md-4">
                    <img src="<?=$full_path?>/assets/img/logo.png" alt="Natal">
                </div>
                <div class="col-md-8">
                    <img src="<?=$full_path?>/assets/img/voce-ja-encontrou.png" alt="Você já encontrou o presente ideal. Agora é hora de concorrer a um vale-compra de R$500! Preencha os campos abaixo e boa sorte! ">
                </div>
            </div>
            <?=$_content_about?>
        </div>
        <div class="box-ganhador <?=$_active_premio?>">
            <p class="text-center"><img src="<?=$full_path?>/assets/img/ganhador.png" alt="Ganhador" class="img-fluid">
            <a href="<?=get_site_url()?>" class="btn btn-warning">Continuar cadastrando</a></p>
        </div>
        <div class="box-nao-foi-dessa-vez  text-center <?php echo $_img_msg ?>">
            <p class="text-center"><img src="<?=$full_path?>/assets/img/nao-foi-dessa-vez.png" alt="Não foi dessa vez!" class="img-fluid">
            <div class="btn btn-warning text-center continue">Continuar cadastrando</div></p>
        </div>
    </div>
</section>



<script>
var _name_input = '<?=$_name_input?>';
var _cpf_input = '<?=$_cpf_input?>';
var _aceita_input = '<?=$_aceita_input?>';
var _email_input = '<?=$_email_input?>';
var _tel_input = '<?=$_telefone_input?>';

jQuery("#aceita").change(function() {
   
        if(jQuery('#enviar').hasClass('disabledOff')){
            jQuery('#enviar').removeClass("disabledOff");
            jQuery('#enviar').prop("disabled", false);
        }else{
            jQuery('#enviar').addClass("disabledOff");
            jQuery('#enviar').prop("disabled", true);
        }
        
});

jQuery('#nome').attr('value', _name_input);
jQuery('#email').attr('value', _email_input);
jQuery('#cpf').attr('value', _cpf_input);
jQuery('#telefone').attr('value', _tel_input);
jQuery('.box-ganhador.false').remove();
jQuery('.continue').click(function (e) { 
    e.preventDefault();
    jQuery('.box-nao-foi-dessa-vez.active').removeClass('active');
    jQuery('.box-formulario.active').removeClass('active');
});
</script>

<?php get_footer(); ?>