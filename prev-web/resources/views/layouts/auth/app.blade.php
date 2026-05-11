<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.header')
</head>
<body >

<div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        @include('includes.preloader')
    </div>

    <!-- Content -->
    <div class="d-flex justify-content-center">

        <section class="content">
            {{$slot}}
        </section>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweet-alert.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

<script>
(function () {

    let serverOnline = true;

    async function pingServer() {
        try {
            await fetch('/check/server/status', { cache: 'no-store' });
            serverOnline = true;
        } catch {
            serverOnline = false;
        }
    }


    setInterval(pingServer, 4000); // Atualiza estado do servidor
    pingServer();

    function showAlert() {
        Swal.fire({
            icon: 'error',
            title: 'Servidor indisponível',
            text: 'Não foi possível comunicar com o servidor, contacte o administrador do sistema.',
            confirmButtonText: 'Fechar'
        });
    }

    document.addEventListener('submit', function () {   //  MOSTRA ALERTA EM TODO SUBMIT
        if (!serverOnline) {
            showAlert();
        }
    }, true);

    document.addEventListener('click', function (e) {   // MOSTRA ALERTA EM TODO CLICK (wire:click, botões, links)

        const el = e.target.closest('button, a');
        if (!el) return;

        if (!serverOnline) {
            showAlert();
        }
    }, true);

})();
</script>

<livewire:scripts />

</body>
</html>
