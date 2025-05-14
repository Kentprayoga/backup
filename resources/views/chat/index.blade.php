@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Inbox Admin</h3>

    <div class="row">
        {{-- Sidebar Pengguna --}}
        <div class="col-md-3">
            <h5>Pengguna:</h5>
            <ul class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item">
                        <a href="{{ route('admin.chat', ['user_id' => $user->id]) }}">
                            {{ $user->email }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Area Chat --}}
        <div class="col-md-9">
            @if($selectedUser)
                <h5>Chat dengan: {{ $selectedUser->email }}</h5>

                <div class="border p-3 mb-3" style="height: 400px; overflow-y: auto;">
                    @foreach($messages as $msg)
                        <div class="mb-3 p-3 rounded"
                            style="background-color: {{ $msg->sender_id == auth()->id() ? '#d1e7dd' : '#f8d7da' }}; 
                                margin-left: {{ $msg->sender_id == auth()->id() ? 'auto' : '0' }};">
                            <strong>{{ $msg->sender_id == auth()->id() ? 'Admin' : $selectedUser->email }}:</strong>
                            <p>{{ $msg->message }}</p>
                            <br>
                            <small>{{ $msg->created_at->format('d M Y H:i') }}</small>
                            {{-- Tombol Hapus Pesan --}}
                            @if($msg->sender_id == auth()->id()) 
                                <form action="{{ route('admin.chat.delete', ['id' => $msg->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">Hapus</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Form Balas Pesan --}}
                <form method="POST" action="{{ route('admin.chat', ['user_id' => $selectedUser->id]) }}">
                    @csrf
                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="3" placeholder="Tulis balasan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                </form>

                {{-- Clear All Messages --}}
                <form action="{{ route('admin.chat.clear', ['userId' => $selectedUser->id]) }}" method="POST" style="margin-top: 20px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus semua pesan dengan pengguna ini?')">Hapus Semua Pesan</button>
                </form>
            @else
                <p>Pilih pengguna dari daftar untuk melihat percakapan.</p>
            @endif
        </div>
    </div>

</div>
@endsection
