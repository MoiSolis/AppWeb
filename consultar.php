<?php
require 'BD.php'; // Incluye el archivo de conexión a la base de datos

// Función para obtener el consumo total por cédula
function obtenerConsumoPorCedula($pdo, $cedula) {
    $sql = "SELECT SUM(l.lectura) AS consumo_total 
            FROM clientes c 
            JOIN lecturas l ON c.id = l.cliente_id 
            WHERE c.cedula = :cedula";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['consumo_total'] ?? 0; // Retorna 0 si no hay resultados
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para obtener el consumo total del medidor
function obtenerConsumoTotalMedidor($pdo) {
    $sql = "SELECT SUM(lectura) AS consumo_total FROM lecturas";
    try {
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['consumo_total'] ?? 0; // Retorna 0 si no hay resultados
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Manejo de formulario
$consumoCedula = null;
$consumoMedidor = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cedula'])) {
        $cedula = $_POST['cedula'];
        $consumoCedula = obtenerConsumoPorCedula($pdo, $cedula);
    }
    if (isset($_POST['consultar'])) {
        $consumoMedidor = obtenerConsumoTotalMedidor($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Consumo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h2>Consulta de Consumo por Cédula</h2>
    <form action="consultar.php" method="post">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required><br>
        <input type="submit" value="Consultar">
    </form>
    <?php
    if ($consumoCedula !== null) {
        echo "<p>Consumo total para la cédula $cedula: $consumoCedula</p>";
    }
    ?>

    <h2>Consulta de Consumo Total del Medidor</h2>
    <form action="consulta_consumo.php" method="post">
        <input type="hidden" name="consultar" value="1">
        <input type="submit" value="Consultar">
    </form>
    <?php
    if ($consumoMedidor !== null) {
        echo "<p>Consumo total del medidor: $consumoMedidor</p>";
    }
    ?>
</body>
</html>
