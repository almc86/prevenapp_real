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
                            <h1 style="font-size:22px; color:#111827; margin:0 0 12px;">¡Hola, {{ $nombre }}! 👋</h1>
                            <p style="font-size:15px; color:#374151; line-height:1.6; margin:0 0 16px;">
                                Tu cuenta de <strong>{{ $empresa }}</strong> en PrevenApp ya está creada y lista para usar.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; margin:8px 0 24px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <div style="font-size:13px; color:#64748b;">Tu plan</div>
                                        <div style="font-size:16px; font-weight:700; color:#111827;">{{ $planNombre }}</div>
                                        <div style="font-size:13px; color:#64748b; margin-top:6px;">
                                            @if ($esGratis)
                                                Plan gratis — sin vencimiento.
                                            @else
                                                Incluye 14 días de prueba gratis. Al final decides si continúas.
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $loginUrl }}" style="display:inline-block; background:#1e40af; color:#ffffff; text-decoration:none; font-weight:600; font-size:15px; padding:14px 28px; border-radius:10px;">
                                            Iniciar sesión
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:24px 36px 32px; border-top:1px solid #f1f5f9;">
                            <p style="font-size:12px; color:#94a3b8; line-height:1.6; margin:0;">
                                Si no creaste esta cuenta, ignora este correo.<br>
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
