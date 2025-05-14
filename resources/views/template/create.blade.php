@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Upload Template</h2>
    <form method="POST" action="{{ route('template.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Kategori</label>
            <select name="categorie_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Nama Template</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Format Nomor</label>
            <input type="text" name="format_nomor" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Upload File</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
