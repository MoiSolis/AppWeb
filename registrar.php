<?php
require 'BD.php'; // Incluye el archivo de conexión a la base de datos



// Función para registrar un cliente
function registrarCliente($pdo, $cedula, $nombre, $direccion, $telefono) {
    $sql = "INSERT INTO clientes (cedula, nombre, direccion, telefono) VALUES (:cedula, :nombre, :direccion, :telefono)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->execute();
        return $pdo->lastInsertId(); // Devuelve el ID del cliente recién insertado
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para registrar una lectura
function registrarLectura($pdo, $cliente_id, $lectura, $fecha) {
    $sql = "INSERT INTO lecturas (cliente_id, lectura, fecha) VALUES (:cliente_id, :lectura, :fecha)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':lectura', $lectura);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        echo "Lectura registrada exitosamente.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del cliente
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Datos de la lectura
    $lectura = $_POST['lectura'];
    $fecha = $_POST['fecha'];

    if (!empty($cedula) && !empty($nombre) && !empty($direccion) && !empty($telefono)) {
        $cliente_id = registrarCliente($pdo, $cedula, $nombre, $direccion, $telefono);
        if ($cliente_id) {
            if (!empty($lectura) && !empty($fecha)) {
                registrarLectura($pdo, $cliente_id, $lectura, $fecha);
            } else {
                echo "Todos los campos de lectura son obligatorios.";
            }
        }
    } else {
        echo "Todos los campos del cliente son obligatorios.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente y Lectura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <form action="registrar.php" method="post">
        <!-- Datos del Cliente -->
        <h2>Registrar Cliente</h2>
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required><br>

        <!-- Datos de la Lectura -->
        <h2>Registrar Lectura</h2>
        <label for="lectura">Lectura:</label>
        <input type="text" id="lectura" name="lectura" required><br>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br>

        <input type="submit" value="Registrar">
    </form>
</body>
</html>

