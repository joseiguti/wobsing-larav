<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado y tiene habilitado Two Factor Authentication
        if (Auth::check() && Auth::user()->hasTwoFactorEnabled()) {
            // Verificar si ha pasado más de 30 minutos desde la última verificación de Two Factor
            $lastVerificationTime = Auth::user()->lastTwoFactorVerificationTime();
            $expirationTime = now()->subMinutes(30);

            if ($lastVerificationTime < $expirationTime) {
                // Generar y almacenar el código de autenticación
                $code = $this->generateCode();
                Auth::user()->storeTwoFactorCode($code);

                // Enviar el código de autenticación por mensaje de texto utilizando Twilio
                $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
                $twilio->messages->create(
                    Auth::user()->phoneNumber, // Número de teléfono del usuario
                    [
                        'from' => config('services.twilio.phone_number'), // Número de teléfono de Twilio
                        'body' => 'Tu código de autenticación: ' . $code,
                    ]
                );

                // Si la autenticación por Two Factor es exitosa, actualizar el tiempo de última verificación
                Auth::user()->updateLastTwoFactorVerificationTime(now());
            } else {
                // Redireccionar a una página de sesión expirada
                return redirect('/sesion-expirada');
            }
        }

        return $next($request);
    }

    private function generateCode()
    {
        // Generar un código de autenticación de 6 dígitos
        return mt_rand(100000, 999999);
    }
}
