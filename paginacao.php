<?php

// Parâmetros de paginação
$limit = 10;  // Número de registos por página
$offset = ($page - 1) * $limit;  // Calcular o deslocamento da tabela que vai aparecer sem isso os registos da tabela só se repetem(OFFSET)
$page_intervalo = 3; //O intervalo de páginas que vai aparecer

//Consulta para contar o número total de registos (sem LIMIT)
$count_query = "SELECT COUNT(*) AS total_registos 
                FROM tbl_aluno_ano AS tbano
                INNER JOIN tbl_alunos AS tbu ON tbano.id_aluno = tbu.id_aluno
                INNER JOIN tbl_ano_turma AS ats ON ats.idat = tbano.idat
                INNER JOIN tbl_user_aluno AS tpw ON tpw.email_aluno = tbu.email_aluno";

$result_count = mysqli_query($ligaDB, $count_query);
$total_registos = mysqli_fetch_assoc($result_count)['total_registos'];//Para dizer quantos registos foram criados e coloca num alias 
// Calcular o número total de páginas
$total_paginas = ceil($total_registos / $limit);
echo "<div>"; // Uma div para depois centralizar tudo
echo "<a href='?page=1'>Primeira</a>&nbsp;&nbsp;&nbsp;"; // Para ir direto para a primeira página

$last_page = $page > 1 ? $page - 1 : 1; //Função para voltar uma página
echo "<a href='?page=$last_page'><<</a> ";  //Para onde a função de voltar uma página foi

$prim_page = max($page - $page_intervalo,1); //Para que o intervalo de páginas não crie páginas negativas
$ult_page = min ($total_paginas, $page + $page_intervalo); //Para que o intervalo de páginas não crie páginas a mais 
for ($p = $prim_page; $p <= $ult_page; $p++) { //Para navegar entre as páginas
    if ($p == $page) {
        echo "<b>$p</b> "; // Página atual destacada em bold
    } else {
        echo "<a href='?page=$p'>$p</a> ";
    }
}

$next_page = $page < $total_paginas ? $page + 1 : $total_paginas; //Função para avançar uma página
echo "<a href='?&page=$next_page'>>></a>&nbsp;&nbsp;&nbsp;";

echo "<a href='?page=$total_paginas'>Última</a>"; //Para ir direto para a ultima pagina
echo "</div>";
?>