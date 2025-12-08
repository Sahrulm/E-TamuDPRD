@extends('layouts.admin')

@section('content')
  <!-- HERO -->
  <section class="pt-2 pb-2 rounded-2xl border border-brand-300 relative overflow-hidden animate-fadeUp bg-gradient-to-br from-brand-100 via-[#FFFDF5] to-brand-100 shadow-soft">
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full border border-brand-300/60 animate-floaty"></div>
    <div class="pointer-events-none absolute -right-20 -bottom-24 h-80 w-80 rounded-full border-4 border-brand-200/70 animate-floaty" style="animation-delay:.8s"></div>
    <div class="absolute inset-x-0 top-0 h-1 shimmer animate-shimmer"></div>
    <div class="px-5 py-5">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-3">
        <svg class="h-7 w-7 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        Panel Admin - Kelola Pengguna
      </h1>
      <p class="text-slate-700 mt-2 text-lg">Kelola akses pengguna sistem E-Tamu DPRD Kota Gorontalo.</p>
    </div>
  </section>

  <!-- ========= KELOLA PENGGUNA ========= -->
  <section>
    <div class="rounded-2xl border border-brand-300 bg-brand-50/80 backdrop-blur p-6 shadow-soft">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-4 justify-between mb-6">
        <h2 class="text-xl font-bold flex items-center gap-3 text-slate-900">
          <svg class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          Kelola Pengguna
        </h2>

        <div class="flex flex-wrap items-center gap-3 ml-auto mobile-stack">
          <span class="text-sm text-slate-700 whitespace-nowrap bg-white px-3 py-2 rounded-xl border border-brand-300">
            <span class="font-semibold text-brand-800" x-text="filteredUsers().length"></span> pengguna
          </span>

          <input type="text" placeholder="Cari nama pengguna…"
                 x-model.debounce.300ms="filterUser.q"
                 class="w-full md:w-64 rounded-xl border border-brand-400 px-4 py-2.5 text-sm bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300"
                 title="Cari Nama">

          <select x-model="filterUser.role"
                  class="w-full md:w-44 rounded-xl border border-brand-400 px-4 py-2.5 text-sm bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300"
                  title="Filter Role">
            <option value="">Semua Role</option>
            <option value="admin">Admin</option>
            <option value="resepsionis">Resepsionis</option>
            <option value="host">Host</option>
          </select>

          <button type="button"
                  class="w-full md:w-auto px-4 py-2.5 text-sm rounded-xl border border-brand-400 bg-white hover:bg-brand-100/80 hover:shadow-md transition-all duration-300 flex items-center gap-2"
                  @click="filterUser={role:'', q:''}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
            Reset
          </button>

          <button @click="openAddUser()"
                  class="ripple w-full md:w-auto rounded-xl bg-yellow-gradient px-5 py-2.5 text-white font-semibold shadow-yellow-glow hover:shadow-lift transition-all duration-300 flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            Tambah User
          </button>
        </div>
      </div>

      <!-- Tabel pengguna -->
      <div class="overflow-x-auto scrollbar-thin relative rounded-xl border border-brand-300 bg-white shadow-sm">
        <table class="min-w-[920px] w-full text-sm">
          <thead class="sticky top-0 z-20 bg-brand-200 shadow-sm">
            <tr class="text-left text-slate-800 font-semibold">
              <th class="py-4 px-6 border-b border-brand-300">Nama</th>
              <th class="py-4 px-6 border-b border-brand-300">Email</th>
              <th class="py-4 px-6 border-b border-brand-300">No. WhatsApp</th>
              <th class="py-4 px-6 border-b border-brand-300">Role</th>
              <th class="py-4 px-6 border-b border-brand-300">Status</th>
              <th class="py-4 px-6 border-b border-brand-300 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="align-top divide-y divide-brand-200">
            <template x-for="u in filteredUsers()" :key="u.id">
              <tr class="bg-white hover:bg-brand-50/50 transition-colors duration-300">
                <td class="py-4 px-6 font-medium text-slate-900">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-yellow-gradient flex items-center justify-center text-white text-xs font-bold shadow-yellow-glow">
                      <span x-text="(u.full_name || 'U').charAt(0).toUpperCase()"></span>
                    </div>
                    <div>
                      <span x-text="u.full_name || '—'" class="block"></span>
                      <span class="text-xs text-slate-500" x-text="u.id" title="ID User"></span>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-6 text-slate-800 font-medium">
                  <span x-text="u.email" class="block"></span>
                  <a x-show="u.no_wa" :href="'https://wa.me/' + u.no_wa" target="_blank" 
                     class="text-xs text-brand-600 hover:text-brand-800 inline-flex items-center gap-1 mt-1">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    Hubungi via WhatsApp
                  </a>
                </td>
                <td class="py-4 px-6">
                  <div x-show="u.no_wa">
                    <a :href="'https://wa.me/' + u.no_wa" target="_blank" 
                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors duration-300">
                      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16.75 13.96c.25.13.41.2.46.3.06.11.04.61-.21 1.18-.2.56-1.24 1.1-1.7 1.12-.46.02-.47.36-2.96-.73-2.49-1.09-3.99-3.75-4.11-3.92-.12-.17-.96-1.38-.92-2.61.05-1.22.69-1.8.95-2.04.24-.26.51-.29.68-.26h.47c.15 0 .36-.06.55.45l.69 1.87c.06.13.1.28.01.44l-.27.41-.39.42c-.12.12-.26.25-.12.5.12.26.62 1.09 1.32 1.78.91.88 1.71 1.17 1.95 1.3.24.14.39.12.54-.04l.81-.94c.19-.25.35-.19.58-.11l1.67.88M12 2a10 10 0 0 1 10 10 10 10 0 0 1-10 10c-1.97 0-3.8-.57-5.35-1.55L2 22l1.55-4.65A9.969 9.969 0 0 1 2 12 10 10 0 0 1 12 2m0 2a8 8 0 0 0-8 8c0 1.72.54 3.31 1.46 4.61L4.5 19.5l2.89-.96A7.95 7.95 0 0 0 12 20a8 8 0 0 0 8-8 8 8 0 0 0-8-8z"/>
                      </svg>
                      <span x-text="u.no_wa"></span>
                    </a>
                  </div>
                  <div x-show="!u.no_wa" class="text-slate-500 text-sm italic">
                    Tidak tersedia
                  </div>
                </td>
                <td class="py-4 px-6">
                  <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-medium ring-1 whitespace-nowrap transition-all duration-300" 
                        :class="{
                          'bg-purple-50 ring-purple-200 text-purple-700': u.role === 'admin',
                          'bg-blue-50 ring-blue-200 text-blue-700': u.role === 'resepsionis', 
                          'bg-green-50 ring-green-200 text-green-700': u.role === 'host'
                        }">
                    <span x-text="u.role.charAt(0).toUpperCase() + u.role.slice(1)"></span>
                  </span>
                </td>
                <td class="py-4 px-6">
                  <div class="inline-flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full" 
                         :class="u.is_active ? 'bg-green-500' : 'bg-red-500'"></div>
                    <span x-text="u.is_active ? 'Aktif' : 'Nonaktif'" 
                          :class="u.is_active ? 'text-green-700' : 'text-red-700'" 
                          class="text-sm font-medium"></span>
                  </div>
                </td>
                <td class="py-4 px-6">
                  <div class="flex flex-wrap gap-2 justify-center">
                    <button @click="editUser(u)" 
                            class="ripple rounded-xl bg-brand-100 px-4 py-2.5 text-xs text-slate-800 hover:bg-brand-200 hover:text-brand-800 hover:shadow-md transition-all duration-300 flex items-center gap-2">
                      <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                      </svg>
                      Edit
                    </button>
                    <button @click="confirmDelete('user', u)" 
                            class="ripple rounded-xl bg-rose-100 px-4 py-2.5 text-xs text-rose-700 hover:bg-rose-200 hover:shadow-md transition-all duration-300 flex items-center gap-2">
                      <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                      </svg>
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </template>
            <tr x-show="filteredUsers().length===0">
              <td colspan="6" class="py-16 px-6 text-center text-slate-600 bg-white rounded-b-xl">
                <div class="flex flex-col items-center gap-4">
                  <svg class="h-16 w-16 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                  <div>
                    <p class="text-slate-700 text-lg font-medium mb-1">Tidak ada pengguna</p>
                    <p class="text-slate-600 text-sm">Belum ada pengguna yang sesuai dengan filter yang dipilih.</p>
                  </div>
                  <button @click="filterUser={role:'', q:''}" 
                          class="text-brand-600 hover:text-brand-700 text-sm font-medium mt-2 px-4 py-2 rounded-lg bg-brand-50 hover:bg-brand-100 transition-colors duration-300">
                    Reset filter
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Info jumlah data -->
      <div class="flex items-center justify-between mt-6 text-sm text-slate-700" x-show="filteredUsers().length > 0">
        <div class="bg-white px-4 py-3 rounded-xl border border-brand-300">
          Total <span class="font-semibold text-brand-800" x-text="filteredUsers().length"></span> pengguna
        </div>
        <div class="text-slate-600 text-sm">
          <span class="font-medium text-brand-700" x-text="users.length"></span> pengguna terdaftar dalam sistem
        </div>
      </div>
    </div>
  </section>

  <!-- ============== MODAL: Tambah/Edit User ============== -->
  <div x-cloak x-show="showAddUser" x-transition.opacity 
       class="fixed inset-0 z-50 flex items-center justify-center p-4"
       style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background: rgba(0, 0, 0, 0.3);">
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-md bg-white rounded-2xl p-6 shadow-2xl animate-scaleIn ring-1 ring-brand-300 z-10" @click.stop>
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-bold flex items-center gap-3 text-slate-900">
          <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          <span x-text="editingUser ? 'Edit User' : 'Tambah User'"></span>
        </h3>
        <button class="ripple rounded-lg p-2 text-slate-500 hover:bg-brand-100/80 transition-all duration-300" @click="showAddUser=false">
          <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
        </button>
      </div>

      <form class="mt-6 space-y-5" @submit.prevent="saveUser">
        <div>
          <label class="block text-sm font-medium text-slate-800 mb-2">Nama Lengkap</label>
          <input type="text" x-model="formUser.full_name" placeholder="Masukkan nama lengkap" 
                 class="w-full rounded-xl border border-brand-400 px-4 py-3 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-800 mb-2">Alamat Email <span class="text-red-500">*</span></label>
          <input type="email" x-model="formUser.email" placeholder="cth: user@dprdgorontalo.com" required 
                 class="w-full rounded-xl border border-brand-400 px-4 py-3 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-800 mb-2">Nomor WhatsApp</label>
          <div class="relative">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
              <svg class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16.75 13.96c.25.13.41.2.46.3.06.11.04.61-.21 1.18-.2.56-1.24 1.1-1.7 1.12-.46.02-.47.36-2.96-.73-2.49-1.09-3.99-3.75-4.11-3.92-.12-.17-.96-1.38-.92-2.61.05-1.22.69-1.8.95-2.04.24-.26.51-.29.68-.26h.47c.15 0 .36-.06.55.45l.69 1.87c.06.13.1.28.01.44l-.27.41-.39.42c-.12.12-.26.25-.12.5.12.26.62 1.09 1.32 1.78.91.88 1.71 1.17 1.95 1.3.24.14.39.12.54-.04l.81-.94c.19-.25.35-.19.58-.11l1.67.88M12 2a10 10 0 0 1 10 10 10 10 0 0 1-10 10c-1.97 0-3.8-.57-5.35-1.55L2 22l1.55-4.65A9.969 9.969 0 0 1 2 12 10 10 0 0 1 12 2m0 2a8 8 0 0 0-8 8c0 1.72.54 3.31 1.46 4.61L4.5 19.5l2.89-.96A7.95 7.95 0 0 0 12 20a8 8 0 0 0 8-8 8 8 0 0 0-8-8z"/>
              </svg>
              <span class="text-sm text-slate-500">+62</span>
            </div>
            <input type="tel" x-model="formUser.no_wa" placeholder="81234567890" 
                   class="w-full rounded-xl border border-brand-400 px-4 py-3 pl-20 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
          </div>
          <p class="mt-2 text-xs text-slate-600">Contoh: 81234567890 (tanpa +62)</p>
        </div>
        
        <div x-show="!editingUser">
          <label class="block text-sm font-medium text-slate-800 mb-2">Password <span class="text-red-500">*</span></label>
          <input type="password"
                 x-model="formUser.password"
                 placeholder="Minimal 8 karakter"
                 :required="!editingUser"
                 minlength="8"
                 class="w-full rounded-xl border border-brand-400 px-4 py-3 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
          <p class="mt-2 text-xs text-slate-600">Password minimal <span class="font-semibold text-brand-700">8</span> karakter.</p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-800 mb-2">Role <span class="text-red-500">*</span></label>
          <select x-model="formUser.role" required 
                  class="w-full rounded-xl border border-brand-400 px-4 py-3 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
            <option value="admin">Admin</option>
            <option value="resepsionis">Resepsionis</option>
            <option value="host">Host</option>
          </select>
        </div>
        
        <div x-show="editingUser">
          <label class="block text-sm font-medium text-slate-800 mb-2">Status Akun</label>
          <div class="flex items-center gap-4">
            <label class="inline-flex items-center">
              <input type="radio" x-model="formUser.is_active" :value="true" 
                     class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-brand-400">
              <span class="ml-2 text-sm text-slate-800">Aktif</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" x-model="formUser.is_active" :value="false" 
                     class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-brand-400">
              <span class="ml-2 text-sm text-slate-800">Nonaktif</span>
            </label>
          </div>
        </div>
        
        <div x-show="editingUser">
          <label class="block text-sm font-medium text-slate-800 mb-2">Password Baru</label>
          <input type="password"
                 x-model="formUser.new_password"
                 placeholder="Kosongkan jika tidak ingin mengubah"
                 class="w-full rounded-xl border border-brand-400 px-4 py-3 bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
          <p class="mt-2 text-xs text-slate-600">Isi hanya jika ingin mengubah password.</p>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <button type="button" 
                  @click="showAddUser=false" 
                  class="rounded-xl border border-brand-400 px-5 py-2.5 font-semibold text-slate-800 bg-white hover:bg-brand-100/80 transition-all duration-300">
            Batal
          </button>
          <button type="submit" 
                  class="ripple rounded-xl bg-yellow-gradient px-5 py-2.5 text-white font-semibold shadow-yellow-glow hover:shadow-lift transition-all duration-300 flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path x-show="!editingUser" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
              <path x-show="editingUser" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
            </svg>
            <span x-text="editingUser ? 'Update' : 'Simpan'"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection