@extends('layouts.host')

@section('content')
<div x-data="{ search: '{{ $currentSearch }}', statusFilter: '{{ $currentStatus }}' }" class="space-y-6">
  <!-- HEADER & FILTER -->
  <div class="rounded-2xl bg-white shadow-soft p-4 sm:p-6 border border-brand-200">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Riwayat Kunjungan</h2>
        <p class="text-slate-600 mt-1">Daftar seluruh riwayat kunjungan tamu yang telah diproses</p>
      </div>
      
      <!-- TANGGAL & CETAK -->
      <div class="flex flex-wrap items-center gap-3">
        <div class="inline-flex items-center gap-2 rounded-xl bg-brand-50 px-4 py-2.5 border border-brand-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 4h-1V3c0-.55-.45-1-1-1s-1 .45-1 1v1H8V3c0-.55-.45-1-1-1s-1 .45-1 1v1H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/>
            <path d="M7 12h5v5H7z"/>
          </svg>
          <span id="dateDisplay" class="font-semibold text-brand-800 text-sm">
            {{ now()->translatedFormat('l, d F Y') }}
          </span>
        </div>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="mt-6 flex flex-col sm:flex-row gap-4">
      <!-- SEARCH FORM -->
      <div class="flex-1">
        <form method="GET" action="{{ route('host.riwayat') }}" id="searchForm">
          <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
              <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <input x-model="search" 
                   name="search"
                   type="text" 
                   class="w-full pl-12 pr-4 py-3 border-2 border-brand-200 rounded-xl focus:border-brand-400 focus:ring-2 focus:ring-brand-200 placeholder-slate-500"
                   placeholder="Cari nama tamu, instansi, atau keperluan..."
                   value="{{ $currentSearch }}"
                   @input.debounce.500ms="$el.form.submit()">
          </div>
          <input type="hidden" name="status" :value="statusFilter">
        </form>
      </div>
      
      <!-- STATUS FILTER -->
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('host.riwayat', ['status' => 'semua', 'search' => $currentSearch]) }}"
           @click="statusFilter = 'semua'"
           :class="statusFilter === 'semua' ? 'bg-brand-500 text-white' : 'bg-white text-slate-700 hover:bg-slate-50'"
           class="ripple px-4 py-2.5 rounded-xl border border-brand-200 font-medium transition-colors">
          Semua ({{ $statistik['semua'] ?? 0 }})
        </a>
        <a href="{{ route('host.riwayat', ['status' => 'diterima', 'search' => $currentSearch]) }}"
           @click="statusFilter = 'diterima'"
           :class="statusFilter === 'diterima' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-700 hover:bg-slate-50'"
           class="ripple px-4 py-2.5 rounded-xl border border-emerald-200 font-medium transition-colors">
          Disetujui ({{ $statistik['diterima'] ?? 0 }})
        </a>
        <a href="{{ route('host.riwayat', ['status' => 'ditolak', 'search' => $currentSearch]) }}"
           @click="statusFilter = 'ditolak'"
           :class="statusFilter === 'ditolak' ? 'bg-rose-500 text-white' : 'bg-white text-slate-700 hover:bg-slate-50'"
           class="ripple px-4 py-2.5 rounded-xl border border-rose-200 font-medium transition-colors">
          Ditolak ({{ $statistik['ditolak'] ?? 0 }})
        </a>
        <a href="{{ route('host.riwayat', ['status' => 'selesai', 'search' => $currentSearch]) }}"
           @click="statusFilter = 'selesai'"
           :class="statusFilter === 'selesai' ? 'bg-blue-500 text-white' : 'bg-white text-slate-700 hover:bg-slate-50'"
           class="ripple px-4 py-2.5 rounded-xl border border-blue-200 font-medium transition-colors">
          Selesai ({{ $statistik['selesai'] ?? 0 }})
        </a>
      </div>
    </div>
  </div>

  <!-- TABEL RIWAYAT -->
  <div class="rounded-2xl bg-white shadow-soft overflow-hidden border border-brand-200">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[1000px]">
        <thead class="bg-brand-50">
          <tr class="border-b border-brand-200">
            <th class="sticky-col whitespace-nowrap pl-6 pr-4 py-4 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">
              <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                TAMU
              </div>
            </th>
            <th class="whitespace-nowrap px-4 py-4 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">
              INSTANSI
            </th>
            <th class="whitespace-nowrap px-4 py-4 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">
              TANGGAL
            </th>
            <th class="whitespace-nowrap px-4 py-4 text-xs font-semibold text-slate-900 uppercase tracking-wider text-center">
              WAKTU
            </th>
            <th class="whitespace-nowrap px-4 py-4 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">
              KEPERLUAN
            </th>
            <th class="whitespace-nowrap px-4 py-4 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">
              ANGGOTA DPRD
            </th>
            <th class="whitespace-nowrap px-4 py-4 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">
              STATUS
            </th>
            <th class="whitespace-nowrap px-4 py-4 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">
              DIPROSES PADA
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 bg-white">
          @forelse ($riwayat as $item)
          <tr class="hover:bg-slate-50 transition-colors">
            <!-- NAMA TAMU -->
            <td class="sticky-col whitespace-nowrap pl-6 pr-4 py-4">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 font-bold text-sm">
                  {{ strtoupper(substr($item['nama'], 0, 1)) }}
                </div>
                <div>
                  <div class="font-semibold text-slate-900">{{ $item['nama'] }}</div>
                  <div class="text-xs text-slate-500 mt-0.5">ID: {{ $item['id'] }}</div>
                </div>
              </div>
            </td>
            
            <!-- INSTANSI -->
            <td class="whitespace-nowrap px-4 py-4">
              <div class="font-medium text-slate-900">{{ $item['instansi_nama'] }}</div>
              @if($item['instansi_kategori'])
              <div class="text-xs text-slate-500">{{ $item['instansi_kategori'] }}</div>
              @endif
            </td>
            
            <!-- TANGGAL -->
            <td class="whitespace-nowrap px-4 py-4">
              <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 4h-1V3c0-.55-.45-1-1-1s-1 .45-1 1v1H8V3c0-.55-.45-1-1-1s-1 .45-1 1v1H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/>
                </svg>
                <span class="text-sm font-medium text-slate-800">
                  {{ $item['tanggal_kunjungan_formatted'] }}
                </span>
              </div>
            </td>
            
            <!-- WAKTU -->
            <td class="whitespace-nowrap px-4 py-4">
              <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 border border-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                  <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                </svg>
                <span class="text-sm font-medium text-blue-800">{{ $item['waktu_kunjungan'] }}</span>
              </div>
            </td>
            
            <!-- KEPERLUAN -->
            <td class="px-4 py-4">
              <div class="max-w-xs">
                <div class="font-medium text-slate-900 line-clamp-2">{{ $item['keperluan'] }}</div>
                @if($item['jumlah_peserta'])
                <div class="text-xs text-slate-500 mt-1">{{ $item['jumlah_peserta'] }} peserta</div>
                @endif
              </div>
            </td>
            
            <!-- ANGGOTA DPRD -->
            <td class="whitespace-nowrap px-4 py-4">
              <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"/>
                </svg>
                <span class="font-medium text-slate-900">{{ $item['bertemu_dengan'] }}</span>
              </div>
            </td>
            
            <!-- STATUS -->
            <td class="whitespace-nowrap px-4 py-4">
              @if ($item['status_sekarang'] === 'diterima')
              <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                {{ $label['diterima'] ?? 'Disetujui' }}
              </span>
              @elseif ($item['status_sekarang'] === 'ditolak')
              <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
                {{ $label['ditolak'] ?? 'Ditolak' }}
              </span>
              @elseif ($item['status_sekarang'] === 'selesai')
              <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                {{ $label['selesai'] ?? 'Selesai' }}
              </span>
              @endif
            </td>
            
            <!-- DIPROSES PADA -->
            <td class="whitespace-nowrap px-4 py-4">
              <div class="text-sm text-slate-700">
                {{ $item['diproses_pada'] }}
              </div>
              <div class="text-xs text-slate-500 mt-0.5">Oleh: {{ $item['host_nama'] }}</div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="px-4 py-12 text-center">
              <div class="mx-auto max-w-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z"/>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Tidak ada riwayat</h3>
                <p class="mt-2 text-slate-600">Tidak ada data riwayat yang sesuai dengan filter Anda</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINATION -->
    @if($riwayat->hasPages())
    <div class="border-t border-slate-200 px-4 py-4 sm:px-6">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-slate-700">
          Menampilkan <span class="font-semibold">{{ $riwayat->firstItem() ?? 0 }}-{{ $riwayat->lastItem() ?? 0 }}</span> 
          dari <span class="font-semibold">{{ $riwayat->total() }}</span> riwayat
        </div>
        <div class="flex gap-1">
          {{ $riwayat->links('vendor.pagination.simple-tailwind') }}
        </div>
      </div>
    </div>
    @endif
  </div>
