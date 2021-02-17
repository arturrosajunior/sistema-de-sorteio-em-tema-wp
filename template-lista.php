<?php
/*Template Name: LISTAGEM*/
$full_path = get_template_directory_uri();
while (have_posts()) : the_post();
    $_content_about = apply_filters('the_content', get_the_content());
    $_tabela_horarios = get_field('tabela_horarios', 2);
endwhile;

if (is_array($_tabela_horarios)) {
    foreach ($_tabela_horarios as $key => $value) {
        //dia_sorteio
        //horario_sorteio
            //ganhador_sorteio
            //hora_para_sortear
            $_hr_sorteio = $value['horario_sorteio'];
            if (is_array($_hr_sorteio)) {
                foreach ($_hr_sorteio as $key2 => $value2) {
                    $_hora_para_sortear = $value2['hora_para_sortear'];
                    $_ganhador = $value2['ganhador_sorteio'];

                    $_ganhadores .= 'Dia: '.$value['dia_sorteio'].' - Hora: '.$_hora_para_sortear.' | Ganhador: '.$_ganhador.'<br>';

                }

            }
            
    }
}

echo $_ganhadores;

get_header();
?>





<?php get_footer(); ?>