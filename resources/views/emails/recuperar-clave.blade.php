<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#1e40af,#3b82f6); padding:32px; text-align:center;">
                            <div style="font-size:24px; font-weight:800; color:#ffffff;">PrevenApp</div>
                            <div style="font-size:13px; color:#dbeafe; margin-top:4px;">Prevención de Riesgos Laborales</div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:36px 36px 16px;">
                            <h1 style="font-size:22px; color:#111827; margin:0 0 12px;">Recupera tu contraseña</h1>
                            <p style="font-size:15px; color:#374151; line-height:1.6; margin:0 0 16px;">
                                Hola{{ $nombre ? ', '.$nombre : '' }}. Recibimos una solicitud para restablecer la contraseña de tu cuenta en PrevenApp.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:8px 0 20px;">
                                        <a href="{{ $resetUrl }}" style="display:inline-block; background:#1e40af; color:#ffffff; text-decoration:none; font-weight:600; font-size:15px; padding:14px 28px; border-radius:10px;">
                                            Restablecer contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#64748b; line-height:1.6; margin:0 0 8px;">
                                Este enlace vence en <strong>{{ $expiraMinutos }} minutos</strong>.
                            </p>
                            <p style="font-size:13px; color:#64748b; line-height:1.6; margin:0 0 16px;">
                                Si no solicitaste este cambio, ignora este correo: tu contraseña no cambiará.
                            </p>

                            <p style="font-size:12px; color:#94a3b8; line-height:1.6; margin:16px 0 0; word-break:break-all;">
                                ¿No funciona el botón? Copia y pega este enlace en tu navegador:<br>
                                <a href="{{ $resetUrl }}" style="color:#3b82f6;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:24px 36px 32px; border-top:1px solid #f1f5f9;">
                            <p style="font-size:12px; color:#94a3b8; line-height:1.6; margin:0;">
                                &copy; {{ date('Y') }} PrevenApp. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
