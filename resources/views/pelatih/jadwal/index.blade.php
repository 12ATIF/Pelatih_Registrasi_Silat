@extends('layouts.pelatih')

@section('title', 'Jadwal Pertandingan')

@section('breadcrumb')
<li class="breadcrumb-item active">Jadwal</li>
@endsection

@section('content')
<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Filter Jadwal</h6>
    </div>
    <div class="card-body">
        <form id="filter-form" method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="pertandingan_id" class="form-label">Pertandingan</label>
                    <select class="form-select" id="pertandingan_id" name="pertandingan_id">
                        <option value="">Semua Pertandingan</option>
                        @foreach($pertandingans as $pertandingan)
                            <option value="{{ $pertandingan->id }}" {{ request('pertandingan_id') == $pertandingan->id ? 'selected' : '' }}>
                                {{ $pertandingan->nama_event }} ({{ date('d/m/Y', strtotime($pertandingan->tanggal_event)) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="subkategori_id" class="form-label">Subkategori</label>
                    <select class="form-select" id="subkategori_id" name="subkategori_id">
                        <option value="">Semua Subkategori</option>
                        @foreach($subkategoris as $subkategori)
                            <option value="{{ $subkategori->id }}" {{ request('subkategori_id') == $subkategori->id ? 'selected' : '' }}>
                                {{ $subkategori->kategoriLomba->nama }} - {{ $subkategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="kelompok_usia_id" class="form-label">Kelompok Usia</label>
                    <select class="form-select" id="kelompok_usia_id" name="kelompok_usia_id">
                        <option value="">Semua Kelompok Usia</option>
                        @foreach($kelompokUsias as $usia)
                            <option value="{{ $usia->id }}" {{ request('kelompok_usia_id') == $usia->id ? 'selected' : '' }}>
                                {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }} tahun)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="kontingen_id" class="form-label">Kontingen</label>
                    <select class="form-select" id="kontingen_id" name="kontingen_id">
                        <option value="">Semua Kontingen Saya</option>
                        @foreach($kontingens as $kontingen)
                            <option value="{{ $kontingen->id }}" {{ request('kontingen_id') == $kontingen->id ? 'selected' : '' }}>
                                {{ $kontingen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('pelatih.jadwal.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Calendar -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Kalender Jadwal</h6>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Daftar Jadwal -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Daftar Jadwal</h6>
    </div>
    <div class="card-body">
        @if($jadwals->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="jadwal-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Pertandingan</th>
                            <th>Kategori</th>
                            <th>Subkategori</th>
                            <th>Kelompok Usia</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $jadwal)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($jadwal->tanggal)) }}</td>
                            <td>{{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai ?: 'Selesai' }}</td>
                            <td>{{ $jadwal->pertandingan->nama_event }}</td>
                            <td>{{ $jadwal->subkategoriLomba->kategoriLomba->nama }}</td>
                            <td>{{ $jadwal->subkategoriLomba->nama }}</td>
                            <td>{{ $jadwal->kelompokUsia->nama }}</td>
                            <td>{{ $jadwal->lokasi_detail ?: $jadwal->pertandingan->lokasi_umum }}</td>
                            <td>
                                <a href="{{ route('pelatih.jadwal.show', $jadwal->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-calendar-alt fa-4x text-muted"></i>
                </div>
                <h4>Belum ada jadwal</h4>
                <p class="text-muted">Silakan pilih filter lain atau tunggu jadwal pertandingan diumumkan.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css">
<style>
    #calendar {
        height: 600px;
    }
    .fc-event {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
<script>
    $(document).ready(function() {
        $('#jadwal-table').DataTable({
            responsive: true,
            order: [[0, 'asc'], [1, 'asc']]
        });
        
        // Calendar
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
           },
           buttonText: {
               today: 'Hari ini',
               month: 'Bulan',
               week: 'Minggu',
               day: 'Hari',
               list: 'Agenda'
           },
           locale: 'id',
           events: [
               @foreach($jadwals as $jadwal)
               {
                   id: '{{ $jadwal->id }}',
                   title: '{{ $jadwal->subkategoriLomba->nama }} - {{ $jadwal->kelompokUsia->nama }}',
                   start: '{{ $jadwal->tanggal }}T{{ $jadwal->waktu_mulai }}',
                   end: '{{ $jadwal->tanggal }}T{{ $jadwal->waktu_selesai ?: '23:59' }}',
                   url: '{{ route('pelatih.jadwal.show', $jadwal->id) }}',
                   backgroundColor: getRandomColor('{{ $jadwal->subkategoriLomba->kategoriLomba->nama }}'),
                   borderColor: getRandomColor('{{ $jadwal->subkategoriLomba->kategoriLomba->nama }}'),
               },
               @endforeach
           ],
           eventClick: function(info) {
               if (info.event.url) {
                   info.jsEvent.preventDefault();
                   window.location.href = info.event.url;
               }
           }
       });
       calendar.render();
       
       // Function to generate consistent colors based on category
       function getRandomColor(category) {
           // Map of categories to colors
           const colorMap = {
               'Seni': '#4e73df',
               'Tanding': '#1cc88a',
               'Ganda': '#36b9cc',
               'Tunggal': '#f6c23e',
               'Regu': '#e74a3b',
           };
           
           // If category exists in map, return that color
           if (colorMap[category]) {
               return colorMap[category];
           }
           
           // Otherwise, generate a "random" but consistent color based on the category string
           let hash = 0;
           for (let i = 0; i < category.length; i++) {
               hash = category.charCodeAt(i) + ((hash << 5) - hash);
           }
           
           let color = '#';
           for (let i = 0; i < 3; i++) {
               const value = (hash >> (i * 8)) & 0xFF;
               color += ('00' + value.toString(16)).substr(-2);
           }
           
           return color;
       }
   });
</script>
@endpush