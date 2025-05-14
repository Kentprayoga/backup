@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Template</h3>

    <form action="{{ route('template.update', $template->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="categorie_id" class="form-label">Kategori</label>
            <select name="categorie_id" id="categorie_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $template->categorie_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Template</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $template->name }}" required>
        </div>

        <div class="mb-3">
            <label for="file_path" class="form-label">File Baru (kosongkan jika tidak diganti)</label>
            <input type="file" class="form-control" id="file_path" name="file_path">
@php use Illuminate\Support\Str; @endphp

<div class="mt-3">
    <label for="preview" class="form-label"></label>
    @if(Str::endsWith($template->file_path, ['.pdf']))
        <iframe src="{{ asset('storage/' . $template->file_path) }}" width="100%" height="500px"></iframe>
    @elseif(Str::endsWith($template->file_path, ['.jpg', '.jpeg', '.png']))
        <img src="{{ asset('storage/' . $template->file_path) }}" class="img-fluid" alt="Preview Gambar">
    @else
        <p>File tidak dapat dipreview. <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank">Download file</a></p>
    @endif
</div>

        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('template.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
