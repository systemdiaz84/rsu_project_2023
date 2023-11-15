<div class="table-responsive">
    <table class="table table-striped" id="home_members_table">
        <thead>
            <tr>
                <th>Miembro</th>
                <th>N° Documento</th>
                <th>Jefe de hogar</th>
                <th>Activo</th>
                <th>Pendiente</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td>{{ $member->name }} {{ $member->lastname }}</td>
                <td>{{ $member->n_doc }}</td>
                <td><span class="badge badge-pill badge-{{ $member->is_boss ? 'success' : 'warning' }}">
                                        {{ $member->is_boss ? 'Sí' : 'No' }}</span></td>
                <td><span class="badge badge-pill badge-{{ $member->is_active ? 'success' : 'danger' }}">
                                        {{ $member->is_active ? 'Sí' : 'No' }}</span></td>
                <td>
                    <span class="d-flex justify-content-center">
                        <span class="badge badge-pill badge-{{ $member->is_pending ? 'warning' : 'success' }}">
                            {{ $member->is_pending ? 'Pendiente' : 'Activo' }}
                        </span>
                    </span>
                    @if ($member->is_pending)
                        <div class="btn-group mt-1 d-flex justify-content-center">
                            <a href="{{ route('admin.homemembers.accept', [$home->id, $member->id]) }}" class="btn btn-success btn-sm">
                                Aceptar
                            </a>
                            <a href="{{ route('admin.homemembers.reject', [$home->id, $member->id]) }}" class="btn btn-danger btn-sm">
                                Rechazar
                            </a>
                        </div>
                    @endif
                </td>
                <td width="10px">
                    <form action={{ route('admin.homemembers.destroy', [$home->id, $member->id]) }} method='post'
                        class="frmDelete">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"><i
                                class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@section('js')
    <script>
        $(document).ready(function() {
            var table = $('#home_members_table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
                },
            });
        });

        </script>
@stop