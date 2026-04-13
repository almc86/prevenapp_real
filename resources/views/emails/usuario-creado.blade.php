<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a PrevenApp</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f0f4f8; padding: 40px 20px;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%;">

          {{-- Header con gradiente --}}
          <tr>
            <td style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #0ea5e9 100%); border-radius: 16px 16px 0 0; padding: 40px 40px 30px; text-align: center;">
              <div style="width: 64px; height: 64px; background-color: rgba(255,255,255,0.2); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <img src="https://img.icons8.com/fluency/48/shield.png" alt="PrevenApp" width="36" height="36" style="display: block;">
              </div>
              <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 0 0 8px; letter-spacing: -0.5px;">
                PrevenApp
              </h1>
              <p style="color: rgba(255,255,255,0.85); font-size: 14px; margin: 0;">
                Sistema de Prevención de Riesgos
              </p>
            </td>
          </tr>

          {{-- Contenido principal --}}
          <tr>
            <td style="background-color: #ffffff; padding: 40px;">

              {{-- Saludo --}}
              <h2 style="color: #1e293b; font-size: 22px; font-weight: 700; margin: 0 0 8px;">
                Hola, {{ $nombre }}
              </h2>
              <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin: 0 0 28px;">
                Tu cuenta ha sido creada exitosamente en PrevenApp. A continuación encontrarás tus credenciales de acceso al sistema.
              </p>

              {{-- Card de credenciales --}}
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 28px;">
                <tr>
                  <td style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #bae6fd; border-radius: 12px; padding: 24px;">
                    <p style="color: #0369a1; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 16px;">
                      Credenciales de acceso
                    </p>

                    {{-- Email --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 12px;">
                      <tr>
                        <td width="110" style="color: #64748b; font-size: 13px; font-weight: 500; padding: 8px 0;">
                          Correo:
                        </td>
                        <td style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 14px; font-weight: 600; color: #1e293b; font-family: monospace;">
                          {{ $email }}
                        </td>
                      </tr>
                    </table>

                    {{-- Password --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 12px;">
                      <tr>
                        <td width="110" style="color: #64748b; font-size: 13px; font-weight: 500; padding: 8px 0;">
                          Contraseña:
                        </td>
                        <td style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 14px; font-weight: 600; color: #1e293b; font-family: monospace;">
                          {{ $password }}
                        </td>
                      </tr>
                    </table>

                    {{-- Rol --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="110" style="color: #64748b; font-size: 13px; font-weight: 500; padding: 8px 0;">
                          Rol asignado:
                        </td>
                        <td style="padding: 8px 0;">
                          <span style="display: inline-block; background-color: #dbeafe; color: #1e40af; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                            {{ ucfirst($rol) }}
                          </span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              {{-- Boton --}}
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 28px;">
                <tr>
                  <td align="center">
                    <a href="{{ $loginUrl }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #1e40af, #3b82f6); color: #ffffff; font-size: 15px; font-weight: 600; text-decoration: none; padding: 14px 36px; border-radius: 10px; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);">
                      Iniciar sesión
                    </a>
                  </td>
                </tr>
              </table>

              {{-- Aviso de seguridad --}}
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td style="background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 16px 20px;">
                    <p style="color: #92400e; font-size: 13px; font-weight: 600; margin: 0 0 4px;">
                      Importante
                    </p>
                    <p style="color: #a16207; font-size: 13px; line-height: 1.5; margin: 0;">
                      Te recomendamos cambiar tu contraseña después del primer inicio de sesión. No compartas estas credenciales con nadie.
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- Footer --}}
          <tr>
            <td style="background-color: #1e293b; border-radius: 0 0 16px 16px; padding: 28px 40px; text-align: center;">
              <p style="color: rgba(255,255,255,0.6); font-size: 13px; line-height: 1.5; margin: 0 0 8px;">
                Este correo fue enviado automáticamente por PrevenApp.<br>
                Por favor no respondas a este mensaje.
              </p>
              <p style="color: rgba(255,255,255,0.35); font-size: 12px; margin: 0;">
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
