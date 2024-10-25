<?php
include("config.php");
// Verificar se o ID do aluno foi passado via GET
if ($_GET['id_aluno']) {
    $id_aluno = $_GET['id_aluno'];

    // Buscar os dados do aluno no banco de dados
    $consulta = "SELECT * FROM tbl_aluno_ano AS tbano
            INNER JOIN tbl_alunos AS tbu ON tbano.id_aluno = tbu.id_aluno
            INNER JOIN tbl_ano_turma AS ats ON ats.idat = tbano.idat
            INNER JOIN tbl_user_aluno AS tpw ON tpw.email_aluno = tbu.email_aluno
            WHERE tbu.id_aluno = $id_aluno";
    $resultado = mysqli_query($ligaDB, $consulta);

    // Verificar se a consulta obteve resultados
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $aluno = mysqli_fetch_assoc($resultado);
        $nome = $aluno['nome_aluno'];
        $email = $aluno['email_aluno'];
        $user = $aluno['user_aluno'];
        $turma = $aluno['turma'];
        $ano = $aluno['ano'];
        $aluno_pw = $aluno['aluno_pw']; 
    } else {
        echo "Não foram encontrados dados para este aluno.";
    }
} else {
    echo "ID do aluno não foi especificado.";
}
if (!$resultado) {
    echo "Erro na consulta: " . mysqli_error($ligaDB);
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR ALUNO</title>
    <link rel="stylesheet" href="form_style.css">
</head>
<body>
    <a href="tabela.php" class="voltar-btn">Voltar</a>
    <div class="form-container">
        <h2>Editar Cliente</h2>
        <form action="editar.php" method="post">
    <input type="hidden" name="id_aluno" value="<?php echo $id_aluno ?>">

    <label for="ano">Ano</label>
    <input type="number" id="ano" name="ano" placeholder="Ano" required min="1" max="12" value="<?php echo $ano?>">

    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" placeholder="Nome" required value="<?php echo $nome ?>">

    <label for="turma">Turma</label>
    <input type="text" id="turma" name="turma" placeholder="Turma" required maxlength="1" style="text-transform: uppercase;" value="<?php echo $turma ?>">

    <label for="user">User</label>
    <input type="text" id="user" name="user" placeholder="User" maxlength="10" required value="<?php echo $user?>">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo $email ?>">

    <label for="password">Password</label>
    <input type="text" id="password" name="password" placeholder="Password" required value="<?php echo $aluno_pw ?>">

    <a href='tabela.php'><button type="submit">Enviar</button> </a>
    
</form>

    </div>
</body>
</html>

<?php

// Verificar se o formulário foi submetido para atualizar os dados (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_aluno'])) {
    $id_aluno = $_POST['id_aluno'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $user = $_POST['user'];
    $turma = $_POST['turma'];
    $ano = $_POST['ano'];
    $aluno_pw = $_POST['password'];

    // Atualizar `tbl_user_aluno`
    $editar_user = "UPDATE tbl_user_aluno as tpw
                    INNER JOIN tbl_alunos  as tbu ON tpw.email_aluno = tbu.email_aluno
                    SET tpw.nome_aluno = '$nome',
                    tpw.email_aluno = '$email',
                    tpw.aluno_pw = '$aluno_pw'
                    WHERE tbu.id_aluno = $id_aluno;";
                    
    mysqli_query($ligaDB, $editar_user);

    // Atualizar `tbl_alunos`
    $editar_aluno = "UPDATE tbl_alunos SET nome_aluno = '$nome',
                    user_aluno = '$user', 
                    email_aluno = '$email' 
                    WHERE id_aluno = $id_aluno";

    mysqli_query($ligaDB, $editar_aluno);

    

    // Verificar se o ano e a turma já existem na `tbl_ano_turma`
    $verificar_ano_turma = "SELECT idat 
                            FROM tbl_ano_turma 
                            WHERE ano = '$ano' AND turma = '$turma'";
    $resultado_ano_turma = mysqli_query($ligaDB, $verificar_ano_turma);

    if (mysqli_num_rows($resultado_ano_turma) == 0) {
        $inserir_ano_turma = "INSERT INTO tbl_ano_turma (ano, turma) VALUES ('$ano', '$turma')";
        mysqli_query($ligaDB, $inserir_ano_turma);
        $idat = mysqli_insert_id($ligaDB);
    } else {
        $linha = mysqli_fetch_assoc($resultado_ano_turma);
        $idat = $linha['idat'];
    }

    // Atualizar `tbl_aluno_ano`
    $editar_aluno_ano = "UPDATE tbl_aluno_ano SET
                        idat = $idat 
                        WHERE id_aluno = $id_aluno";
    mysqli_query($ligaDB, $editar_aluno_ano);
    header("Location: tabela.php");
    exit();
}
?>