@props(['data' => [], 'active_users' => []])

<div class="container-fluid px-4 py-3">

    <div class="bg-white rounded p-3 mb-4 shadow-sm">
        <form
            method="GET"
            target="report-frame"
            action="{{ route('app.dashboard.report.user.get.data') }}">

            <div class="form-row align-items-center">

                <div class="col-md-3 mt-2 mt-md-0">
                    <select name="active_user_id" class="form-control">
                        <option value="0">-- Todos os Utilizadores --</option>
                        @if ($active_users->isNotEmpty())
                            @foreach ($active_users as $user)
                                <option value="{{ $user['id'] }}">
                                    {{ $user['name'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-4 mt-2 mt-md-0">
                    <input
                        name="searcher"
                        class="form-control"
                        placeholder="Pesquisar por nome, email, telefone"
                        type="text">
                </div>

                <div class="col-auto mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search mr-1"></i>
                        Aplicar Filtro
                    </button>
                </div>

            </div>
        </form>
    </div>

    <iframe
        name="report-frame"
        class="w-100 border-0 rounded bg-white"
        style="height: 420px;"
        src="{{ route('app.dashboard.report.user.get.data') }}">
    </iframe>

</div>
