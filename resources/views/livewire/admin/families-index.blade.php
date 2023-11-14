<div class="card">
    <div class="card-header">

        <input wire:model="search" type="text" name="" id="" class="form-control" placeholder="Buscar familia">

    </div>
    <div class="card-body">
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>DESCRIPCIÓN</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>

                @foreach ($families as $family)
                    <tr>
                        <td>{{ $family->id }}</td>
                        <td>{{ $family->name }}</td>
                        <td>{{ $family->description }}</td>
                        <td width="50px"><a href={{ route('admin.families.show', $family->id) }}
                                class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i></a></td>
                        <td width="50px">
                            <form action={{ route('admin.families.destroy', $family->id) }} method='post'>
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"><i
                                        class="bi bi-trash3"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="float-right">{{ $families->links() }}</div>
    </div>
</div>
