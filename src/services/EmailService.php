<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.mailtrap.io'; 
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'TU_USER';
        $this->mailer->Password = 'TU_PASS';
        $this->mailer->Port = 2525;

        $this->mailer->setFrom('no-reply@restaurante.com', 'Restaurante');
        $this->mailer->isHTML(true);
    }

    public function enviar($destinatario, $asunto, $mensajeHtml)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($destinatario);

            $this->mailer->Subject = $asunto;
            $this->mailer->Body    = $mensajeHtml;

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
            return false;
        }
    }

    public function enviarPlatoCreado($nombrePlato, $precio)
    {
        $asunto = "Nuevo plato creado: $nombrePlato";
        $mensaje = "
            <h2>Nuevo plato añadido</h2>
            <p><strong>Nombre:</strong> $nombrePlato</p>
            <p><strong>Precio:</strong> $precio €</p>
        ";

        return $this->enviar("admin@pruebas.com", $asunto, $mensaje);
    }
}
