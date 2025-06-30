<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>PRODUCTOS</title>
</head>
<body>
  <a href="{{ url('/') }}">
    <button type="button">Volver</button>
  </a>
  <h1>PRODUCTOS</h1>

  <!-- Mensaje de éxito -->
  @if(session('status'))
    <p style="color: green;">{{ session('status') }}</p>
  @endif

  <!-- Formulario de alta -->
  <form action="{{ route('productos.store') }}" method="POST">
    @csrf                                  {{-- Protege contra CSRF --}}
    <input 
      type="text" 
      name="descripcion" 
      placeholder="Descripcion" 
      value="{{ old('descripcion') }}" 
      required
    >
    @error('descripcion')
      <div style="color:red;">{{ $message }}</div>
    @enderror
    <input 
      type="number" 
      name="stock" 
      placeholder="Stock" 
      value="{{ old('stock') }}" 
      required
      min=0
    >

    <input 
      type="number" 
      name="precio"
      step="0.01"
      placeholder="Precio" 
      value="{{ old('precio') }}" 
      required
      min=0
    >

    @error('apellido')
      <div style="color:red;">{{ $message }}</div>
    @enderror
    @error('stock')
      <div style="color:red;">{{ $message }}</div>
    @enderror
    @error('precio')
      <div style="color:red;">{{ $message }}</div>
    @enderror

    <button type="submit">Agregar Producto</button>
  </form>

  <hr>
  <ul>
    @foreach($productos as $prod)
      <li>
        {{ $prod->descripcion }}
        {{ "|" }}
        ${{ $prod->precio }}
        {{ "|" }}
        {{ $prod->stock }} u
        <a href="{{ route('editor.edit', ['productos', $prod->id, "productos"]) }}">
          <button type="button" >
            editar
          </button>
        </a>
      </li>
    @endforeach
  </ul>

</body>
</html>
