{{-- resources/views/editor.blade.php --}}
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Editor genérico: {{ $table }} #{{ $id }}</title>
</head>
<body>
  <h1>Editar {{ ucfirst($table) }} #{{ $id }}</h1>

  @if(session('status'))
    <p style="color: green">{{ session('status') }}</p>
  @endif

  <form action="{{ route('editor.update', [$table,$id,$back]) }}" method="POST">
    @csrf

    @foreach($columns as $col)
      <div style="margin-bottom:.5em;">
        <label for="{{ $col }}">{{ ucfirst(str_replace('_',' ',$col)) }}:</label><br>
        <input
          type="text"
          id="{{ $col }}"
          name="{{ $col }}"
          value="{{ old($col, $record->$col) }}"
          style="width:300px;"
        >
        @error($col)
          <div style="color:red">{{ $message }}</div>
        @enderror
      </div>
    @endforeach

    <button type="submit">Guardar cambios</button>
  </form>

 <p>
  <a href="{{ url($back) }}">← Volver</a>
</p>

</body>
</html>
