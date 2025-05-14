@extends('layouts.app')

@section('content')
  <div class="container">
    <h1 class="text-dark mb-4">Selamat Datang, Admin!</h1>

    <!-- Kartu Dashboard -->
    <div class="d-flex gap-4 overflow-auto pb-2">
      <a href="{{ route('history.index') }}" class="text-decoration-none">
        <div class="card shadow-sm" style="min-width: 220px;">
          <div class="card-body text-center">
            <h3 class="card-title text-dark">{{ $totalDocuments }}</h3>
            <p class="card-text fw-semibold text-dark">Total Documents</p>
          </div>
        </div>
      </a>

      <a href="{{ route('approvals.index') }}" class="text-decoration-none">
        <div class="card shadow-sm" style="min-width: 220px;">
          <div class="card-body text-center">
            <h3 class="card-title text-dark">{{ $pendingApprovals }}</h3>
            <p class="card-text fw-semibold text-dark">Pending Approvals</p>
          </div>
        </div>
      </a>

      <a href="{{ route('admin.chat') }}" class="text-decoration-none">
        <div class="card shadow-sm" style="min-width: 220px;">
          <div class="card-body text-center">
            <h3 class="card-title text-dark">{{ $incomingMessages }}</h3>
            <p class="card-text fw-semibold text-dark">Pesan Masuk</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Chart Pengajuan per Tanggal -->
    <div class="card mt-4 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Pengajuan per Tanggal (7 Hari Terakhir)</h5>
        <canvas id="documentsChart"></canvas>
      </div>
    </div>

  </div>

  <!-- Script Chart.js -->
  <script>
    var ctx = document.getElementById('documentsChart').getContext('2d');
    var documentsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: @json($dates), // Tanggal
        datasets: [{
          label: 'Jumlah Pengajuan',
          data: @json($counts), // Jumlah pengajuan
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
@endsection
