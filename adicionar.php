<?php 
    // Incluir o ficheiro de configuração para conectar à base de dados
    include("config.php");

    // Receber e escapar os dados enviados do formulário para evitar injeção SQL
    $ano = isset($_POST["ano"]) ? mysqli_real_escape_string($ligaDB, $_POST["ano"]) : null;
    $turma = isset($_POST["turma"]) ? mysqli_real_escape_string($ligaDB, $_POST["turma"]) : null;
    $nome = isset($_POST["nome"]) ? mysqli_real_escape_string($ligaDB, $_POST["nome"]) : null;
    $user = isset($_POST["user"]) ? mysqli_real_escape_string($ligaDB, $_POST["user"]) : null;
    $email = isset($_POST["email"]) ? mysqli_real_escape_string($ligaDB, $_POST["email"]) : null;
    $pw = isset($_POST["pw"]) ? mysqli_real_escape_string($ligaDB, $_POST["pw"]) : null;

    // Mostrar mensagem de confirmação com os dados inseridos pelo utilizador
    echo "<h2>Tem a certeza que deseja adicionar o seguinte aluno?</h2>";
    echo "<p><strong>Ano:</strong> $ano</p>";
    echo "<p><strong>Turma:</strong> $turma</p>";
    echo "<p><strong>Nome:</strong> $nome</p>";
    echo "<p><strong>User:</strong> $user</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Password:</strong> $pw</p>";

    // Formulário de confirmação com dados ocultos para reenviar quando o utilizador confirmar
    echo '<form method="post">';
    echo '<input type="hidden" name="ano" value="' . $ano . '">';
    echo '<input type="hidden" name="turma" value="' . $turma . '">';
    echo '<input type="hidden" name="nome" value="' . $nome . '">';
    echo '<input type="hidden" name="user" value="' . $user . '">';
    echo '<input type="hidden" name="email" value="' . $email . '">';
    echo '<input type="hidden" name="pw" value="' . $pw . '">';
    echo '<button type="submit" name="confirmar" value="sim">Sim</button>';
    echo '<button type="submit" name="confirmar" value="nao">Não</button>';
    echo '</form>'; 
    
    // Verificar se o utilizador clicou em "Sim" ou "Não" no formulário de confirmação
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {

        // Se o utilizador clicou em "Sim", proceder com a inserção na base de dados
        if ($_POST['confirmar'] === "sim") {
            // Inserir os dados do aluno na tabela `tbl_alunos`
            $inserir_aluno = "INSERT INTO tbl_alunos (nome_aluno, user_aluno, email_aluno) VALUES ('$nome', '$user', '$email')";
            mysqli_query($ligaDB, $inserir_aluno);
            
            // Obter o ID do aluno recém-inserido para usar nas próximas relações
            $id_aluno = mysqli_insert_id($ligaDB);

            // Verificar se o ano e a turma já existem na tabela `tbl_ano_turma`
            $consulta_turma = "SELECT idat FROM tbl_ano_turma WHERE ano = '$ano' AND turma = '$turma'";
            $resultado_turma = mysqli_query($ligaDB, $consulta_turma);
            
            if (mysqli_num_rows($resultado_turma) > 0) {
                // Se o ano e a turma já existirem, obter o ID correspondente
                $row = mysqli_fetch_assoc($resultado_turma);
                $idat = $row['idat'];
            } else {
                // Se o ano e a turma não existirem, criar um novo registo e obter o novo ID
                $inserir_turma = "INSERT INTO tbl_ano_turma (ano, turma) VALUES ('$ano', '$turma')";
                mysqli_query($ligaDB, $inserir_turma);
                $idat = mysqli_insert_id($ligaDB); // Obter o ID recém-inserido
            }

            // Inserir a relação aluno-ano/turma na tabela `tbl_aluno_ano`
            $inserir_relacao = "INSERT INTO tbl_aluno_ano (id_aluno, idat) VALUES ('$id_aluno', '$idat')";
            mysqli_query($ligaDB, $inserir_relacao);

            // Inserir o user e a password na tabela `tbl_user_aluno`
            $inserir_user = "INSERT INTO tbl_user_aluno (nome_aluno, email_aluno, aluno_pw) VALUES ('$nome','$email', '$pw')";
            mysqli_query($ligaDB, $inserir_user);

            // Confirmar a inserção e redirecionar para o formulário inicial se tudo correr bem
            if (mysqli_commit($ligaDB)) {
                header("Location: adicionar.html"); // Voltar para o formulário
                exit();
            } else {
                // Exibir mensagem de erro se a inserção falhar
                echo "<h2 style='color: red;'>Erro: " . mysqli_error($ligaDB) . "</h2>";
            }

        // Se o utilizador clicou em "Não", cancelar e redirecionar para o formulário inicial
        } elseif ($_POST['confirmar'] === "nao") {
            header("Location: adicionar.html"); // Voltar para o formulário
            exit();
        }
    }
?>
