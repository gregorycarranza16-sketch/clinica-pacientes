<?php
// Conexión a la base de datos en XAMPP (Windows)
$conexion = new mysqli("192.168.56.101", "remoto", "12345", "clinica");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";

// Guardar nuevo paciente
if (isset($_POST['guardar'])) {
    $cedula   = trim($_POST['cedula']);
    $nombre   = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);

    if (!empty($cedula) && !empty($nombre) && !empty($apellido)) {
        $stmt = $conexion->prepare("INSERT INTO paciente (cedula, nombre, apellido) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $cedula, $nombre, $apellido);
        if ($stmt->execute()) {
            $mensaje = "¡Paciente guardado exitosamente!";
        } else {
            $mensaje = "Error al guardar: " . $conexion->error;
        }
    }
}

// Consultar la lista de pacientes
$resultado = $conexion->query("SELECT * FROM paciente");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clinica123 - Práctica</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        .box { background: white; padding: 20px; border-radius: 8px; width: 450px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 0 auto; }
        label { font-weight: bold; }
        input[type="text"] { width: 95%; padding: 8px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        .msg { color: #0056b3; font-weight: bold; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; background: white;}
        th { background: #0056b3; color: white; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Gestión de Pacientes</h2>
        <?php if ($mensaje): ?>
            <p class="msg"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Cédula:</label>
            <input type="text" name="cedula" required>

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" required>

            <button type="submit" name="guardar">GUARDAR</button>
        </form>
    </div>

    <br>
    <div class="box" style="width: 600px;">
        <h3>Lista de Pacientes en Windows</h3>
        <table>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
            </tr>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>