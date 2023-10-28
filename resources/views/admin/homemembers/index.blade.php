@include('admin.homemembers.create')

<table class="table table-striped" id="home_members_table">
    <thead>
        <tr>
            <th>id</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($members as $member)
        <tr>
            <td>{{ $member->id }}</td>
            <td>{{ $member->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

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