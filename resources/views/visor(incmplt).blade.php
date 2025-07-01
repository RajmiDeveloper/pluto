<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Visor</title>
</head>
<body>
  <a href="{{ url('/') }}">
    <button type="button">Volver</button>
  </a>
  <h1>{{ $name }} #{{ $id }}</h1>

<ul>
  @foreach($columns as $col)
  <script>  console.log("{{ str($col) }}")</script>

  @endforeach
</ul>

<p>
  <a href="{{ url($back) }}"><button>← Volver</button></a>
</p>
</body>
</html>
