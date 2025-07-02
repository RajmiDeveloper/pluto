<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Facturas</title>
  <style>
    /* El modal, oculto por defecto */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      overflow: auto;
    }
    .modal-content {
      background-color: #fefefe;
      margin: 10% auto; padding: 20px;
      border: 1px solid #888;
      width: 80%; max-width: 400px;
      position: relative;
    }
    .close {
      position: absolute; right: 10px; top: 5px;
      font-size: 28px; font-weight: bold;
      color: #aaa; cursor: pointer;
    }
    .close:hover { color: #000; }
  </style>
</head>
<body>
  <a href="{{ url('/') }}">
    <button type="button">Volver</button>
  </a>

  <h1>Facturas</h1>

  <ul>
    @foreach($facturas as $fac)
      <li style="width:100%; margin-bottom:.5em;">
        Factura #{{ $fac->id }} —
        Cliente: {{ $fac->cliente_nombre ?? '—' }} —
        Total: ${{ number_format($fac->monto,2) }}

        <button
          type="button"
          class="openModalBtn"
          data-cliente-id="{{ $fac->cliente_id }}"
          data-cliente-name="{{ $fac->cliente_nombre }}"
          data-monto="{{ number_format($fac->monto,2) }}"
        >
          Ver
        </button>
      </li>
    @endforeach
  </ul>

  <!-- Modal -->
  <div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Detalles de Factura</h2>
      <p id="modal-cliente-id"><strong>Cliente ID:</strong> </p>
      <p id="modal-cliente-name"><strong>Nombre:</strong> </p>
      <p id="modal-monto"><strong>Total:</strong> </p>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal        = document.getElementById('myModal');
      const closeBtn     = modal.querySelector('.close');
      const openBtns     = document.querySelectorAll('.openModalBtn');
      const cliIdField   = document.getElementById('modal-cliente-id');
      const cliNameField = document.getElementById('modal-cliente-name');
      const montoField   = document.getElementById('modal-monto');

      openBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          cliIdField.innerHTML   = `<strong>Cliente ID:</strong> ${btn.dataset.clienteId}`;
          cliNameField.innerHTML = `<strong>Nombre:</strong> ${btn.dataset.clienteName}`;
          montoField.innerHTML   = `<strong>Total:</strong> $${btn.dataset.monto}`;
          modal.style.display    = 'block';
        });
      });

      closeBtn.addEventListener('click', () => modal.style.display = 'none');

      window.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
      });
    });
  </script>
</body>
</html>
