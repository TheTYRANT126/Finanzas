@props(['bag' => 'default'])
@if($errors->getBag($bag)->any())
    <div class="notice error-notice" role="alert">
        <strong>Revisa los datos del formulario.</strong>
        <ul>
            @foreach($errors->getBag($bag)->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
