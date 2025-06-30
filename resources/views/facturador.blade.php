<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>FACTURADOR</title>
  <style>
    .autocomplete-wrapper { position: relative; display:inline-block; width:300px; }
    .autocomplete-input { width: 100%; padding:.5em; box-sizing:border-box; border:1px solid #ccc; }
    .autocomplete-suggestions { position:absolute; top:calc(100% + 4px); left:0; width:100%; margin:0; padding:0; list-style:none; border:1px solid #ccc; background:#fff; box-sizing:border-box; display:none; max-height:200px; overflow-y:auto; z-index:1000; }
    .autocomplete-suggestions li { padding:.5em; cursor:pointer; }
    .autocomplete-suggestions li:hover { background:#eee; }
    #detalle { width:100%; border-collapse:collapse; margin-top:1em; }
    #detalle th, #detalle td { border:1px solid #ccc; padding:.5em; }
    #addRow { margin-top:.5em; }
  </style>
</head>


<body>
  <h1>FACTURADOR</h1>
  @if(session('status'))
    <p style="color:green;">{{ session('status') }}</p>
  @endif

  <form action="{{ route('facturador.store') }}" method="POST">
    @csrf 
    <!-- Cliente Autocomplete -->
    <x-autocomplete
      id="cliente"
      name="cliente_name"
      valueName="cliente_id"
      label="Cliente"
      placeholder="Busca un cliente..."
      :route="'clientes.autocomplete'"
      labelField="nombre"
      valueField="id"
      extraField="identificacion"
      class="cliente"
    />

    <!-- Tipo de movimiento -->
    <div style="margin-top:1em;">
      <label for="tipo_movimiento">Tipo:</label><br>
      <select id="tipo_movimiento" name="tipo_movimiento" required>
        <option value="">-- Tipo --</option>
        <option value="venta_contado">Venta al contado</option>
        <option value="venta_credito">Venta a crédito</option>
        <option value="pago">Pago</option>
        <option value="devolucion">Devolución</option>
      </select>
    </div>

    <!-- Tabla dinámica de productos -->
    <table id="detalle">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio Unitario</th>
          <th>Cantidad</th>
          <th>Descuento (%)</th>
          <th>Importe</th>
          <th></th>
        </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr>
          <td colspan="4" style="text-align:right;"><strong>Total:</strong></td>
          <td id="total">
            0.00
            
          </td>
          <td></td>
        </tr>
      </tfoot>
    </table>
    <button type="button" id="addRow">Añadir línea</button>

    <button type="submit" style="margin-top:1em;">Generar factura</button>
  </form>

  <!-- Template para fila de producto -->
  <template id="tpl-producto">
    <tr id="fila">
      <td style="position:relative; width:200px;">
        <x-autocomplete
          id="producto"
          name="producto_name[]"
          valueName="producto_id"
          placeholder="Buscar producto..."
          :route="'productos.autocomplete'"
          labelField="descripcion"
          valueField="id"
          extraField="precio"
          class="autocomplete-input"
          label=""
        />
      </td>
      <td ><input type="number" class="preciou" id="preciou" name="preciou[]" value="0.00" min="0" disabled></td>
      <td><input type="number" class="cantidad" id="cantidad" name="cantidad[]" value="1" min="1" class="w-16 cantidad" disabled></td>
      <td><input type="number" class="descuento" id="descuento" name="descuento[]" value="0" min="0" max="100" class="w-16 descuento" disabled></td>
      <td class="importe" id="importe"></td>
      <td><button type="button" class="remove-row">✕</button></td>
    </tr>
  </template>
@if(session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
@endif
@if(session('status'))
  <div class="alert alert-success">
    {{ session('status') }}
  </div>
@endif


@if($errors->any())
  <div style="color: red;">
    <ul>
      @foreach($errors->all() as $msg)
        <li>{{ $msg }}</li>
      @endforeach
    </ul>
  </div>
@endif

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('#detalle tbody');
    const total = document.getElementById('total');
    const tpl   = document.getElementById('tpl-producto').content;
    let idx = 1;

    function recalc() {
      let sum = 0;
      tbody.querySelectorAll('tr').forEach(r => {
        sum += parseFloat(r.querySelector('.importe').textContent) || 0;
      });
      total.innerHTML = `
        ${sum.toFixed(2)}
        <input type="hidden" name="total" value = "${sum.toFixed(2)}" required>
      `

      document.getElementsByName('total').value = sum.toFixed(2);
      console.log('el total es:',document.getElementsByName('total').value)
    }

    function addRow() {
      const clone = document.importNode(tpl, true);
      const auto = clone.querySelector('[id*="producto_"]');
      const newId = `producto${idx}`;
      auto.id = newId;
      auto.dataset.row = idx
      auto.dataset.column = 1
      console.log("CLONE", clone)
      
      clone.querySelector('[id*="fila"]').id += idx

      
      clone.querySelectorAll('[for*="producto_"]').forEach(l => l.htmlFor = newId);
      clone.querySelectorAll('#producto_-id').forEach(h => h.id = `${newId}-value`);
      clone.querySelectorAll('#producto_-suggestions').forEach(u => u.id = `${newId}-suggestions`);
      console.log("CLONE", clone)
      tbody.appendChild(clone);

      const row      = tbody.lastElementChild;
      const priceTd  = row.querySelector('.preciou');
      const qtyInput = row.querySelector('.cantidad');
      const discInput= row.querySelector('.descuento');
      const impEl    = row.querySelector('.importe');
      let basePrice  = 0;

      priceTd.id =`preciou${idx}`
      priceTd.dataset.row = idx
      priceTd.dataset.column = 2
      qtyInput.id =`cantidad${idx}`
      qtyInput.dataset.row = idx
      qtyInput.dataset.column = 3
      discInput.dataset.row = idx
      discInput.dataset.column = 4
      discInput.id += idx
      impEl.id =`importe${idx}`
      impEl.dataset.row = idx
      impEl.dataset.column =5

      // Escucha evento extra para precio
      const nameInput = row.querySelector('.autocomplete-input');
      nameInput.addEventListener('click', () => {
        basePrice = parseFloat(nameInput.querySelector('.contenedor').dataset.extra) || 0;
        console.log('baseprice = ', basePrice)
        priceTd.value  = basePrice.toFixed(2);
        qtyInput.value       = 1;
        discInput.value      = 0;
        impEl.textContent    = basePrice.toFixed(2);
        recalc();
      });

      // Recalcular importe
      [qtyInput, discInput].forEach(i => i.addEventListener('input', () => {
        const q = parseFloat(qtyInput.value) || 0;
        const d = parseFloat(discInput.value) || 0;
        const price = parseFloat(qtyInput.closest('tr').querySelector('.preciou').value)
        const totalLine = price * q * (1 - d/100);
        impEl.textContent = totalLine.toFixed(2);
        recalc();
      }));

      // Eliminar fila
      row.querySelector('.remove-row').addEventListener('click', () => {
        row.remove();
        recalc();
      });

      idx++;

    const inputField = document.querySelectorAll(".preciou");
    
    inputField.forEach( i => i.addEventListener('change', (event) => {
      console.log('Value changed to:', event.target.value);
      console.log(i)
      const row      = i.closest('tr');
      console.log(row)
      const importe = row.querySelector('.importe')
      const cantidad = row.querySelector('.cantidad')
      importe.textContent = event.target.value * row.querySelector('.cantidad').value
      recalc()
    }))
    }

    document.getElementById('addRow').addEventListener('click', addRow);
    addRow();




    // Evento al Seleccionar un autocomplete-option
    document.body.addEventListener('click', e => {
      if (e.target.matches('.autocomplete-suggestions li')) {
        const li = e.target;
        if(li.matches(".autocomplete-option-precio")){
          const row = li.closest('tr');

          // Extraer precio del producto
          const precioUnitario = parseFloat(li.dataset.extra) || 0;
          
          // Colocar precio
          row.querySelector('.preciou').value = precioUnitario.toFixed(2);

          //Habilitar campos
          row.querySelector('.preciou').removeAttribute('disabled')
          row.querySelector('.cantidad').removeAttribute('disabled')
          row.querySelector('.descuento').removeAttribute('disabled')

          recalc()
        }
      }
  
});
  });




  </script>
</body>
</html>
