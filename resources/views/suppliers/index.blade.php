<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Supplier Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* small custom styles to match your UI vibe */
    body { background:#f5f8fa; }
    .sidebar { background:#e7f0f6; min-height:100vh; }
    .form-panel { background:#eaf3f8; padding:30px; border-radius:4px; }
    .btn-save { background:#28a745; color:#fff; }
    .btn-reset { background:#bfc9c6; color:#fff; }
    .table-light-pink td, .table-light-pink th { background:#fdeff0; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-4">
      <h4 class="mb-4">Medical Management</h4>
      <nav class="nav flex-column">
        <a class="nav-link" href="#">Dashboard</a>
        <a class="nav-link active fw-bold" href="#">Supplier</a>
        <a class="nav-link" href="#">GRN</a>
        <a class="nav-link" href="#">Doctor</a>
        <a class="nav-link" href="#">Center</a>
        <a class="nav-link" href="#">Report</a>
      </nav>
    </div>

    <!-- Main -->
    <div class="col-md-10 p-4">
      <h2>Supplier Registration</h2>
      <p class="text-muted">Add a new supplier to the system</p>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="form-panel mb-4">
        <form method="POST" action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}">
          @csrf
          @if(isset($supplier))
            @method('PUT')
          @endif

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Supplier ID</label>
                <div class="col-sm-8">
                  <input type="text" name="supplier_id" class="form-control" value="{{ old('supplier_id', $supplier->supplier_id ?? '') }}">
                  @error('supplier_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Supplier Name</label>
                <div class="col-sm-8">
                  <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name ?? '') }}" required>
                  @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Company Name</label>
                <div class="col-sm-8">
                  <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $supplier->company_name ?? '') }}">
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                  <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email ?? '') }}">
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Phone</label>
                <div class="col-sm-8">
                  <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone ?? '') }}">
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Address</label>
                <div class="col-sm-8">
                  <input type="text" name="address" class="form-control" value="{{ old('address', $supplier->address ?? '') }}">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">City</label>
                <div class="col-sm-8">
                  <input type="text" name="city" class="form-control" value="{{ old('city', $supplier->city ?? '') }}">
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">District</label>
                <div class="col-sm-8">
                  <input type="text" name="district" class="form-control" value="{{ old('district', $supplier->district ?? '') }}">
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Supply Type</label>
                <div class="col-sm-8">
                  <select name="supply_type" class="form-select">
                    <option value="">-- Select --</option>
                    <option value="equipment" {{ old('supply_type', $supplier->supply_type ?? '')=='equipment' ? 'selected':'' }}>Equipment</option>
                    <option value="consumable" {{ old('supply_type', $supplier->supply_type ?? '')=='consumable' ? 'selected':'' }}>Consumable</option>
                    <option value="service" {{ old('supply_type', $supplier->supply_type ?? '')=='service' ? 'selected':'' }}>Service</option>
                  </select>
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Description</label>
                <div class="col-sm-8">
                  <textarea name="description" class="form-control" rows="4">{{ old('description', $supplier->description ?? '') }}</textarea>
                </div>
              </div>

              <div class="mt-4 text-end">
                <button type="submit" class="btn btn-save px-4 py-2 me-2">{{ isset($supplier) ? 'Update' : 'Save' }}</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-reset px-4 py-2">Reset Form</a>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Recently Added -->
      <h4>Recently Added Supplier</h4>

      <div class="table-responsive">
        <table class="table table-bordered table-sm table-light-pink">
          <thead>
            <tr>
              <th>No</th>
              <th>Supp Id</th>
              <th>Supp Name</th>
              <th>Company</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Address</th>
              <th>City</th>
              <th>District</th>
              <th>Supply Type</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($suppliers as $idx => $s)
              <tr>
                <td>{{ $suppliers->firstItem() + $idx }}</td>
                <td>{{ $s->supplier_id }}</td>
                <td>{{ $s->name }}</td>
                <td>{{ $s->company_name }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->phone }}</td>
                <td>{{ $s->address }}</td>
                <td>{{ $s->city }}</td>
                <td>{{ $s->district }}</td>
                <td>{{ $s->supply_type }}</td>
                <td>{{ Str::limit($s->description, 40) }}</td>
                <td>
                  <a href="{{ route('suppliers.edit', $s) }}" class="badge bg-info text-dark">Edit</a>
                  <form action="{{ route('suppliers.destroy', $s) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete this supplier?');">
                    @csrf
                    @method('DELETE')
                    <button class="badge bg-danger border-0">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="12" class="text-center">No suppliers yet</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $suppliers->links() }}
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
