<?php
header('Content-Type: application/json');

// Configuración básica
$destinatario = "tuemail@restaurantecampestre.com";
$asunto = "Nueva Reserva desde la Web";

// Procesar datos del formulario
$datos = [
    'nombre' => filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING),
    'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
    'telefono' => filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING),
    'fecha' => filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING),
    'hora' => filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING),
    'personas' => filter_input(INPUT_POST, 'personas', FILTER_SANITIZE_NUMBER_INT),
    'servicio' => filter_input(INPUT_POST, 'servicio', FILTER_SANITIZE_STRING),
    'mensaje' => filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING)
];

// Validar datos requeridos
if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['fecha']) || empty($datos['hora']) || empty($datos['personas'])) {
    echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos requeridos.']);
    exit;
}

if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
    exit;
}

// Construir el mensaje del correo
$mensajeCorreo = "Nueva reserva recibida:\n\n";
$mensajeCorreo .= "Nombre: " . $datos['nombre'] . "\n";
$mensajeCorreo .= "Email: " . $datos['email'] . "\n";
$mensajeCorreo .= "Teléfono: " . ($datos['telefono'] ?: 'No proporcionado') . "\n";
$mensajeCorreo .= "Fecha: " . $datos['fecha'] . "\n";
$mensajeCorreo .= "Hora: " . $datos['hora'] . "\n";
$mensajeCorreo .= "Número de personas: " . $datos['personas'] . "\n";
$mensajeCorreo .= "Servicio: " . $datos['servicio'] . "\n";
$mensajeCorreo .= "Mensaje adicional:\n" . ($datos['mensaje'] ?: 'Ninguno') . "\n";

// Cabeceras del correo
$cabeceras = "From: " . $datos['email'] . "\r\n" .
             "Reply-To: " . $datos['email'] . "\r\n" .
             "X-Mailer: PHP/" . phpversion();

// Enviar el correo
$enviado = mail($destinatario, $asunto, $mensajeCorreo, $cabeceras);

// Guardar en base de datos (ejemplo básico)
// En una implementación real, usarías MySQLi o PDO
$archivoDB = 'reservas.txt';
$linea = implode('|', $datos) . "\n";
file_put_contents($archivoDB, $linea, FILE_APPEND);

// Respuesta JSON
if ($enviado) {
    echo json_encode(['success' => true, 'message' => '¡Reserva enviada con éxito! Nos pondremos en contacto contigo pronto.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Hubo un error al enviar la reserva. Por favor inténtalo de nuevo más tarde.']);
}
?>