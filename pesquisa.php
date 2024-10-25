<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pesquisar</title>
</head>
<body>
<div class="box-search">
        <form action="" method="get">
            <input name="pesquisa" placeholder="Pesquisar" type="text">
            <button type="submit" style="background-color: #D8BFD8; color: white;">Pesquisar</button> 
            <a href="tabela.php">Limpar Pesquisa</a> <!-- Link para resetar a pesquisa -->
        </form>
    </div>
</body>
</html>
<?php
    $pesquisa = isset($_GET['pesquisa']) ? mysqli_real_escape_string($ligaDB, $_GET['pesquisa']) : '';
    // Definir paginação
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;  // Página atual
    $limit = 10;  // Número de registos por página
    $offset = ($page - 1) * $limit;  // Calcular o deslocamento para a tabela
    
    // Iniciar a consulta SQL
    $consulta = "SELECT * 
    FROM tbl_aluno_ano AS tbano
    INNER JOIN tbl_alunos AS tbu ON tbano.id_aluno = tbu.id_aluno
    INNER JOIN tbl_ano_turma AS ats ON ats.idat = tbano.idat
    INNER JOIN tbl_user_aluno AS tpw ON tpw.email_aluno = tbu.email_aluno
    LIMIT $limit OFFSET $offset
";

    if (!empty($pesquisa)) {
        $consulta = "SELECT * FROM tbl_aluno_ano AS tbano
        INNER JOIN tbl_alunos AS tbu ON tbano.id_aluno = tbu.id_aluno
        INNER JOIN tbl_ano_turma AS ats ON ats.idat = tbano.idat
        INNER JOIN tbl_user_aluno AS tpw ON tpw.email_aluno = tbu.email_aluno
        Where tbu.nome_aluno LIKE '%$pesquisa%'
        or tbu.user_aluno LIKE '%$pesquisa%'
        or ats.ano LIKE '%$pesquisa%'
        or ats.turma LIKE  '%$pesquisa%'
        ORDER BY ats.ano
        LIMIT $limit OFFSET $offset";
        }

$resultado = mysqli_query($ligaDB, $consulta);