</div>

<!-- MODAL DETAIL -->
<div id="modal-detail" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden place-items-center p-4" aria-hidden="true">
  <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl animate-scaleIn">
    <div class="sticky top-0 z-10 bg-white border-b border-slate-200 px-6 py-4">
      <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          Detail Kunjungan
        </h3>
        <button type="button" onclick="closeDetail()" 
                class="ripple rounded-lg p-1 text-slate-500 hover:bg-slate-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </button>
      </div>
    </div>
    
    <div class="p-6 space-y-6">
      <div id="detail-content">
        <!-- Konten akan diisi oleh JavaScript -->
      </div>
    </div>
  </div>
</div>

<!-- MODAL ALASAN -->
<div id="modal-alasan" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden place-items-center p-4" aria-hidden="true">
  <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-2xl animate-scaleIn">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-100 text-rose-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
        </span>
        Alasan Penolakan
      </h3>
      <button type="button" onclick="closeAlasan()" 
              class="ripple rounded-lg p-1 text-slate-500 hover:bg-slate-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>
    
    <div id="alasan-content" class="p-4 bg-rose-50 rounded-xl border border-rose-200 text-slate-700 whitespace-pre-wrap">
      <!-- Konten alasan akan diisi oleh JavaScript -->
    </div>
    
    <div class="mt-6 flex justify-end">
      <button type="button" onclick="closeAlasan()" 
              class="ripple rounded-xl bg-rose-600 px-5 py-2.5 font-bold text-white hover:bg-rose-700">
        Tutup
      </button>
    </div>
  </div>
