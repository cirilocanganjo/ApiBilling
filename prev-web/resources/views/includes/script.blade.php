<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweet-alert.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- overlayScrollbars -->
<!-- <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script> -->

<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<!-- DataPicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

<!-- Outros plugins -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
@stack('scripts')
<!-- Inicialização do DataTables -->
<script>
    $(document).ready(function() {
        $('#numberlineshow').on('change', function(){
          $('#lengthForm').submit();
        });

    $('.datatable').each(function() {
        var columnDefs = [];

        $(this).find('thead th').each(function(index) {
            if ($(this).hasClass('no-sort')) {
                columnDefs.push({ orderable: false, targets: index });
            }
        });

        $(this).DataTable({
            responsive: true,
            paging: false,      // desativa paginação do front-end
            lengthChange: false,     // permite mudar quantidade de registros
            searching: false,
            autoWidth: false,
            info: false,        // Desliga texto automático do DataTables. Ex: Mostrando de 11 até 15 de 15 registros
            pagingType: "simple_numbers",
            order: [[0, 'asc']],
            columnDefs: columnDefs,
            language: {
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                paginate: { previous: "Anterior", next: "Próximo" },
                zeroRecords: "Nenhum registro encontrado"
            },
            initComplete: function () {
                $('.dataTables_length select')
                    .removeClass()
                    .addClass('form-control form-control-sm');

                       // Pega o select após a inicialização do DataTable
                   /*  var lengthSelect = $('#categoriasTable_length select');

                    // Intercepta mudança
                    lengthSelect.on('change', function() {
                        var perPage = $(this).val();
                        var currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('numberlineshow', perPage);
                        window.location.href = currentUrl.toString();
                    });

                    // Define valor inicial conforme GET
                    lengthSelect.val('{{ request("numberlineshow", 10) }}'); */

                    //Começa aqui

                   // Cria um form invisível para enviar numberlineshow
                    /* if($('#lengthForm').length === 0){
                        $('#categoriasTable').before(`
                            <form id="lengthForm" method="GET" style="display:none;">
                                <input type="hidden" name="numberlineshow" value="{{ request('numberlineshow', 10) }}">
                                <input type="hidden" name="q" value="{{ request('q') }}">
                            </form>
                        `);
                    }
                    // Pega o select de quantidade do DataTable
                    var lengthSelect = $('#categoriasTable_length select');
                     // Define valor inicial do select conforme GET
                    lengthSelect.val('{{ request("numberlineshow", 10) }}');
                    // Intercepta mudança do select
                    lengthSelect.on('change', function() {
                        var val = $(this).val();
                        // Atualiza o input do form invisível
                        $('#lengthForm input[name="numberlineshow"]').val(val);
                        $('#lengthForm').submit(); // envia o GET
                    }); */


            },
            drawCallback: function(settings) {
                var pages = this.api().page.info().pages;
                if (pages <= 1) $('.dataTables_paginate').hide();
                else $('.dataTables_paginate').show();
            }
        });
    });


    $('#reservation').daterangepicker({
        autoUpdateInput: false,  // permite limpar o campo
        locale: {
            format: 'DD-MM-YYYY', // formato brasileiro
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Limpar',
            fromLabel: 'De',
            toLabel: 'Até',
            customRangeLabel: 'Personalizado',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            firstDay: 1 // segunda-feira como primeiro dia da semana
        }
    });

    // Quando aplica o range
    $('#reservation').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
    });

    // Quando cancela/limpa
    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });


});
/* $(document).ready(function() {
    $('.datatable').DataTable({
        responsive: true,
        searching: false,
        autoWidth: false,
        pagingType: "simple_numbers",   // ESTILO NUMÉRICO
        order: [[0, 'asc']],        // Ordena pelo ID por padrão
        columnDefs: [
            { orderable: false, targets: 3 } // Coluna Ações não ordena
        ],
        language: {
            lengthMenu: "Mostrar _MENU_ registros", //Mostrar _MENU_ registros por página
            info: "A Mostrar de _START_ até _END_ de _TOTAL_ registros", //Mostrando _START_ a _END_ de _TOTAL_ registros
            paginate: {
                previous: "Anterior",
                next: "Próximo"
            },
            zeroRecords: "Nenhum registro encontrado"
        },
        // Personalização do select
        initComplete: function () {
            // Ajusta a classe do SELECT do DataTables no AdminLTE
            $('.dataTables_length select')
                .removeClass() // remove classes antigas
                .addClass('form-control form-control-sm'); // adiciona somente as corretas
                // Função para atualizar o cursor
        },
        drawCallback: function(settings) {
            var pages = this.api().page.info().pages;

            if (pages <= 1) {
                $('.dataTables_paginate').hide();
            } else {
                $('.dataTables_paginate').show();
            }
        }

    });


}); */
</script>

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


    setInterval(pingServer, 4000);  // Atualiza estado do servidor
    pingServer();

    function showAlert() {
        Swal.fire({
            icon: 'error',
            title: 'Servidor indisponível',
            text: 'Não foi possível comunicar com o servidor, contacte o administrador do sistema.',
            confirmButtonText: 'Fechar'
        });
    }


    document.addEventListener('submit', function () {  // MOSTRA ALERTA EM TODO SUBMIT
        if (!serverOnline) {
            showAlert();
        }
    }, true);


    document.addEventListener('click', function (e) { //  MOSTRA ALERTA EM TODO CLICK (wire:click, botões, links)
        const el = e.target.closest('button, a');
        if (!el) return;

        if (!serverOnline) {
            showAlert();
        }
    }, true);

})();
</script>


@stack('scripts')
