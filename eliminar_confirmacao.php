<?php
include("config.php");
if (isset($_POST['id_aluno'])) {
    $id_aluno = $_POST['id_aluno'];

    $eliminar_user = "DELETE FROM tbl_user_aluno 
                      WHERE email_aluno = (SELECT email_aluno FROM tbl_alunos WHERE id_aluno = $id_aluno)";;
    $resultado_user = mysqli_query($ligaDB, $eliminar_user);

    //Pego o idat para usar como id
    $consulta_idat = "SELECT idat FROM tbl_aluno_ano WHERE id_aluno = $id_aluno";
    $resultado_idat = mysqli_query($ligaDB, $consulta_idat);
    
    //verifica se o idat é maior que 0 e depois põe numa variavel
    if ($resultado_idat && mysqli_num_rows($resultado_idat) > 0) {
        $idat = mysqli_fetch_assoc($resultado_idat)['idat'];
    
        // Verificar se existem outros alunos na turma com o idat`
        $verificar_ano_turma = "SELECT COUNT(*) as tudo FROM tbl_aluno_ano WHERE idat = $idat";
        $resultado_verificacao = mysqli_query($ligaDB, $verificar_ano_turma);
        $contador = mysqli_fetch_assoc($resultado_verificacao)['tudo'];
        
        //Apaga a relação para conseguir apagar o resto
        $eliminar_aluno_ano = "DELETE FROM tbl_aluno_ano WHERE id_aluno = $id_aluno";
        $resultado = mysqli_query($ligaDB, $eliminar_aluno_ano);


        // Se não existirem outros alunos, elimina na `tbl_ano_turma`
        if ($contador == 1) {
            $eliminar_ano_turma = "DELETE FROM tbl_ano_turma WHERE idat = $idat";
            mysqli_query($ligaDB, $eliminar_ano_turma);
        }
    }
    

    $eliminar_aluno = "DELETE FROM tbl_alunos WHERE  id_aluno = $id_aluno";
    $resultado_aluno = mysqli_query($ligaDB, $eliminar_aluno);


    if ($resultado) {
        echo "<div style='text-align: center; color: white; background-color: #1a1a3d; padding: 20px; border-radius: 10px;'>
                <h2>O aluno foi eliminado com sucesso!</h2>
                <a href='tabela.php'><button style='background-color: purple; color: white; padding: 10px 20px; border: none; border-radius: 5px;'>Voltar para a Tabela</button></a>
              </div>";
    } else {
        echo "Erro ao eliminar o aluno: " . mysqli_error($ligaDB);
    }
} 
?>

