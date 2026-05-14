 <div wire:ignore class="d-flex align-items-center justify-content-between  gap-2  mb-2">

                                <!-- Quantidade de dados na tela -->
                                    <div class="d-flex align-items-center">
                                        <span>Mostrar</span>
                                        @php
                                         $perPageOptions = range(10, 100, 10);
                                        @endphp
                                        <select title="Quantidade de resultados" wire:model.live='perPage' class="input-item p-2 border rounded">
                                            @foreach ($perPageOptions as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        <span>registos</span>
                                    </div>

                                    <div>

                                     @if (Route::current()->getname() === 'app.dashboard.categories')
                                            <a
                                                href="{{route('app.dashboard.form.category')}}"
                                                class="px-3 py-2 btn btn-primary btn-sm text-white rounded"
                                            >
                                                Adicionar
                                            </a>

                                    @endif

                                       @if (Route::current()->getname() === 'app.dashboard.subcategories')
                                            <a
                                                href="{{route('app.dashboard.form.subcategory')}}"
                                                class="px-3 py-2 btn btn-primary btn-sm text-white rounded"
                                            >
                                                Adicionar
                                            </a>

                                    @endif

                                    @if (Route::current()->getname() === 'app.dashboard.companies')
                                             <a
                                                href="{{route('app.dashboard.form.company')}}"
                                                class="px-3 py-2 btn btn-primary btn-sm text-white rounded"
                                            >
                                                Adicionar
                                            </a>
                                    @endif

                                    @if (Route::current()->getname() === 'app.dashboard.clients')
                                             <a
                                                href="{{route('app.dashboard.form.client')}}"
                                                class="px-3 py-2 btn btn-primary btn-sm text-white rounded"
                                            >
                                                Adicionar
                                            </a>
                                    @endif

                                    @if (Route::current()->getname() === 'app.dashboard.suppliers')
                                             <a
                                                href="{{route('app.dashboard.form.supplier')}}"
                                                class="px-3 py-2 btn btn-primary btn-sm text-white rounded"
                                            >
                                                Adicionar
                                            </a>
                                    @endif



                                    @if (Route::current()->getname() === 'app.dashboard.users')
                                             <a
                                                href="{{route('app.dashboard.form.user')}}"
                                                class="px-3 py-2 btn btn-primary btn-sm text-white rounded"
                                            >
                                                Adicionar
                                            </a>
                                    @endif

                                    @if (Route::current()->getname() === 'app.dashboard.units')
                                            <a
                                                href="{{route('app.dashboard.form.unit')}}"
                                                class="px-3 py-2 btn btn-primary btn-sm text-white rounded"
                                            >
                                                Adicionar
                                            </a>

                                    @endif
                                </div>

                                </div>

