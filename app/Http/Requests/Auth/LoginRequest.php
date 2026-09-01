<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determinar si el usuario puede realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [

            'correo' => [
                'required',
                'string',
                'email'
            ],

            'contrasena' => [
                'required',
                'string'
            ],

        ];
    }


    /**
     * Intentar autenticar al usuario.
     */
    public function authenticate(): void
    {

        $this->ensureIsNotRateLimited();


        if (! Auth::attempt([

            'correo' => $this->correo,

            'password' => $this->contrasena


        ], $this->boolean('remember'))) {


            RateLimiter::hit(
                $this->throttleKey()
            );


            throw ValidationException::withMessages([

                'correo' => 'Las credenciales son incorrectas.'

            ]);

        }


        RateLimiter::clear(
            $this->throttleKey()
        );


        Session::regenerate();

    }


    /**
     * Evitar demasiados intentos.
     */
    public function ensureIsNotRateLimited(): void
    {

        if (! RateLimiter::tooManyAttempts(
            $this->throttleKey(),
            5
        )) {

            return;

        }


        event(new Lockout($this));


        $seconds = RateLimiter::availableIn(
            $this->throttleKey()
        );


        throw ValidationException::withMessages([

            'correo' => "Demasiados intentos. Espera {$seconds} segundos."

        ]);

    }


    /**
     * Llave para limitar intentos.
     */
    public function throttleKey(): string
    {

        return Str::transliterate(

            Str::lower(
                $this->string('correo')
            )
            .'|'.
            $this->ip()

        );

    }
}