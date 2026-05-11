
<!-- Toast Reutilizável AdminLTE 3 -->
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    // Configurações padrão do Toastr (AdminLTE Style)
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "2000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Mensagens de Sessão
    @if(session('success'))
        toastr.success("{{ session('success') }}", "Sucesso!");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}", "Erro!");
    @endif

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}", "Aviso!");
    @endif

    @if(session('info'))
        toastr.info("{{ session('info') }}", "Informação!");
    @endif

});
</script>
