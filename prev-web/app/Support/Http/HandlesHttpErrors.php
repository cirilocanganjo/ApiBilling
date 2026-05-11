<?php

namespace App\Support\Http;

use Illuminate\Http\Client\Response;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


trait HandlesHttpErrors
{
    protected function handleHttpError(Response $response): void
    {
        $status = $response->status();
        match ($status) {
            401 => $this->errorAlert('Sessão expirada. Faça o login novamente.'),
            403 => $this->errorAlert('Acesso não autorizado, contacte o administrador do sistema.'),
            404 => $this->errorAlert('Recurso não encontrado, contacte o administrador do sistema.'),
            422 => null, // tratado nos componentes Livewire
            default => $this->errorAlert('Erro inesperado ao processar a solicitação, contacte o administrador do sistema.'),
        };
    }

    protected function errorAlert(string $message): void
    {
        LivewireAlert::title('Erro')
            ->text($message)
            ->error()
            ->withConfirmButton()
            ->confirmButtonText('Fechar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->show();

    }

    protected function successAlert(string $message): void
    {
        LivewireAlert::title('Sucesso')
            ->text($message)
            ->success()
            ->withConfirmButton()
            ->confirmButtonText('Fechar')
            ->timer(0)
            ->show();
    }
}
