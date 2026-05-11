<style>
    /* Page base */
    body { background: #f6f8fa; font-family: "Segoe UI", Roboto, Arial, sans-serif; color:#222; }
    .page-card { background:#fff; border:1px solid #e3edf6; border-radius:4px; padding:18px; box-shadow:0 0 0 rgba(0,0,0,0); }
    .filters { background:#f7fbfd; border:1px solid #e6f0f7; border-radius:4px; padding:14px; }
    .breadcrumb-line { color:#5b9bd5; font-size:14px; margin-bottom:12px; }
    .form-control, .custom-select { min-height:40px; }
    .row-gap { margin-bottom:12px; }

    /* header action buttons */
    .top-actions { display:flex; gap:8px; }

    /* dropdown multi select button styling */
    .multiselect-native-select .btn { min-width:220px; text-align:left; }

    /* table area */
    .table-wrap { margin-top:16px; background:#fff; border:1px solid #eaeff4; border-radius:4px; padding:0; }
    table.dataTable thead th { background: transparent; border-bottom:1px solid #eaeff4; }
    .ref-link { color:#1f77d0; text-decoration:none; }
    .small-muted { color:#7b8a95; font-size:12px; display:block; }

    /* Options dropdown in table */
    .dropdown-options .dropdown-menu { min-width:180px; }
    .dropdown-options .dropdown-item i { width:18px; text-align:center; margin-right:8px; color:#495057; }

    /* floating action buttons (right bottom) */
    .fab-group { position:fixed; right:22px; bottom:84px; z-index:1100; display:flex; flex-direction:column; gap:12px; }
    .fab { width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#1f77d0;color:#fff;box-shadow:0 4px 12px rgba(31,119,208,.16);cursor:pointer;border:0; }
    .fab.help { background:#0aa; }

    /* footer bar (like print) */
    .footer-bar { position:fixed; left:0; right:0; bottom:0; background:#fff; border-top:1px solid #eee; padding:12px 20px; z-index:1050; display:flex; align-items:center; gap:12px; }
    .results-info { color:#666; font-size:13px; }

    /* responsive tweaks */
    @media (max-width: 767px) {
      .multiselect-native-select .btn { min-width: 140px; }
      .filters { padding:10px; }
      .top-actions { flex-wrap:wrap; }
      .fab-group { right:12px; bottom:90px; }
    }
  </style>


<div class="container-fluid p-3">
  <!-- breadcrumb-like -->
  <div class="breadcrumb-line">Produtos / <small class="text-muted">Listagem</small></div>

  <!-- top bar with actions -->
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div></div>
    <div class="top-actions">
      <button class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Adicionar Produto</button>
      <div class="btn-group">
        <button class="btn btn-outline-secondary">Mais <i class="fas fa-chevron-down ml-2"></i></button>
      </div>
    </div>
  </div>

  <!-- filters -->
  <div class="page-card filters">
    <form id="filtersForm">
      <div class="form-row row-gap align-items-center">
        <div class="col-md-4">
          <input type="text" id="qName" class="form-control" placeholder="Pesquisa por Nome ou Referência">
        </div>

        <div class="col-md-2">
          <select class="custom-select">
            <option>Lojas</option>
            <option>Loja A</option>
          </select>
        </div>

        <div class="col-md-2">
          <select id="selectCategoria" multiple="multiple">
            <option value="sem">Sem Categoria</option>
            <option value="chic">Chicletes</option>
            <option value="rev">Revistas</option>
          </select>
        </div>

        <div class="col-md-2">
          <select class="custom-select">
            <option>Sem Marca</option>
          </select>
        </div>

        <div class="col-md-2 text-right">
          <button type="button" id="btnClear" class="btn btn-link">Limpar</button>
          <button type="button" id="btnSearch" class="btn btn-outline-primary">Pesquisar</button>
        </div>
      </div>

      <!-- second row of filters -->
      <div class="form-row row-gap mt-3">
        <div class="col-md-3">
          <label class="mb-1">PVP</label>
          <div class="input-group">
            <input class="form-control form-control-sm" placeholder="Min">
            <input class="form-control form-control-sm" placeholder="até">
            <input class="form-control form-control-sm" placeholder="Max">
          </div>
        </div>

        <div class="col-md-3">
          <label class="mb-1">Preço Fornecedor</label>
          <div class="input-group">
            <input class="form-control form-control-sm" placeholder="Min">
            <input class="form-control form-control-sm" placeholder="até">
            <input class="form-control form-control-sm" placeholder="Max">
          </div>
        </div>

        <div class="col-md-2">
          <label class="mb-1">Stock</label>
          <input class="form-control form-control-sm" placeholder="Stock">
        </div>

        <div class="col-md-2">
          <label class="mb-1">Estado</label>
          <select class="custom-select custom-select-sm">
            <option>Ativo</option>
            <option>Inativo</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="mb-1">Filtrar por Tipo</label>
          <select class="custom-select custom-select-sm">
            <option>Todos</option>
          </select>
        </div>
      </div>

      <!-- checkbox row like print -->
      <div class="form-row mt-3">
        <div class="col-12">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="chkRef" checked>
            <label class="form-check-label" for="chkRef">Referência</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="chkNome" checked>
            <label class="form-check-label" for="chkNome">Nome do Produto</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="chkPreco" checked>
            <label class="form-check-label" for="chkPreco">Preço</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="chkCusto" checked>
            <label class="form-check-label" for="chkCusto">Preço de Custo</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="chkImposto">
            <label class="form-check-label" for="chkImposto">Imposto</label>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- table card -->
  <div class="table-wrap mt-4 page-card">
    <div class="p-3 d-flex justify-content-between align-items-center">
      <div>
        Mostrar
        <select id="pageLen" class="custom-select custom-select-sm" style="width:auto; display:inline-block;">
          <option>20</option><option>50</option><option>100</option>
        </select>
        registos
      </div>
      <div class="results-info">A Mostrar de 1 até 5 de 5 registos</div>
    </div>

    <div class="px-3">
      <table id="productsTable" class="display" style="width:100%">
        <thead>
          <tr>
            <th>Ref</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Preço Fornecedor</th>
            <th>Opções</th>
          </tr>
        </thead>
        <tbody>
          <!-- sample rows -->
          <tr>
            <td><a class="ref-link" href="#">demo-v.1</a></td>
            <td>
              <strong>Bubaloo</strong>
              <span class="small-muted">Chicletes</span>
            </td>
            <td>0,95 Kz <div class="small-muted">S/IVA: 0,83 Kz</div></td>
            <td>0,70 Kz</td>
            <td>
              <div class="dropdown dropdown-options">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">Opções</button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#"><i class="fas fa-file-alt"></i> Detalhe</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Editar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-copy"></i> Duplicar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-ban"></i> Suspender Produto</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> Apagar Produto</a>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td><a class="ref-link" href="#">demo-v.2</a></td>
            <td><strong>Gorila</strong><span class="small-muted">Chicletes</span></td>
            <td>0,05 Kz <div class="small-muted">S/IVA: 0,04 Kz</div></td>
            <td>0,04 Kz</td>
            <td>
              <div class="dropdown dropdown-options">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">Opções</button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#"><i class="fas fa-file-alt"></i> Detalhe</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Editar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-copy"></i> Duplicar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-ban"></i> Suspender Produto</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> Apagar Produto</a>
                </div>
              </div>
            </td>
          </tr>

          <!-- more sample rows -->
          <tr>
            <td><a class="ref-link" href="#">demo-v.3</a></td>
            <td><strong>Happydent</strong><span class="small-muted">Chicletes</span></td>
            <td>0,95 Kz <div class="small-muted">S/IVA: 0,83 Kz</div></td>
            <td>0,70 Kz</td>
            <td>
              <div class="dropdown dropdown-options">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">Opções</button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#"><i class="fas fa-file-alt"></i> Detalhe</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Editar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-copy"></i> Duplicar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-ban"></i> Suspender Produto</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> Apagar Produto</a>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td><a class="ref-link" href="#">demo-v.4</a></td>
            <td><strong>Trident Splash</strong><span class="small-muted">Chicletes</span></td>
            <td>1,09 Kz <div class="small-muted">S/IVA: 0,96 Kz</div></td>
            <td>0,81 Kz</td>
            <td>
              <div class="dropdown dropdown-options">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">Opções</button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#"><i class="fas fa-file-alt"></i> Detalhe</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Editar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-copy"></i> Duplicar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-ban"></i> Suspender Produto</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> Apagar Produto</a>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td><a class="ref-link" href="#">demo-v.5</a></td>
            <td><strong>Trident Mint</strong><span class="small-muted">Chicletes</span></td>
            <td>1,09 Kz <div class="small-muted">S/IVA: 0,96 Kz</div></td>
            <td>0,81 Kz</td>
            <td>
              <div class="dropdown dropdown-options">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">Opções</button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#"><i class="fas fa-file-alt"></i> Detalhe</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Editar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-copy"></i> Duplicar</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-ban"></i> Suspender Produto</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> Apagar Produto</a>
                </div>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- floating FABs -->
<div class="fab-group">
  <button class="fab" title="Grid"><i class="fas fa-th-large"></i></button>
  <button class="fab help" title="Ajuda"><i class="fas fa-question"></i></button>
</div>

<!-- bottom footer bar -->
<div class="footer-bar">
  <button class="btn btn-primary">SALVAR - F2</button>
  <button class="btn btn-outline-secondary">CANCELAR</button>
  <div style="flex:1"></div>
  <div class="results-info">A Mostrar de 1 até 5 de 5 registos</div>
</div>


<script>
  $(function(){
    // DataTable
    const table = $('#productsTable').DataTable({
      paging: true,
      info: false,
      lengthChange: false,
      pageLength: 20,
      columnDefs: [{ orderable:false, targets:4 }]
    });

    // page length select
    $('#pageLen').on('change', function(){
      table.page.len( parseInt($(this).val(),10) ).draw();
    });

    // bootstrap multiselect init (checkbox dropdown)
    $('#selectCategoria').multiselect({
      includeSelectAllOption: true,
      enableFiltering: true,
      buttonWidth: '220px',
      nonSelectedText: 'Sem Categoria',
      allSelectedText: 'Selecionar Todas',
      numberDisplayed: 2,
      selectAllText: 'Selecionar Todas',
      onChange: function(option, checked) {
        // nothing special now
      }
    });

    // search button (demo: filters table by name)
    $('#btnSearch').on('click', function(){
      const q = $('#qName').val().trim();
      table.search(q).draw();
    });

    $('#btnClear').on('click', function(){
      $('#filtersForm')[0].reset();
      $('#selectCategoria').multiselect('refresh');
      table.search('').draw();
    });

    // keep results-info text updated
    function updateInfo(){ 
      const info = table.page.info();
      $('.results-info').text('A Mostrar de ' + (info.start+1) + ' até ' + info.end + ' de ' + info.recordsDisplay + ' registos');
    }
    table.on('draw', updateInfo);
    updateInfo();

    // keyboard F2 focus search (optional)
    $(document).on('keydown', function(e){
      if(e.key === 'F2') { e.preventDefault(); $('#qName').focus(); }
    });
  });
</script>

