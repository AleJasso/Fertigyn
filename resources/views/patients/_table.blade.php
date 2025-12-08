@php
    use Illuminate\Support\Str;
@endphp

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-uppercase small fw-semibold">Nombre</th>
                    <th class="text-uppercase small fw-semibold">Categoría</th>
                    <th class="text-uppercase small fw-semibold">Edad</th>
                    <th class="text-uppercase small fw-semibold">Teléfono</th>
                    <th class="text-uppercase small fw-semibold">Correo</th>
                    <th class="text-uppercase small fw-semibold text-center">Acciones</th>
                </tr>
            </thead>

            <tbody id="patients-tbody">
            @forelse($patients as $p)
                @php
                    $fullName = trim($p->first_name.' '.$p->last_name);
                    $age      = $p->age;
                    $catName  = $p->category?->name;
                    $search   = strtolower(
                        $fullName.' '.
                        ($catName ?? '').' '.
                        ($p->email ?? '').' '.
                        ($p->phone ?? '')
                    );
                @endphp

                <tr data-search="{{ $search }}">
                    <td class="fw-semibold">
                        {{ $fullName }}
                    </td>

                    <td>
                        {{ $catName ?? '—' }}
                    </td>

                    <td>
                        @if($age !== null)
                            {{ $age }} años
                        @else
                            —
                        @endif
                    </td>

                    <td>{{ $p->phone ?: '—' }}</td>
                    <td>{{ $p->email ?: '—' }}</td>

                    <td class="text-center">
                        <div class="d-inline-flex align-items-center gap-1 bg-fg-soft rounded-pill px-1 py-0">
                            {{-- Ver expediente --}}
                            <a href="{{ route('admin.consultations.index', $p) }}"
                              class="btn btn-sm border-0 bg-transparent text-warning px-2"
                              title="Ver expediente">
                                📁
                            </a>
                            @can('admin-only')
                            {{-- EDITAR: AQUI VAN LOS DATA-* QUE LEE EL SCRIPT --}}
                            <button type="button"
                                    class="btn btn-sm btn-warning border-0 rounded-pill px-3 btn-edit-patient"
                                    data-id="{{ $p->id }}"
                                    data-first-name="{{ $p->first_name }}"
                                    data-last-name="{{ $p->last_name }}"
                                    data-email="{{ $p->email }}"
                                    data-phone="{{ $p->phone }}"
                                    data-birth="{{ optional($p->birth_date)->format('Y-m-d') }}"
                                    data-sex="{{ $p->sex }}"
                                    data-category-id="{{ $p->category_id }}"
                                    data-address="{{ $p->address }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPatientModal">
                                ✏️
                            </button>

                            {{-- Eliminar (solo visual, aún sin lógica) --}}
                            <form method="POST"
                                  action="{{ route('admin.patients.destroy', $p) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar a {{ $p->full_name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm border-0 bg-transparent text-danger px-2"
                                        title="Eliminar paciente">
                                    🗑️
                                </button>
                                @endcan
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No hay pacientes registrados.
                    </td>
                </tr>
            @endforelse
            </tbody>

            @if($patients->hasPages())
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="d-flex justify-content-center my-3">
                                {{ $patients->appends(['q'=>$q,'cat'=>$cat])->links() }}
                            </div>
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