</div>

<script>
function printRiwayat() {
  // Buka window baru untuk print
  const printWindow = window.open('{{ route("host.riwayat", array_merge(request()->all(), ['print' => '1'])) }}', '_blank');
  
  // Tidak perlu timeout karena halaman akan otomatis mencetak ketika parameter print=1
}

function showDetail(item) {
  // Format tanggal
  const tanggal = item.tanggal_kunjungan_formatted || '—';
  const waktu = item.waktu_kunjungan || '—';
  const diproses = item.diproses_pada || '—';
  const host = item.host_nama || '—';
  
  // Tentukan badge status
  let statusBadge = '';
  if (item.status_sekarang === 'diterima') {
    statusBadge = '<span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-800">✓ Disetujui</span>';
  } else if (item.status_sekarang === 'ditolak') {
    statusBadge = '<span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-3 py-1 text-sm font-semibold text-rose-800">✗ Ditolak</span>';
  } else if (item.status_sekarang === 'selesai') {
    statusBadge = '<span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">✓ Selesai</span>';
  }
  
  // Isi konten modal detail
  document.getElementById('detail-content').innerHTML = `
    <div class="space-y-6">
      <!-- Header dengan status -->
      <div class="flex items-center justify-between">
        <div>
          <h4 class="text-lg font-bold text-slate-900">${item.nama}</h4>
          <p class="text-slate-600">${item.instansi_nama}</p>
        </div>
        ${statusBadge}
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Kolom Kiri: Data Tamu -->
        <div class="space-y-4">
          <div class="bg-slate-50 rounded-xl p-4">
            <h5 class="font-bold text-slate-900 mb-3 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Data Tamu
            </h5>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Nama:</span>
                <span class="text-slate-900">${item.nama}</span>
              </div>
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Instansi:</span>
                <span class="text-slate-900">${item.instansi_nama}</span>
              </div>
              ${item.instansi_kategori ? `
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Kategori:</span>
                <span class="text-slate-900">${item.instansi_kategori}</span>
              </div>` : ''}
              ${item.email ? `
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Email:</span>
                <span class="text-slate-900">${item.email}</span>
              </div>` : ''}
              ${item.no_hp ? `
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">No. HP:</span>
                <span class="text-slate-900">${item.no_hp}</span>
              </div>` : ''}
            </div>
          </div>
          
          <div class="bg-slate-50 rounded-xl p-4">
            <h5 class="font-bold text-slate-900 mb-3 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7 7h10v2H7V7zm10 10H7v-2h10v2zm0-4H7v-2h10v2z"/>
              </svg>
              Informasi Pemrosesan
            </h5>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Diproses pada:</span>
                <span class="text-slate-900">${diproses}</span>
              </div>
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Oleh:</span>
                <span class="text-slate-900">${host}</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Kolom Kanan: Jadwal & Keperluan -->
        <div class="space-y-4">
          <div class="bg-slate-50 rounded-xl p-4">
            <h5 class="font-bold text-slate-900 mb-3 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 4h-1V3c0-.55-.45-1-1-1s-1 .45-1 1v1H8V3c0-.55-.45-1-1-1s-1 .45-1 1v1H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/>
              </svg>
              Jadwal Kunjungan
            </h5>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Tanggal:</span>
                <span class="text-slate-900">${tanggal}</span>
              </div>
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Waktu:</span>
                <span class="text-slate-900">${waktu} WITA</span>
              </div>
              ${item.jumlah_peserta ? `
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Jumlah Peserta:</span>
                <span class="text-slate-900">${item.jumlah_peserta} orang</span>
              </div>` : ''}
            </div>
          </div>
          
          <div class="bg-slate-50 rounded-xl p-4">
            <h5 class="font-bold text-slate-900 mb-3 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12H7v-2h6v2zm4-4H7V8h10v2z"/>
              </svg>
              Tujuan & Keperluan
            </h5>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="font-medium text-slate-700">Bertemu dengan:</span>
                <span class="text-slate-900">${item.bertemu_dengan}</span>
              </div>
              <div>
                <span class="font-medium text-slate-700 block mb-1">Keperluan:</span>
                <p class="text-slate-900 bg-white p-3 rounded-lg border border-slate-200">${item.keperluan}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      ${item.status_sekarang === 'ditolak' && item.alasan ? `
      <div class="bg-rose-50 rounded-xl p-4 border border-rose-200">
        <h5 class="font-bold text-rose-900 mb-2 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          Alasan Penolakan
        </h5>
        <p class="text-rose-800 whitespace-pre-wrap">${item.alasan}</p>
      </div>` : ''}
    </div>
  `;
  
  // Tampilkan modal
  document.getElementById('modal-detail').classList.remove('hidden');
}

function closeDetail() {
  document.getElementById('modal-detail').classList.add('hidden');
}

function showAlasan(alasan) {
  document.getElementById('alasan-content').textContent = alasan;
  document.getElementById('modal-alasan').classList.remove('hidden');
}

function closeAlasan() {
  document.getElementById('modal-alasan').classList.add('hidden');
}

// Tutup modal dengan ESC
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeDetail();
    closeAlasan();
  }
});

// Tutup modal saat klik di luar
document.getElementById('modal-detail')?.addEventListener('click', function(e) {
  if (e.target === this) closeDetail();
});
document.getElementById('modal-alasan')?.addEventListener('click', function(e) {
  if (e.target === this) closeAlasan();
});
</script>
@endsection