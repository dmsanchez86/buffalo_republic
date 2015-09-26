<?php

$name           = $_POST["name"];
$email          = $_POST["email"];
$phone          = $_POST["phone"];
$observations   = $_POST["observations"];
$date           = $_POST["date"];
$time           = $_POST["time"];
$people         = $_POST["people"];

// El mensaje
$mensaje = "Solicitud Reserva Buffalo Republic\r\n\n<h3><b>Datos de la Reserva</b></h3>\r\n";
$mensaje .= "<b>Nombre: </b>" . $name;
$mensaje .= "<b>Correo Electronico: </b>" . $email;
$mensaje .= "<b>Teléfono: </b>" . $phone;
$mensaje .= "<b>Observaciones: </b>" . $observations;
$mensaje .= "<br><b>Fecha de la reservación: </b>" . $date;
$mensaje .= "<br><b>Hora de la reservación: </b>" . $time;
$mensaje .= "<br><b>Cantidad de personas: </b>" . $people;

$headers   = "Content-Type: text/html; charset=ISO-8859-1\r\n";//PA QUE RECONOZCA HTML
// Enviarlo
$status = mail('dmsanchez86@misena.edu.co', 'Reservación ', $mensaje, $headers);

echo $status;