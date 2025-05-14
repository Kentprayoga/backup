@extends('layouts.app')

@section('content')
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
<div class="container-fluid">
  <div class="card shadow mb-4">
        <div class="card-header py-3">
    <h3>Daftar Template</h3>
    
    <!-- Button untuk Menampilkan Modal Template -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#templateModal">
        Tambah Template
    </button>
    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#categoryModal">Lihat Daftar Kategori
    </button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryAddModal">Tambah kategori
    </button>

    <!-- Modal Daftar Template -->
    <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="templateModalLabel">Tambah Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('template.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="categorie_id" class="form-label">Kategori</label>
                            <select name="categorie_id" id="categorie_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Template</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Template" required>
                        </div>
                        <div class="mb-3">
                            <label for="file_path" class="form-label">File Template</label>
                            <input type="file" class="form-control" id="file_path" name="file_path" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Template</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Template</th>
                <th>Kategori</th>
                <th>Format Nomor</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $template)
                <tr>
                    <td>{{ $template->id }}</td>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->category->name }}</td>
                    <td>{{ $template->format_nomor }}</td>
                    <td><a href="{{ asset('storage/' . $template->file_path) }}" target="_blank">Lihat File</a></td>
                    <td>
                        <a href="{{ route('template.edit', $template->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('template.destroy', $template->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>



{{--  --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Daftar Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <!-- Tombol Edit -->
<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
    Edit
</button>


                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit untuk setiap kategori -->
<!-- Tempatkan ini DI LUAR <tr> -->
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>ID Kategori</label>
                        <input type="text" class="form-control" value="{{ $category->id }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




    {{--  --}}
    <!-- Modal untuk menambah kategori -->
<div class="modal fade" id="categoryAddModal" tabindex="-1" aria-labelledby="categoryAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryAddModalLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk menambah kategori -->
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="category_id" class="form-label">ID Kategori</label>
                        <input type="number" class="form-control" id="category_id" name="id" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Kategori</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
