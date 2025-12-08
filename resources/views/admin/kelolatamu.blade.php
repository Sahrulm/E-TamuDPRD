@extends('layouts.admin')

@section('content')
  <!-- HERO -->
  <section class="pt-2 pb-2 rounded-2xl border border-brand-300 relative overflow-hidden animate-fadeUp bg-gradient-to-br from-brand-100 via-[#FFFDF5] to-brand-100 shadow-soft">
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border border-brand-300/60 animate-floaty"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-200/70 animate-floaty" style="animation-delay:.8s"></div>
    <div class="absolute inset-x-0 top-0 h-1 shimmer animate-shimmer"></div>
    <div class="px-5 py-5">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-2">
        <svg class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
          <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
        </svg>
        Panel Admin - Kelola Tamu
      </h1>
      <p class="text-slate-700 mt-1">Kelola seluruh data kunjungan tamu DPRD Kota Gorontalo.</p>
    </div>
  </section>

  <!-- ========= KELOLA TAMU ========= -->
  <section>
    <div class="rounded-2xl border border-brand-300 bg-brand-50/80 backdrop-blur p-5 shadow-soft">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-4 justify-between mb-6">
        <h2 class="text-xl font-bold flex items-center gap-3 text-slate-900">
          <svg class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 7H5a2 2 0 00-2 2v7h18V9a2 2 0 00-2-2zM3 18h18v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1z"/>
          </svg>
          Kelola Tamu
        </h2>

        <div class="flex flex-wrap items-center gap-3 ml-auto mobile-stack">
          <span class="text-sm text-slate-700 whitespace-nowrap bg-white px-3 py-2 rounded-xl border border-brand-300">
            <span class="font-semibold text-brand-800" x-text="filteredTamu().length"></span> hasil
          </span>

          <select x-model="filter.status"
                  class="w-full md:w-40 rounded-xl border border-brand-400 px-3 py-2 text-sm bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300"
                  title="Filter Status">
            <option value="">Semua Status</option>
            <option value="menunggu">Menunggu</option>
            <option value="diterima">Diterima</option>
            <option value="ditolak">Ditolak</option>
            <option value="selesai">Selesai</option>
          </select>

          <input type="month" x-model="filter.month"
                 class="w-full md:w-44 rounded-xl border border-brand-400 px-3 py-2 text-sm bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300"
                 title="Filter Bulan">

          <input type="text" placeholder="Cari…"
                 x-model.debounce.300ms="filter.q"
                 class="w-full md:w-60 rounded-xl border border-brand-400 px-3 py-2 text-sm bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300"
                 title="Cari Nama/Email/Instansi/HP/Bertemu">

          <button type="button"
                  class="w-full md:w-auto px-4 py-2 text-sm rounded-xl border border-brand-400 bg-white hover:bg-brand-100/80 transition-all duration-300 hover:shadow-md"
                  @click="resetFilters()">
            Reset
          </button>
        </div>
      </div>

      <!-- Tabel + Pagination -->
      <div class="overflow-x-auto scrollbar-thin relative rounded-xl border border-brand-300 bg-white shadow-sm">
        <table class="min-w-[1700px] w-full text-sm">
          <thead class="sticky top-0 z-20 bg-brand-200 shadow-sm">
            <tr class="text-left text-slate-800 font-semibold">
              <th class="py-4 px-5 border-b border-brand-300">Nama</th>
              <th class="py-4 px-5 border-b border-brand-300">Email</th>
              <th class="py-4 px-5 border-b border-brand-300">Nomor HP</th>
              <th class="py-4 px-5 border-b border-brand-300 text-center">Jumlah Peserta</th>
              <th class="py-4 px-5 border-b border-brand-300">Kategori Instansi</th>
              <th class="py-4 px-5 border-b border-brand-300">Nama Instansi</th>
              <th class="py-4 px-5 border-b border-brand-300">Tanggal Berkunjung</th>
              <th class="py-4 px-5 border-b border-brand-300">Waktu Berkunjung</th>
              <th class="py-4 px-5 border-b border-brand-300 text-center">Status</th>
              <th class="py-4 px-5 border-b border-brand-300 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="align-top divide-y divide-brand-200">
            <!-- PAGINATED DATA -->
            <template x-for="row in paginatedTamu()" :key="row.id">
              <tr class="bg-white hover:bg-brand-50/50 transition-colors duration-300">
                <td class="py-4 px-5 font-medium text-slate-900" x-text="row.nama || '—'"></td>
                <td class="py-4 px-5 truncate text-slate-800" x-text="row.email || '—'"></td>
                <td class="py-4 px-5 whitespace-nowrap text-slate-800" x-text="row.no_hp || '—'"></td>
                <td class="py-4 px-5 text-center text-slate-900 font-medium" x-text="Intl.NumberFormat('id').format(row.jumlah_peserta || 0)"></td>
                <td class="py-4 px-5 uppercase whitespace-nowrap text-slate-700 text-xs font-medium bg-brand-100 px-2 py-1 rounded" x-text="row.instansi_kategori || '—'"></td>
                <td class="py-4 px-5 text-slate-800" x-text="row.instansi_nama || '—'"></td>
                <td class="py-4 px-5 whitespace-nowrap text-slate-800 font-medium" x-text="formatDate(row.tanggal_kunjungan)"></td>
                <td class="py-4 px-5 whitespace-nowrap text-slate-800 font-medium" x-text="formatTime(row.waktu_kunjungan) + ' WITA'"></td>
                <td class="py-4 px-5 text-center">
                  <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium ring-1 whitespace-nowrap transition-all duration-300" :class="badgeClass(row.status_sekarang)">
                    <span x-text="labelStatus(row.status_sekarang)"></span>
                  </span>
                </td>
                <td class="py-4 px-5">
                  <div class="flex flex-wrap gap-2 justify-center">
                    <button @click="editTamu(row)" class="ripple rounded-xl bg-brand-100 px-3 py-2 text-xs text-slate-800 hover:bg-brand-200 hover:text-brand-800 hover:shadow-md transition-all duration-300 flex items-center gap-1">
                      <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                      </svg>
                      Edit
                    </button>
                    <button @click="confirmDelete('tamu', row)" class="ripple rounded-xl bg-rose-100 px-3 py-2 text-xs text-rose-700 hover:bg-rose-200 hover:shadow-md transition-all duration-300 flex items-center gap-1">
                      <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                      </svg>
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </template>
            <tr x-show="filteredTamu().length===0">
              <td colspan="12" class="py-12 px-5 text-center text-slate-600 bg-white rounded-b-xl">
                <div class="flex flex-col items-center gap-3">
                  <svg class="h-12 w-12 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 7H5a2 2 0 00-2 2v7h18V9a2 2 0 00-2-2zM3 18h18v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1z"/>
                  </svg>
                  <p class="text-slate-700">Tidak ada data tamu sesuai filter.</p>
                  <button @click="resetFilters()" class="text-brand-600 hover:text-brand-700 text-sm font-medium mt-2">
                    Reset filter
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <div class="flex flex-wrap items-center justify-between mt-6 text-sm text-slate-700" x-show="filteredTamu().length > 0">
        <div class="mb-2 sm:mb-0 bg-white px-4 py-2 rounded-xl border border-brand-300">
          Menampilkan
          <span class="font-semibold text-brand-800" x-text="pageInfoStart()"></span>
          –
          <span class="font-semibold text-brand-800" x-text="pageInfoEnd()"></span>
          dari
          <span class="font-semibold text-brand-800" x-text="filteredTamu().length"></span>
          data
        </div>
        <div class="flex items-center gap-2">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-brand-400 bg-white hover:bg-brand-100/80 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 hover:shadow-md flex items-center gap-1"
            @click="prevPage()"
            :disabled="currentPage === 1"
          >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
            </svg>
            Prev
          </button>
          <template x-for="page in totalPages()" :key="page">
            <button
              type="button"
              class="px-4 py-2 rounded-xl border text-sm font-medium transition-all duration-300"
              :class="page === currentPage
                ? 'bg-brand-500 border-brand-500 text-white shadow-yellow-glow'
                : 'bg-white border-brand-400 text-slate-800 hover:bg-brand-100/80 hover:shadow-md'"
              @click="goToPage(page)"
              x-text="page"
            ></button>
          </template>
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-brand-400 bg-white hover:bg-brand-100/80 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 hover:shadow-md flex items-center gap-1"
            @click="nextPage()"
            :disabled="currentPage === totalPages()"
          >
            Next
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- ============== MODAL: Tambah Tamu (MULTI-STEP) ============== -->
  <div x-cloak x-show="showAddEditTamu && !editingTamu"
       x-data="{ step: 1 }"
       @keydown.escape.window="showAddEditTamu=false"
       class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-all duration-200"
       :class="showAddEditTamu ? 'opacity-100 visible' : 'opacity-0 invisible'">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-md" @click="showAddEditTamu=false"></div>

    <div class="relative w-full max-w-4xl max-h-[90vh] overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-brand-300 transition-transform animate-scaleIn">
      <!-- Header sticky -->
      <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-brand-300 bg-white/95 px-6 py-5 backdrop-blur">
        <div>
          <h3 class="text-2xl font-extrabold flex items-center gap-3 text-slate-900">
            <svg class="h-7 w-7 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 7H5a2 2 0 00-2 2v7h18V9a2 2 0 00-2-2zM3 18h18v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1z"/>
            </svg>
            <span>Pengajuan Kunjungan Tamu</span>
          </h3>
          <p class="mt-1 text-slate-700 text-sm">Silakan lengkapi formulir di bawah ini dengan data yang benar</p>
        </div>
        <button class="rounded-full p-2 text-slate-500 hover:bg-brand-100/80 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all duration-300" aria-label="Tutup" @click="showAddEditTamu=false">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>

      <!-- Stepper -->
      <div class="px-6 pt-4">
        <ol class="flex items-center gap-6 overflow-x-auto">
          <li class="flex items-center gap-3 flex-shrink-0">
            <span class="grid h-8 w-8 place-items-center rounded-full border-2 font-semibold transition-all duration-300"
                  :class="step===1 ? 'border-brand-500 bg-brand-500 text-white shadow-yellow-glow' : 'border-brand-300 bg-brand-100 text-brand-700'">1</span>
            <span class="text-sm font-medium text-slate-800">Data Keperluan</span>
          </li>
          <li class="flex items-center gap-3 flex-shrink-0">
            <span class="grid h-8 w-8 place-items-center rounded-full border-2 font-semibold transition-all duration-300"
                  :class="step===2 ? 'border-brand-500 bg-brand-500 text-white shadow-yellow-glow' : 'border-brand-300 bg-brand-100 text-brand-700'">2</span>
            <span class="text-sm font-medium text-slate-800">Pihak Tujuan &amp; Dokumen</span>
          </li>
        </ol>
      </div>

      <!-- Form -->
      <form @submit.prevent="saveTamu" class="max-h-[calc(90vh-180px)] overflow-y-auto px-6 pb-6">
        <!-- STEP 1 -->
        <section x-show="step===1" x-transition.opacity class="mt-6 space-y-6">
          <!-- Informasi Pemohon -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700 animate-bounceIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Informasi Pemohon</h4>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-800">Nama Lengkap <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.nama" type="text" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="Nama lengkap">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Alamat Email <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.email" type="email" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="nama@email.com">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Nomor Handphone (WhatsApp) <span class="text-red-500">*</span></label>
                <div class="mt-1 flex rounded-xl border border-brand-400 bg-white shadow-sm focus-within:border-brand-600 focus-within:ring-2 focus-within:ring-brand-500 transition-all duration-300">
                  <span class="inline-flex items-center rounded-l-xl bg-brand-100 px-3 text-slate-700 select-none">+62</span>
                  <input required x-model="formTamu.no_hp" type="tel" class="w-full rounded-r-xl px-3 py-2 focus:outline-none" placeholder="81234567890">
                </div>
                <p class="mt-1 text-xs text-slate-600">Format: 812-3456-7890 (tanpa +62)</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Jumlah Peserta <span class="text-red-500">*</span></label>
                <input required x-model.number="formTamu.jumlah_peserta" type="number" min="1" max="50" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="cth: 10">
                <p class="mt-1 text-xs text-slate-600">Maksimal 50 orang per kunjungan</p>
              </div>
            </div>
          </div>

          <!-- Informasi Instansi/Organisasi -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700 animate-bounceIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Informasi Instansi/Organisasi</h4>
            </div>

            <label class="block text-sm font-medium text-slate-800">Instansi/Daerah Asal <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.instansi_kategori" value="opd" class="peer" required>
                <span class="text-sm text-slate-800">OPD</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.instansi_kategori" value="lembaga" class="peer">
                <span class="text-sm text-slate-800">Lembaga</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.instansi_kategori" value="perseorangan" class="peer">
                <span class="text-sm text-slate-800">Perseorangan</span>
              </label>
              <label class="group flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.instansi_kategori" value="ormas" class="peer">
                <span class="text-sm text-slate-800">Ormas</span>
              </label>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-800">Nama Instansi/Organisasi <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.instansi_nama" type="text" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="cth: Dinas Pendidikan">
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-800">Detail Keperluan <span class="text-red-500">*</span></label>
                <textarea required x-model="formTamu.keperluan" rows="3" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300" placeholder="Tuliskan keperluan kunjungan..."></textarea>
              </div>
            </div>
          </div>

          <!-- Jadwal -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700 animate-bounceIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Jadwal Kunjungan</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-800">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.tanggal_kunjungan" type="date" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-800">Waktu Kunjungan <span class="text-red-500">*</span></label>
                <input required x-model="formTamu.waktu_kunjungan" type="time" class="mt-1 w-full rounded-xl border border-brand-400 bg-white px-3 py-2 shadow-sm focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" @click="step=2" class="ripple rounded-xl bg-yellow-gradient px-5 py-2 font-semibold text-white shadow-yellow-glow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand-500 flex items-center gap-2">
              Lanjut
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
              </svg>
            </button>
          </div>
        </section>

        <!-- STEP 2 -->
        <section x-show="step===2" x-transition.opacity class="mt-6 space-y-6">
          <!-- Pihak Tujuan -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
              <div class="rounded-full bg-brand-500/20 p-2 text-brand-700 animate-bounceIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z" /><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm10 12H5V5h10v10z" clip-rule="evenodd"/></svg>
              </div>
              <h4 class="text-lg font-semibold text-slate-900">Pihak yang Dituju</h4>
            </div>

            <label class="block text-sm font-medium text-slate-800">Kategori Pihak yang Dituju <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="Pimpinan" required @change="formTamu.bertemu_sub=''; updateBertemuText()">
                <span class="text-sm text-slate-800">Pimpinan</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="AKD" @change="formTamu.bertemu_sub=''; updateBertemuText()">
                <span class="text-sm text-slate-800">AKD</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 cursor-pointer hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_kategori" value="Sekretariat" @change="formTamu.bertemu_sub=''; updateBertemuText()">
                <span class="text-sm text-slate-800">Sekretariat</span>
              </label>
            </div>

            <!-- List dinamis -->
            <div class="mt-4 space-y-3" x-show="formTamu.bertemu_kategori==='Pimpinan'">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_sub" value="Ketua DPRD" @change="updateBertemuText()" required>
                <span class="text-slate-800">Ketua DPRD</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_sub" value="Wakil ketua 1" @change="updateBertemuText()">
                <span class="text-slate-800">Wakil ketua 1</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_sub" value="Wakil ketua 2" @change="updateBertemuText()">
                <span class="text-slate-800">Wakil ketua 2</span>
              </label>
            </div>

            <div class="mt-4" x-show="formTamu.bertemu_kategori==='AKD'">
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                  <input type="radio" x-model="formTamu.bertemu_sub" value="Badan Kehormatan" @change="updateBertemuText()" required>
                  <span class="text-slate-800">Badan Kehormatan</span>
                </label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                  <input type="radio" x-model="formTamu.bertemu_sub" value="Badan Anggaran" @change="updateBertemuText()">
                  <span class="text-slate-800">Badan Anggaran</span>
                </label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                  <input type="radio" x-model="formTamu.bertemu_sub" value="Badan Pembentukan Peraturan Daerah" @change="updateBertemuText()">
                  <span class="text-slate-800">Badan Pembentukan Peraturan Daerah</span>
                </label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                  <input type="radio" x-model="formTamu.bertemu_sub" value="Badan Musyawarah" @change="updateBertemuText()">
                  <span class="text-slate-800">Badan Musyawarah</span>
                </label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                  <input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 1" @change="updateBertemuText()">
                  <span class="text-slate-800">Komisi 1</span>
                </label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                  <input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 2" @change="updateBertemuText()">
                  <span class="text-slate-800">Komisi 2</span>
                </label>
                <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                  <input type="radio" x-model="formTamu.bertemu_sub" value="Komisi 3" @change="updateBertemuText()">
                  <span class="text-slate-800">Komisi 3</span>
                </label>
              </div>
            </div>

            <div class="mt-4 space-y-3" x-show="formTamu.bertemu_kategori==='Sekretariat'">
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_sub" value="Sekretaris" @change="updateBertemuText()" required>
                <span class="text-slate-800">Sekretaris</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_sub" value="Bagian Umum dan Humas" @change="updateBertemuText()">
                <span class="text-slate-800">Bagian Umum dan Humas</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_sub" value="Bagian Keuangan" @change="updateBertemuText()">
                <span class="text-slate-800">Bagian Keuangan</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-brand-300 hover:ring-brand-500 transition-all duration-300">
                <input type="radio" x-model="formTamu.bertemu_sub" value="Persidangan dan Perundang-undangan" @change="updateBertemuText()">
                <span class="text-slate-800">Persidangan dan Perundang-undangan</span>
              </label>
            </div>

            <!-- Gabungan (readonly) -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-slate-800">Bertemu Dengan (otomatis)</label>
              <input x-model="formTamu.bertemu_dengan" type="text" readonly class="mt-1 w-full rounded-xl border border-brand-400 px-3 py-2 bg-brand-100 text-slate-700">
              <p class="text-xs text-slate-600 mt-1">Otomatis dari pilihan kategori & sub-kategori.</p>
            </div>
          </div>

          <!-- Status -->
          <div class="rounded-2xl border border-brand-300 bg-brand-50 p-5 shadow-sm">
            <label class="block text-sm font-medium text-slate-800">Status</label>
            <select x-model="formTamu.status_sekarang" class="mt-1 w-full rounded-xl border border-brand-400 px-3 py-2 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
              <option value="menunggu">Menunggu</option>
              <option value="ditolak">Ditolak</option>
              <option value="diterima">Diterima</option>
              <option value="selesai">Selesai</option>
            </select>
          </div>

          <div class="flex items-center justify-between">
            <button type="button" @click="step=1" class="rounded-xl px-4 py-2 text-slate-800 hover:bg-brand-50 transition-all duration-300 flex items-center gap-2">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
              </svg>
              Kembali
            </button>
            <div class="flex items-center gap-3">
              <button type="button" @click="showAddEditTamu=false" class="rounded-xl px-4 py-2 text-slate-700 bg-white hover:bg-brand-50 transition-all duration-300">Batal</button>
              <button type="submit" class="ripple rounded-xl bg-yellow-gradient px-5 py-2 font-semibold text-white shadow-yellow-glow hover:shadow-lift hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand-500 flex items-center gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Simpan
              </button>
            </div>
          </div>
        </section>
      </form>
    </div>
  </div>
@endsection