<?php
    // Inicia a sessão
    session_start();

    // Configuração para conectar à base de dados
    include('config.php');


    $user = $_POST['username'];
    $password = $_POST['password'];


    $user = mysqli_real_escape_string($ligaDB, $user);
    $password = mysqli_real_escape_string($ligaDB, $password);

    // SQL
    $sql = "SELECT * FROM tbl_utilizadores WHERE nome_user= '$user' AND pw_user= '$password'";
    
    // Executa a consulta SQL
    $result = mysqli_query($ligaDB, $sql);

    // Verifica se a consulta foi bem-sucedida
    if ($result) {
        // Verifica se há resultados
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row['nome_user'];
            $_SESSION['user_type'] = $row['user_type'];
            header("Location: tabela.php"); 
            exit;
        } else {
            // Mensagem de erro se o utilizador ou senha estiverem incorretos
            echo "Nome de utilizador ou password estão incorretos.";
        }
    } else {
        // Exibe o erro da consulta SQL, se houver
        echo "Erro na consulta: " . mysqli_error($ligaDB);
    }

    // Fecha a conexão com a base de dados
    mysqli_close($ligaDB);
?>
