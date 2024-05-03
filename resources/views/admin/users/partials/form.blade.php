
<div class="form-group">
    {!! Form::label('name', 'Nombres') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese los nombres',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('lastname', 'Apellidos') !!}
    {!! Form::text('lastname', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese los apellidos',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('email', 'email') !!}
    {!! Form::text('email', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el email',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('password', 'password') !!}
    {!! Form::password('password',  [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el password',
        isset($user) ? '' : 'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('n_doc', 'DNI') !!}
    {!! Form::text('n_doc', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el DNI',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('role', 'Roles', ['class' => 'form-label']) !!}
    @foreach($roles as $role)
        <div class="checkbox">
            <label>
                {!! Form::checkbox('roles[]', $role->id, isset($user) ? $user->hasRole($role->name) : false) !!}
                {{ $role->name }}
            </label>
        </div>
    @endforeach
</div>
