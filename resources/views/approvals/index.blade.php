@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
    <h2>Halaman Approval Dokumen</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>nip</th>
                            <th>Nama Surat</th>
                            <th>Nama Pengaju</th>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Pengajuan</th> <!-- Menampilkan tanggal pengajuan -->
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($approvals as $index => $approval)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $approval->document->user->profile->nip }}</td>
                                <td>{{ $approval->document->template->name ?? '-' }}</td>
                                <td>{{ $approval->document->user->profile->name }}</td> <!-- Nama Pengaju -->
                                <td>{{ $approval->document->document_number }}</td> <!-- Nomor Dokumen -->
                                <td>{{ \Carbon\Carbon::parse($approval->document->tanggal_pengajuan)->format('d-m-Y H:i:s') }}</td> <!-- Tanggal Pengajuan oleh user -->
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $approval->status }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#documentModal{{ $approval->id }}">
                                        Lihat
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal untuk melihat detail dokumen -->
                            <div class="modal fade" id="documentModal{{ $approval->id }}" tabindex="-1" aria-labelledby="documentModalLabel{{ $approval->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="documentModalLabel{{ $approval->id }}">Detail Dokumen</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>ID Pengaju:</strong> {{ $approval->document->user_id }}</p>
                                            <p><strong>Nama Pengaju:</strong> {{ $approval->document->user->profile->name }}</p> <!-- Nama Pengaju -->
                                            <p><strong>Nomor Dokumen:</strong> {{ $approval->document->document_number }}</p>
                                            <p><strong>Tanggal Pengajuan:</strong> {{ \Carbon\Carbon::parse($approval->document->tanggal_pengajuan)->format('d-m-Y H:i:s') }}</p> <!-- Tanggal Pengajuan -->
                                            <p><strong>Alasan:</strong> {{ $approval->document->alasan ?? '-' }}</p>

                                            @if ($approval->document->file_path)
                                                <p><strong>File Dokumen:</strong></p>
                                                <a href="{{ asset('storage/' . $approval->document->file_path) }}" target="_blank" class="btn btn-secondary">Lihat / Unduh File</a>
                                            @else
                                                <p><strong>Data Form:</strong></p>
                                                <ul>
                                                    <li><strong>Nama:</strong> {{ $approval->document->name }}</li>
                                                    <li><strong>Nomor Dokumen:</strong> {{ $approval->document->document_number }}</li>
                                                </ul>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('approvals.approve', $approval->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Setujui</button>
                                            </form>

                                            <form action="{{ route('approvals.reject', $approval->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada pengajuan yang menunggu approval.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
 