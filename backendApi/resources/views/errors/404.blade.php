@extends('layouts.errors.app')
@section('title', 'Página não encontrada')
    <div class="min-h-screen px-4 py-16 flex flex-col items-center justify-center text-center sm:px-6 lg:px-8">
        <!-- Conteúdo principal -->
        <div class="max-w-2xl mx-auto">
            <!-- Número 404 grande e estilizado -->
            <h1 class="text-9xl font-extrabold text-gray-200 tracking-tight sm:text-[12rem] md:text-[14rem]">
                404
            </h1>

            <!-- Mensagem principal -->
            <div class="mt-[-4rem] relative">
                <h2 class="text-4xl font-bold text-gray-900 sm:text-5xl">
                    Oops! Página não encontrada
                </h2>

                <p class="mt-6 text-lg text-gray-600 max-w-xl mx-auto">
                    Parece que o recurso que estás a procurar não existe ou foi movido.
                    <br class="hidden sm:inline">
                    Verifica se o endereço está correto ou tenta outra coisa.
                </p>
            </div>

            <!-- Botões de ação -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <!-- Botão Voltar atrás -->
                <button
                    onclick="history.back()"
                    class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 shadow-lg hover:shadow-xl"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar à página anterior
                </button>

                <!-- Botão Página Inicial -->
                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-8 py-4 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ir para a Página Inicial
                </a>
            </div>

        </div>
    </div>
