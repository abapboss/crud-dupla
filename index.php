<?php

$servername = "localhost";
$user = "root";
$pasword = "root";
$dbname = "crud_ruanlucas";

$conn = new mysqli($servername, $user, $pasword, $dbname);

if ($conn->connect_error) {
    die("Conexao falhou: " . $conn->connect_error);
}

if (isset($_GET['deleteProfessor'])) {
    $id = $_GET['deleteProfessor'];

    $sql = "DELETE FROM professor_aula WHERE id_professor = '$id'";
    if ($conn->query($sql) === true) {
        echo "Aulas do professor excluidas com sucesso.";
    }
    $sql = "DELETE FROM professores WHERE id = '$id'";
    if ($conn->query($sql) === true) {
        echo "Professor excluido com sucesso.";
    }
    header("Location: index.php");
    exit();
} else if (isset($_GET['deleteAula'])) {
    $id = $_GET['deleteAula'];

    $sql = "DELETE FROM professor_aula WHERE id_aula = '$id'";
    if ($conn->query($sql) === true) {
        echo "Vinculos de aula com professor excluidas com sucesso.";
    }

    $sql = "DELETE FROM aulas WHERE id = '$id'";
    if ($conn->query($sql) === true) {
        echo "Aula excluida com sucesso.";
    }
    header("Location: index.php");
    exit();
} else if (isset($_POST['createProfessor'])) {
    $nome_professor = $_POST['nome_professor'];

    $sql = "INSERT INTO professores (nome) values('$nome_professor');";
    if ($conn->query($sql) === true) {
        echo "Professor adiocionado";
    }
    header("Location: index.php");
    exit();
} else if (isset($_POST['createAula'])) {
    $horario_aula = $_POST['horario_aula'];
    $sala = $_POST['sala'];

    $sql = "INSERT INTO aulas (horario_aula, sala) values('$horario_aula', '$sala');";
    if ($conn->query($sql) === true) {
        echo "Aula adicionada";
    }
    header("Location: index.php");
    exit();
} else if (isset($_POST['createProfessorAula'])) {
    $id_professor = $_POST['id_professor'];
    $id_aula = $_POST['id_aula'];

    $sql = "INSERT INTO professor_aula VALUES('$id_aula', '$id_professor');";
    if ($conn->query($sql) === true) {
        echo "Professor adicionado a aula.";
    }
    header("Location: index.php");
    exit();
}


$sql = "SELECT * FROM professores";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
            <th> ID </th>
            <th> Nome </th>
            <th> Acoes </th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                    <td> {$row['id']} </td>
                    <td> {$row['nome']} </td>
                    <td><a href='index.php?deleteProfessor={$row['id']}'>Deletar</a></td>
                </tr>";
    }
    echo "</table> <br>";
} else {
    echo "Nenhum professor encontrado.";
}

$sql = "select aulas.id, aulas.horario_aula, aulas.sala, GROUP_CONCAT(professores.nome ORDER BY professores.nome SEPARATOR ', ') AS professores from aulas left join professor_aula on id_aula = aulas.id left join professores on professor_aula.id_professor = professores.id group by aulas.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
            <th> ID </th>
            <th> Horario </th>
            <th> Sala </th>
            <th> Professores </th>
            <th> Acoes </th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                    <td> {$row['id']} </td>
                    <td> {$row['horario_aula']} </td>
                    <td> {$row['sala']} </td>
                    <td> {$row['professores']} </td>
                    <td><a href='index.php?deleteAula={$row['id']}'>Deletar</a></td>
                </tr>";
    }
    echo "</table> <br>";
} else {
    echo "Nenhum registro encontrado.";
}

?>

<html>

<body>

    <form method='POST'>
        Nome: <input type='text' name='nome_professor' require> <br>
        <input type="submit" name='createProfessor' value='Cadastrar professor'>
    </form>

    <form method='POST'>
        Horario: <input type='time' name='horario_aula' require> <br>
        Sala: <input type='text' name='sala' require> <br>
        <input type="submit" name='createAula' value='Cadastrar aula'>
    </form>

    <form method='POST'>
        ID Professor: <input type='number' name='id_professor' require> <br>
        ID Aula: <input type='number' name='id_aula' require> <br>
        <input type="submit" name='createProfessorAula' value='Cadastrar professor em uma aula'>
    </form>

</body>

</html>