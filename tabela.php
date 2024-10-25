<?php
session_start(); // Inicia a sessão

// Simulando a autenticação do utilizador
$user = ''; 


if ($user === 'Otacilio') {
    $_SESSION['user_name'] = 'Otacilio';
    $_SESSION['user_type'] = 'utilizador'; 
} elseif ($user === 'Rodrigo') {
    $_SESSION['user_name'] = 'Rodrigo';
    $_SESSION['user_type'] = 'editor'; 
} elseif ($user === 'Florin') {
    $_SESSION['user_name'] = 'Florin';
    $_SESSION['user_type'] = 'administrador'; 
}



?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Dados</title>
    <link rel="stylesheet" href="tabela_style.css">
    <script>
        function manageButtons() {
            var userType = "<?php echo $_SESSION['user_type']; ?>"; // Captura o tipo de usuário do PHP
            
            // Habilita ou desabilita botões 
            var btnEditar = document.getElementById("btn-editar");
            var btnEliminar = document.getElementById("btn-eliminar");
            var btnAdicionar = document.getElementById("btn-adicionar");

            //  Habilitar ou desabilitar 
            if (userType === 'administrador') {
                btnEditar.disabled = false;
                btnEliminar.disabled = false;
                btnAdicionar.disabled = false;
            } else if (userType === 'editor') {
                btnEditar.disabled = false;
                btnEliminar.disabled = true; // Editor não pode eliminar
                btnAdicionar.disabled = false; // Editor pode adicionar
            } else if (userType === 'utilizador') {
                btnEditar.disabled = true; // Utilizador não pode editar
                btnEliminar.disabled = true; // Utilizador não pode eliminar
                btnAdicionar.disabled = true; // Utilizador não pode adicionar
            }
        }

        // Função criada chamada ao selecionar um aluno
        function onSelectAluno() {
            var userType = "<?php echo $_SESSION['user_type']; ?>";
            
            var btnEditar = document.getElementById("btn-editar");
            var btnEliminar = document.getElementById("btn-eliminar");

            // Verifica novamente o tipo de usuário ao selecionar o aluno
            if (userType === 'administrador') {
                btnEditar.disabled = false;
                btnEliminar.disabled = false;
            } else if (userType === 'editor') {
                btnEditar.disabled = false;
                btnEliminar.disabled = true; // Editor não pode eliminar
            } else if (userType === 'utilizador') {
                btnEditar.disabled = true; // Utilizador não pode editar
                btnEliminar.disabled = true; // Utilizador não pode eliminar
            }
        }

        // Chama a função quando a página carrega
        window.onload = manageButtons;
    </script>
</head>
<body>
<a href="logout.php" class="logout-btn">Logout</a>

    <form action="adicionar.html" method="post" style="display:inline;">
        <button type="submit" id="btn-adicionar" style="background-color: green; color: white;">Adicionar</button>
    </form>
    <form action="editar.php" method="get" style="display:inline;">
        <input type="hidden" name="id_aluno" id="id_aluno_editar">
        <button type="submit" id="btn-editar" style="background-color: blue; color: white;" disabled>Editar</button>
    </form>
    <form action="eliminar.php" method="post" style="display:inline;">
        <input type="hidden" name="id_aluno" id="id_aluno_eliminar">
        <button type="submit" id="btn-eliminar" style="background-color: red; color: white;" disabled>Eliminar</button>
    </form>

    <h2 style="text-align:center;">Tabela de Dados da Base de Dados</h2>

    <table>
        <thead>
            <tr>
                <th><a href="?coluna=ano&direcao=<?php echo $nova_direcao; ?>">Ano</a></th>
                <th><a href="?coluna=turma&direcao=<?php echo $nova_direcao; ?>">Turma</a></th>
                <th><a href="?coluna=nome_aluno&direcao=<?php echo $nova_direcao; ?>">Nome</a></th>
                <th>User</th>
                <th>Email</th>
                <th>Password</th>
                <th>Selecionar</th>
            </tr>
        </thead>
        <tbody>

            <?php 
            include("config.php");
            $coluna = isset($_GET['coluna']) ? $_GET['coluna'] : 'ano';
            $direcao = isset($_GET['direcao']) ? $_GET['direcao'] : 'ASC';
            $nova_direcao = ($direcao === 'ASC') ? 'DESC' : 'ASC';

            $page = isset($_GET['page']) ? ($_GET['page']) : 1;
            $limit = 10;  // Número de registos por página
            $offset = ($page - 1) * $limit;  

            
            $consulta = "SELECT * FROM tbl_aluno_ano AS tbano
            INNER JOIN tbl_alunos AS tbu ON tbano.id_aluno = tbu.id_aluno
            INNER JOIN tbl_ano_turma AS ats ON ats.idat = tbano.idat
            INNER JOIN tbl_user_aluno AS tpw ON tpw.email_aluno = tbu.email_aluno
            ORDER BY $coluna $direcao
            LIMIT $limit OFFSET $offset";

            $resultado = mysqli_query($ligaDB, $consulta);
            include("pesquisa.php");
            // Tabela de registos
            while ($registos = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $registos["ano"] . "</td>";
                echo "<td>" . $registos["turma"] . "</td>";
                echo "<td>" . $registos["nome_aluno"] . "</td>";
                echo "<td>" . $registos["user_aluno"] . "</td>";
                echo "<td>" . $registos["email_aluno"] . "</td>";
                echo "<td>" . $registos["aluno_pw"] . "</td>";
                echo "<td style='text-align: center;'>
                    <input type='radio' name='selecionar' value='" . $registos['id_aluno'] . "' 
                    onclick='document.getElementById(\"id_aluno_editar\").value=\"" . $registos['id_aluno'] . "\"; 
                            document.getElementById(\"id_aluno_eliminar\").value=\"" . $registos['id_aluno'] . "\"; 
                            onSelectAluno();'>
                </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
            include("paginacao.php");
            echo "<h3> Foram encontrados $total_registos registos </h3>";
            echo "Número de páginas: $total_paginas";
            ?>
</body>
</html>