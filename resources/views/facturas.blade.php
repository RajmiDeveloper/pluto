  <title>Clientes</title>
</head>
<body>
  <a href="{{ url('/') }}">
    <button type="button">Volver</button>
  </a>
  <h1>Facturas</h1>

<ul>
  @foreach($facturas as $fac)
    <div style="width:100%; margin-bottom:.5em;">
      |{{ $fac->id }} {{ $fac->monto }}| 

      
      <a href="{{ route('visor.index', ['detalles_factura_venta','id_factura', $fac->id,'Factura', "facturas"]) }}">
        <button type="button" >
          Ver
        </button>
      </a>
    </div>
  @endforeach
</ul>


</body>
</html>
