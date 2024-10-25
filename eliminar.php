<?php
include("config.php");

// Verificar se o ID do aluno foi passado via GET
if (isset($_POST['id_aluno'])) {
    $id_aluno = $_POST['id_aluno'];

   
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
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Aluno</title>
    <link rel="stylesheet" href="eliminar_style.css">

</head>
<body>
    <a href="tabela.php" class="voltar-btn">Voltar</a>
    <div class="confirm-container">
        <h2>Tem a certeza que quer eliminar este aluno?</h2>
        <p><strong>Ano:</strong> <?php echo isset($ano) ? $ano : ''; ?></p>
        <p><strong>Turma:</strong> <?php echo isset($turma) ? $turma : ''; ?></p>
        <p><strong>Nome:</strong> <?php echo isset($nome) ? $nome : ''; ?></p>
        <p><strong>Email:</strong> <?php echo isset($email) ? $email : ''; ?></p>
        <p><strong>User:</strong> <?php echo isset($user) ? $user : ''; ?></p>
        <p><strong>Password:</strong> <?php echo isset($aluno_pw) ? $aluno_pw : ''; ?></p>

        
        <form action="eliminar_confirmacao.php" method="post">
            <input type="hidden" name="id_aluno" value="<?php echo $id_aluno; ?>">
            <button type="submit" class="btn btn-sim">Sim</button>
        </form>
        
        <form action="tabela.php" method="get">
            <button type="submit" class="btn btn-nao">Não</button>
        </form>
    </div>
</body>
</html>

