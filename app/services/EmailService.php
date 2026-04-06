<?php

require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';
require_once __DIR__ . '/../../config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private function mailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USER;
        $mail->Password   = MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom(MAIL_USER, MAIL_FROM_NAME);
        return $mail;
    }

    /** Envía el correo de confirmación de suscripción. */
    public function enviarConfirmacion(string $email, string $token): void
    {
        $url  = APP_URL . '/index.php?action=confirmar&token=' . urlencode($token);
        $mail = $this->mailer();
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu suscripción — Tipo de Cambio BCCR';
        $mail->Body    = $this->tplConfirmacion($email, $url);
        $mail->AltBody = "Confirma tu suscripción entrando a: $url";
        $mail->send();
    }

    /** Envía el tipo de cambio del día. */
    public function enviarTipoDia(string $email, string $token, array $tasa): void
    {
        $unsub = APP_URL . '/index.php?action=desuscribir&token=' . urlencode($token);
        $mail  = $this->mailer();
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = '₡ Tipo de Cambio · ' . date('d/m/Y') . ' · Venta ₡' . number_format((float)$tasa['venta'], 2);
        $mail->Body    = $this->tplTipoDia($tasa, $unsub);
        $mail->AltBody = "Tipo de Cambio BCCR — " . date('d/m/Y') . "\nCompra: ₡" . number_format((float)$tasa['compra'], 2) . "\nVenta: ₡" . number_format((float)$tasa['venta'], 2) . "\n\nCancelar suscripción: $unsub";
        $mail->send();
    }

    /* ── Plantillas HTML (compatibles con Outlook, Gmail, Apple Mail) ────── */

    private function tplConfirmacion(string $email, string $url): string
    {
        $emailEsc = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $urlEsc   = htmlspecialchars($url,   ENT_QUOTES, 'UTF-8');

        return '<!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f4f6ff;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" style="padding:40px 16px;background-color:#f4f6ff;">

  <!-- Contenedor principal -->
  <table role="presentation" width="580" cellpadding="0" cellspacing="0" border="0" style="max-width:580px;width:100%;">

    <!-- CABECERA — bgcolor como fallback para Outlook (no soporta gradients) -->
    <tr>
      <td align="center" bgcolor="#4f46e5" style="background-color:#4f46e5;background-image:linear-gradient(135deg,#312e81,#4f46e5,#7c3aed);padding:32px 40px;border-radius:12px 12px 0 0;">
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:800;color:#ffffff;margin:0;line-height:1.3;">Tipo de Cambio &middot; BCCR</p>
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#c7d2fe;margin:8px 0 0;line-height:1.4;">Costa Rica</p>
      </td>
    </tr>

    <!-- CUERPO -->
    <tr>
      <td bgcolor="#ffffff" style="background-color:#ffffff;padding:40px;border-left:1px solid #e2e8ff;border-right:1px solid #e2e8ff;">
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:700;color:#1e1b4b;margin:0 0 12px;line-height:1.3;">Confirma tu suscripci&oacute;n</p>
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#6b7280;margin:0 0 32px;line-height:1.7;">
          Hola, recibimos una solicitud para suscribir <strong style="color:#1e1b4b;">' . $emailEsc . '</strong>
          al bolet&iacute;n diario de tipo de cambio del d&oacute;lar.
          Haz clic en el bot&oacute;n para confirmar.
        </p>

        <!-- BOTÓN — VML para Outlook, <a> para el resto -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
            <!--[if mso]>
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
              href="' . $urlEsc . '"
              style="height:50px;v-text-anchor:middle;width:240px;"
              arcsize="10%"
              strokecolor="#4f46e5"
              fillcolor="#4f46e5">
              <w:anchorlock/>
              <center style="font-family:Arial,sans-serif;font-size:15px;font-weight:bold;color:#ffffff;">
                Confirmar suscripci&#243;n
              </center>
            </v:roundrect>
            <![endif]-->
            <!--[if !mso]><!-->
            <a href="' . $urlEsc . '"
               style="background-color:#4f46e5;border-radius:10px;color:#ffffff;display:inline-block;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;line-height:50px;text-align:center;text-decoration:none;width:240px;-webkit-text-size-adjust:none;mso-hide:all;">
              Confirmar suscripci&oacute;n
            </a>
            <!--<![endif]-->
          </td>
        </tr>
        </table>

        <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9ca3af;margin:28px 0 0;line-height:1.5;">
          Si no solicitaste esto, ignora este mensaje. El enlace es de un solo uso.
        </p>
        <!-- Enlace de texto como respaldo por si el botón falla -->
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9ca3af;margin:8px 0 0;line-height:1.5;word-break:break-all;">
          O copia este enlace en tu navegador:<br>
          <a href="' . $urlEsc . '" style="color:#4f46e5;">' . $urlEsc . '</a>
        </p>
      </td>
    </tr>

    <!-- PIE -->
    <tr>
      <td bgcolor="#f8f9ff" style="background-color:#f8f9ff;padding:20px 40px;border:1px solid #e2e8ff;border-top:none;border-radius:0 0 12px 12px;text-align:center;">
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9ca3af;margin:0;line-height:1.5;">
          Datos oficiales del <strong style="color:#6b7280;">Banco Central de Costa Rica (BCCR)</strong>
        </p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>';
    }

    private function tplTipoDia(array $tasa, string $urlUnsub): string
    {
        $fecha    = date('d \d\e F \d\e Y', strtotime($tasa['fecha']));
        $compra   = number_format((float)$tasa['compra'], 2);
        $venta    = number_format((float)$tasa['venta'],  2);
        $unsubEsc = htmlspecialchars($urlUnsub, ENT_QUOTES, 'UTF-8');

        return '<!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f4f6ff;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" style="padding:40px 16px;background-color:#f4f6ff;">

  <table role="presentation" width="580" cellpadding="0" cellspacing="0" border="0" style="max-width:580px;width:100%;">

    <!-- CABECERA -->
    <tr>
      <td align="center" bgcolor="#4f46e5" style="background-color:#4f46e5;background-image:linear-gradient(135deg,#312e81,#4f46e5,#7c3aed);padding:28px 40px;border-radius:12px 12px 0 0;">
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:800;color:#ffffff;margin:0;line-height:1.3;">Tipo de Cambio &middot; BCCR</p>
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#c7d2fe;margin:8px 0 0;line-height:1.4;">' . $fecha . '</p>
      </td>
    </tr>

    <!-- TASAS -->
    <tr>
      <td bgcolor="#ffffff" style="background-color:#ffffff;padding:36px 40px;border-left:1px solid #e2e8ff;border-right:1px solid #e2e8ff;">

        <p style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:1.2px;margin:0 0 20px;line-height:1;">TASAS DEL D&Iacute;A</p>

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <!-- Compra -->
          <td width="48%" bgcolor="#d1fae5" style="background-color:#d1fae5;padding:22px;text-align:center;border-radius:10px;">
            <p style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;color:#065f46;text-transform:uppercase;letter-spacing:1px;margin:0;line-height:1;">&#9660; COMPRA</p>
            <p style="font-family:Arial,Helvetica,sans-serif;font-size:34px;font-weight:800;color:#059669;margin:10px 0 0;line-height:1;">&#8353;&nbsp;' . $compra . '</p>
          </td>
          <td width="4%">&nbsp;</td>
          <!-- Venta -->
          <td width="48%" bgcolor="#ffe4e6" style="background-color:#ffe4e6;padding:22px;text-align:center;border-radius:10px;">
            <p style="font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;color:#9f1239;text-transform:uppercase;letter-spacing:1px;margin:0;line-height:1;">&#9650; VENTA</p>
            <p style="font-family:Arial,Helvetica,sans-serif;font-size:34px;font-weight:800;color:#e11d48;margin:10px 0 0;line-height:1;">&#8353;&nbsp;' . $venta . '</p>
          </td>
        </tr>
        </table>

        <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9ca3af;margin:24px 0 0;text-align:center;line-height:1.5;">
          Datos publicados por el Banco Central de Costa Rica
        </p>
      </td>
    </tr>

    <!-- PIE -->
    <tr>
      <td bgcolor="#f8f9ff" style="background-color:#f8f9ff;padding:20px 40px;border:1px solid #e2e8ff;border-top:none;border-radius:0 0 12px 12px;text-align:center;">
        <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9ca3af;margin:0;line-height:1.6;">
          Recibes este correo porque te suscribiste al bolet&iacute;n diario.<br>
          <a href="' . $unsubEsc . '" style="color:#6b7280;text-decoration:underline;">Cancelar suscripci&oacute;n</a>
        </p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>';
    }
}
