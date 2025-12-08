<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Activación de cuenta FertiGyn</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7f7fb; padding:20px;">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background:#ffffff; border-radius:16px; padding:24px;">
                <tr>
                    <td align="center" style="padding-bottom:16px;">
                        <div style="font-size:24px; font-weight:bold; color:#d83d71;">
                            FertiGyn
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:16px; color:#333;">
                        <p>Hola <strong>{{ $user->name }}</strong>,</p>

                        <p>Se ha creado una cuenta para ti en el sistema <strong>FertiGyn</strong>.</p>

                        <p>
                            <strong>Correo:</strong> {{ $user->email }}<br>
                            <strong>Contraseña temporal:</strong> {{ $plainPassword }}
                        </p>

                        <p>
                            Antes de usar tu cuenta, por favor haz clic en el siguiente botón para
                            <strong>confirmar tu correo y activar la cuenta</strong>:
                        </p>

                        <p style="text-align:center; margin:24px 0;">
                            <a href="{{ $activationUrl }}"
                               style="background:#d83d71; color:#ffffff; padding:12px 24px;
                                      text-decoration:none; border-radius:999px; font-weight:bold;">
                                Activar mi cuenta
                            </a>
                        </p>

                        <p>Después de activarla, te recomendamos cambiar la contraseña al iniciar sesión.</p>

                        <p style="font-size:12px; color:#777;">
                            Si tú no solicitaste esta cuenta, puedes ignorar este mensaje.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
