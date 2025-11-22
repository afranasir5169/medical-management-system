<!-- resources/views/grns/create.blade.php -->
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Create GRN</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f5f8fa; }
    .panel { background:#eaf3f8; padding:20px; border-radius:6px; }
    .btn-save { background:#28a745; color:#fff; }
  </style>
</head>
<body>
<div class="container my-4">
  <h2>Goods Receive Note (GRN)</h2>

  @if($errors->any())
    <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="panel">
    <form action="{{ route('grns.store') }}" method="POST" id="grnForm">
      @csrf

      <div class="row mb-2">
        <div class="col-md-4">
          <label>GRN Number</label>
          <input name="grn_number" class="form-control" value="{{ $grnNumber ?? old('grn_number') }}" required>
        </div>
        <div class="col-md-4">
          <label>Invoice Number</label>
          <input name="invoice_number" class="form-control" value="{{ old('invoice_number') }}">
        </div>
        <div class="col-md-4">
          <label>GRN Date</label>
          <input type="date" name="grn_date" class="form-control" value="{{ old('grn_date', now()->toDateString()) }}">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Supplier</label>
          <select name="supplier_id" class="form-select">
            <option value="">-- Select Supplier --</option>
            @foreach($suppliers as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <h5>Received Items</h5>
      <table class="table table-bordered" id="itemsTable">
        <thead>
          <tr><th>No</th><th>Item Code</th><th>Item Name</th><th>Qty</th><th>Price/Unit</th><th>Total</th><th>Action</th></tr>
        </thead>
        <tbody></tbody>
      </table>

      <div class="row">
        <div class="col-md-4 offset-md-8">
          <label>Total:</label>
          <div id="displayTotal">Rs. 0.00</div>
          <label>Discount (%)</label>
          <input type="number" step="0.01" id="discountPercent" name="discount_percent" class="form-control" value="0">
          <label>Net:</label>
          <div id="displayNet">Rs. 0.00</div>
        </div>
      </div>

      <div class="text-end mt-3">
        <button type="button" id="addBlank" class="btn btn-outline-primary">Add Item</button>
        <button type="submit" class="btn btn-save">Save GRN</button>
      </div>
    </form>
  </div>
</div>

<script>
  const tbody = document.querySelector('#itemsTable tbody');
  const addBlank = document.getElementById('addBlank');
  const discountInput = document.getElementById('discountPercent');
  const displayTotal = document.getElementById('displayTotal');
  const displayNet = document.getElementById('displayNet');

  function formatMoney(v){ return 'Rs. ' + Number(v).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}); }

  function recalc(){
    let total = 0;
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
      const ttl = parseFloat(tr.querySelector('.row-total').textContent) || 0;
      total += ttl;
    });
    displayTotal.textContent = formatMoney(total.toFixed(2));
    const disc = parseFloat(discountInput.value) || 0;
    const net = total - (disc/100 * total);
    displayNet.textContent = formatMoney(net.toFixed(2));
  }

  function addRow(data = {item_code:'', item_name:'', quantity:1, price_per_unit:0}){
    const idx = tbody.children.length;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${idx+1}</td>
      <td><input name="items[${idx}][item_code]" class="form-control form-control-sm" value="${data.item_code}"></td>
      <td><input name="items[${idx}][item_name]" class="form-control form-control-sm" value="${data.item_name}"></td>
      <td><input type="number" min="1" name="items[${idx}][quantity]" class="form-control form-control-sm qty" value="${data.quantity}"></td>
      <td><input type="number" step="0.01" name="items[${idx}][price_per_unit]" class="form-control form-control-sm price" value="${data.price_per_unit}"></td>
      <td class="row-total text-end">${(data.quantity * data.price_per_unit).toFixed(2)}</td>
      <td><button type="button" class="btn btn-danger btn-sm remove">Del</button></td>
    `;
    tbody.appendChild(tr);

    const qty = tr.querySelector('.qty');
    const price = tr.querySelector('.price');

    function updateRow(){
      const q = parseFloat(qty.value) || 0;
      const p = parseFloat(price.value) || 0;
      tr.querySelector('.row-total').textContent = (q*p).toFixed(2);
      recalc();
    }

    qty.addEventListener('input', updateRow);
    price.addEventListener('input', updateRow);

    tr.querySelector('.remove').addEventListener('click', () => {
      tr.remove();
      Array.from(tbody.children).forEach((r,i) => {
        r.querySelector('td:first-child').textContent = i+1;
        r.querySelectorAll('input').forEach(inp => {
          inp.name = inp.name.replace(/\[\d+\]/, '['+i+']');
        });
      });
      recalc();
    });

    recalc();
  }

  addBlank.addEventListener('click', () => addRow());
  discountInput.addEventListener('input', recalc);

  // If you want initial sample rows uncomment:
  // addRow({item_code:'Itm01', item_name:'Syringe 5ml', quantity:100, price_per_unit:50});
</script>
</body>
</html>
