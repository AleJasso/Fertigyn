<tbody id="patients-tbody">
@forelse($patients as $p)
  <tr>
    <td class="fw-semibold">
      {{ $p->full_name }}
    </td>

    <td>
      {{ $p->category?->name ?? '—' }}
    </td>

    <td>
      @if($p->age)
        {{ $p->age }} años
      @else
        —
      @endif
    </td>

    <td>{{ $p->phone ?: '—' }}</td>
    <td>{{ $p->email ?: '—' }}</td>

    <td class="text-end">
      <div class="btn-group btn-group-sm" role="group">
        {{-- Ver expediente --}}
        <a href="{{ route('admin.patients.show', $p) }}"
           class="btn btn-fg"
           title="Ver expediente">
          📁
        </a>

        {{-- Editar --}}
        <a href="{{ route('admin.patients.edit', $p) }}"
           class="btn btn-outline-secondary"
           title="Editar paciente">
          ✏️
        </a>

        {{-- Eliminar --}}
        <form method="POST"
              action="{{ route('admin.patients.destroy', $p) }}"
              class="d-inline"
              onsubmit="return confirm('¿Eliminar a {{ $p->full_name }}? Esta acción no se puede deshacer.');">
          @csrf
          @method('DELETE')
          <button type="submit"
                  class="btn btn-outline-danger"
                  title="Eliminar paciente">
            🗑️
          </button>
        </form>
      </div>
    </td>
  </tr>
@empty
  <tr>
    <td colspan="6" class="text-center text-muted py-4">
      Sin resultados.
    </td>
  </tr>
@endforelse
</tbody>

@if($patients->hasPages())
  <tfoot>
    <tr>
      <td colspan="6">
        <div class="d-flex justify-content-center my-3">
          {{ $patients->links() }}
        </div>
      </td>
    </tr>
  </tfoot>
@endif
