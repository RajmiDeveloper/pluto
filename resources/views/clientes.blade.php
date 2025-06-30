<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Clientes</title>
</head>
<body>
  <a href="{{ url('/') }}">
    <button type="button">Volver</button>
  </a>
  <h1>CLIENTES</h1>

  <!-- Mensaje de éxito -->
  @if(session('status'))
    <p style="color: green;">{{ session('status') }}</p>
  @endif

  <form action="{{ route('clientes.store') }}" method="POST">
    @csrf                               
    <input 
      type="text" 
      name="nombre" 
      placeholder="Nombre" 
      value="{{ old('nombre') }}" 
      required
    >
    @error('nombre')
      <div style="color:red;">{{ $message }}</div>
    @enderror

    <input 
      type="text" 
      name="apellido" 
      placeholder="apellido" 
      value="{{ old('apellido') }}" 
      required
    >
    @error('apellido')
      <div style="color:red;">{{ $message }}</div>
    @enderror


    <input 
      type="text" 
      name="identificacion" 
      placeholder="Identificacion" 
      value="{{ old('indentificacion') }}" 
      required
    >
    @error('identificacion')
      <div style="color:red;">{{ $message }}</div>
    @enderror

    <input 
      type="text" 
      name="telefono" 
      placeholder="Teléfono (opcional)" 
      value="{{ old('telefono') }}"
    >
    @error('telefono')
      <div style="color:red;">{{ $message }}</div>
    @enderror

    <input 
      type="text" 
      name="direccion" 
      placeholder="Dirección (opcional)" 
      value="{{ old('direccion') }}"
    >
    @error('direccion')
      <div style="color:red;">{{ $message }}</div>
    @enderror

    <button type="submit">Agregar Cliente</button>
  </form>

  <hr>

  <!-- Listado de clientes -->
  <ul>
    @foreach($clientes as $cli)
      <li>
        {{ $cli->nombre }}
        {{ $cli->apellido }}
        {{ $cli->identificacion }} 
        (+54 {{ $cli->telefono ?? 'sin teléfono' }})
        {{ $cli->direccion ?? 'sin dirección' }}
        ${{ $cli->saldo }}
      </li>
    @endforeach
  </ul>

</body>
</html>
