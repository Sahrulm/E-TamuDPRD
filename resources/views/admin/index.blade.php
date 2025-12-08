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
          <path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1c.6 0 1-.4 1-1s-.4-1-1-1h-1V4c0-1.1-.9-2-2-2H6C4.9 2 4 2.9 4 4v7H3c-.6 0-1 .4-1 1s.4 1 1 1zM6 4h12v7H6V4z"/>
        </svg>
        Panel Admin
      </h1>
      <p class="text-slate-700 mt-2 text-lg">Kelola pengguna & seluruh data tamu DPRD Kota Gorontalo.</p>
    </div>
  </section>

  <!-- ========= DASHBOARD ========= -->
  <section>
    <div class="rounded-2xl border border-brand-300 bg-brand-50/80 backdrop-blur p-6 shadow-soft space-y-6">
      <!-- Toolbar kecil: rentang hari -->
      <div class="flex flex-wrap items-center gap-3 justify-between">
        <h2 class="text-xl font-bold flex items-center gap-3 text-slate-900">
          <svg class="h-6 w-6 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1c.6 0 1-.4 1-1s-.4-1-1-1h-1V4c0-1.1-.9-2-2-2H6C4.9 2 4 2.9 4 4v7H3c-.6 0-1 .4-1 1s.4 1 1 1zM6 4h12v7H6V4z"/>
          </svg>
          Dashboard
        </h2>
        <div class="flex items-center gap-3">
          <label class="text-sm text-slate-700 font-medium">Rentang Waktu:</label>
          <select x-model.number="dashboardRange"
                  @change="refreshChart()"
                  class="rounded-xl border border-brand-400 px-4 py-2.5 text-sm bg-white focus:border-brand-600 focus:ring-brand-500 focus:ring-2 transition-all duration-300">
            <option :value="7">7 hari</option>
            <option :value="14">14 hari</option>
            <option :value="30">30 hari</option>
          </select>
        </div>
      </div>

      <!-- Kartu Metrik -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Tamu -->
        <div class="rounded-2xl border border-brand-300 bg-gradient-to-br from-amber-500 to-amber-600 p-6 shadow-lg text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
          <div class="absolute top-4 right-4 opacity-20 group-hover:opacity-30 transition-opacity duration-300">
            <svg class="h-12 w-12" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold opacity-90">Total Tamu</div>
          <div class="mt-2 text-3xl font-bold" x-text="statusCounts.menunggu + statusCounts.diterima + statusCounts.ditolak + statusCounts.selesai"></div>
          <div class="mt-3 text-xs flex items-center gap-2 opacity-90">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            <span>Total pengunjung terdaftar</span>
          </div>
        </div>

        <!-- Total Tamu Selesai -->
        <div class="rounded-2xl border border-brand-300 bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 shadow-lg text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
          <div class="absolute top-4 right-4 opacity-20 group-hover:opacity-30 transition-opacity duration-300">
            <svg class="h-12 w-12" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold opacity-90">Kunjungan Selesai</div>
          <div class="mt-2 text-3xl font-bold" x-text="metrics.totalSelesai"></div>
          <div class="mt-3 text-xs flex items-center gap-2 opacity-90">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            <span>Kunjungan telah selesai</span>
          </div>
        </div>

        <!-- Total User -->
        <div class="rounded-2xl border border-brand-300 bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
          <div class="absolute top-4 right-4 opacity-20 group-hover:opacity-30 transition-opacity duration-300">
            <svg class="h-12 w-12" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold opacity-90">Total Pengguna</div>
          <div class="mt-2 text-3xl font-bold" x-text="metrics.totalUser"></div>
          <div class="mt-3 text-xs flex items-center gap-2 opacity-90">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span>Jumlah user sistem</span>
          </div>
        </div>

        <!-- Kunjungan Hari Ini -->
        <div class="rounded-2xl border border-brand-300 bg-gradient-to-br from-purple-500 to-purple-600 p-6 shadow-lg text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
          <div class="absolute top-4 right-4 opacity-20 group-hover:opacity-30 transition-opacity duration-300">
            <svg class="h-12 w-12" viewBox="0 0 24 24" fill="currentColor">
              <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold opacity-90">Kunjungan Hari Ini</div>
          <div class="mt-2 text-3xl font-bold" x-text="metrics.todayVisits"></div>
          <div class="mt-3 text-xs flex items-center gap-2 opacity-90">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Kunjungan hari ini</span>
          </div>
        </div>
      </div>

      <!-- Grafik dan Statistik Grid -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Grafik Perbandingan Tamu per Hari -->
        <div class="xl:col-span-2 rounded-2xl border border-brand-300 bg-white p-6 shadow-sm">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
              <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1c.6 0 1-.4 1-1s-.4-1-1-1h-1V4c0-1.1-.9-2-2-2H6C4.9 2 4 2.9 4 4v7H3c-.6 0-1 .4-1 1s.4 1 1 1zM6 4h12v7H6V4z"/>
              </svg>
              Statistik Kunjungan per Hari
            </h3>
            <span class="text-sm text-slate-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
              <span x-text="dashboardRange"></span> hari terakhir
            </span>
          </div>
          <div class="h-80">
            <canvas id="dashboardChart" class="h-full w-full"></canvas>
          </div>
        </div>

        <!-- Statistik Status Tamu -->
        <div class="rounded-2xl border border-brand-300 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2 mb-6">
            <svg class="h-5 w-5 text-brand-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            Status Kunjungan
          </h3>
          <div class="space-y-4">
            <!-- Menunggu -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-amber-50 border border-amber-200 hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                  <svg class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-amber-800">Menunggu</div>
                  <div class="text-2xl font-bold text-amber-900" x-text="statusCounts.menunggu"></div>
                </div>
              </div>
            </div>

            <!-- Diterima -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                  <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-emerald-800">Diterima</div>
                  <div class="text-2xl font-bold text-emerald-900" x-text="statusCounts.diterima"></div>
                </div>
              </div>
            </div>

            <!-- Ditolak -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200 hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                  <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-rose-800">Ditolak</div>
                  <div class="text-2xl font-bold text-rose-900" x-text="statusCounts.ditolak"></div>
                </div>
              </div>
            </div>

            <!-- Selesai -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200 hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
                  <svg class="h-5 w-5 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-slate-800">Selesai</div>
                  <div class="text-2xl font-bold text-slate-900" x-text="statusCounts.selesai"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Keseluruhan -->
          <div class="mt-6 p-4 rounded-xl bg-brand-50 border border-brand-200">
            <div class="text-center">
              <div class="text-sm font-medium text-brand-800">Total Semua Status</div>
              <div class="text-2xl font-bold text-brand-900" x-text="Object.values(statusCounts).reduce((a, b) => a + b, 0)"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SCRIPT UNTUK CHART YANG TERINTEGRASI DENGAN ALPINE -->
  <script>
    // Global variable untuk chart instance
    window.dashboardChartInstance = null;
    window.currentChartRange = 7;

    // Fungsi untuk membuat atau update chart
    window.updateChartWithData = function(tamuData, range = 7) {
        try {
            console.log('🔄 Updating chart with real data...');
            console.log('Range:', range, 'Data points:', tamuData?.length || 0);

            const canvas = document.getElementById('dashboardChart');
            if (!canvas) {
                console.error('❌ Canvas not found!');
                return;
            }

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('❌ Cannot get canvas context');
                return;
            }

            // Hancurkan chart lama jika ada
            if (window.dashboardChartInstance) {
                window.dashboardChartInstance.destroy();
                window.dashboardChartInstance = null;
            }

            // Simpan range saat ini
            window.currentChartRange = range;

            // Siapkan data chart dari data tamu
            const chartData = prepareChartDataFromTamu(tamuData, range);
            console.log('📊 Chart data prepared:', chartData);

            // Buat chart baru
            window.dashboardChartInstance = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            grid: { display: false },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });

            console.log('✅ Chart updated successfully with range:', range);
            
        } catch (error) {
            console.error('💥 Error updating chart:', error);
        }
    };

    // Fungsi untuk menyiapkan data chart dari data tamu
    function prepareChartDataFromTamu(tamuData, range) {
        try {
            console.log('🔄 Preparing chart data for', range, 'days...');
            
            if (!tamuData || !Array.isArray(tamuData) || tamuData.length === 0) {
                console.warn('⚠️ No tamu data available, using fallback data');
                return getFallbackChartData(range);
            }

            const labels = [];
            const datasets = [
                { 
                    label: 'Menunggu', 
                    data: [], 
                    backgroundColor: '#f59e0b',
                    borderColor: '#f59e0b',
                    borderWidth: 1
                },
                { 
                    label: 'Diterima', 
                    data: [], 
                    backgroundColor: '#10b981',
                    borderColor: '#10b981',
                    borderWidth: 1
                },
                { 
                    label: 'Ditolak', 
                    data: [], 
                    backgroundColor: '#ef4444',
                    borderColor: '#ef4444',
                    borderWidth: 1
                },
                { 
                    label: 'Selesai', 
                    data: [], 
                    backgroundColor: '#64748b',
                    borderColor: '#64748b',
                    borderWidth: 1
                }
            ];

            // Generate labels untuk hari-hari terakhir
            const today = new Date();
            
            for (let i = range - 1; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                const dateStr = date.toISOString().split('T')[0];
                
                // Format label: "01 Des"
                const label = new Intl.DateTimeFormat('id-ID', { 
                    day: '2-digit', 
                    month: 'short' 
                }).format(date);
                
                labels.push(label);
                
                // Filter data untuk hari ini
                const dayData = tamuData.filter(t => {
                    if (!t.tanggal_kunjungan) {
                        return false;
                    }
                    return t.tanggal_kunjungan === dateStr;
                });

                // Hitung berdasarkan status
                const menunggu = dayData.filter(t => t.status_sekarang === 'menunggu').length;
                const diterima = dayData.filter(t => t.status_sekarang === 'diterima').length;
                const ditolak = dayData.filter(t => t.status_sekarang === 'ditolak').length;
                const selesai = dayData.filter(t => t.status_sekarang === 'selesai').length;

                datasets[0].data.push(menunggu);
                datasets[1].data.push(diterima);
                datasets[2].data.push(ditolak);
                datasets[3].data.push(selesai);

                // Debug log untuk beberapa hari pertama
                if (i >= range - 3) {
                    console.log(`📊 ${dateStr} (${label}): ${dayData.length} kunjungan - ${menunggu}M, ${diterima}D, ${ditolak}T, ${selesai}S`);
                }
            }

            const result = {
                labels: labels,
                datasets: datasets
            };
            
            console.log('✅ Chart data prepared for', range, 'days');
            console.log('Labels:', labels);
            console.log('Data totals:', {
                menunggu: datasets[0].data.reduce((a, b) => a + b, 0),
                diterima: datasets[1].data.reduce((a, b) => a + b, 0),
                ditolak: datasets[2].data.reduce((a, b) => a + b, 0),
                selesai: datasets[3].data.reduce((a, b) => a + b, 0)
            });
            
            return result;
            
        } catch (error) {
            console.error('💥 Error preparing chart data:', error);
            return getFallbackChartData(range);
        }
    }

    // Fallback data jika ada error
    function getFallbackChartData(range) {
        console.log('🔄 Using fallback chart data for', range, 'days');
        
        const labels = [];
        const today = new Date();
        
        for (let i = range - 1; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(date.getDate() - i);
            const label = new Intl.DateTimeFormat('id-ID', { 
                day: '2-digit', 
                month: 'short' 
            }).format(date);
            labels.push(label);
        }

        return {
            labels: labels,
            datasets: [
                { label: 'Menunggu', data: Array(range).fill(0), backgroundColor: '#f59e0b' },
                { label: 'Diterima', data: Array(range).fill(0), backgroundColor: '#10b981' },
                { label: 'Ditolak', data: Array(range).fill(0), backgroundColor: '#ef4444' },
                { label: 'Selesai', data: Array(range).fill(0), backgroundColor: '#64748b' }
            ]
        };
    }

    // Fungsi untuk refresh chart dari Alpine - DIPERBAIKI
    window.refreshDashboardChart = function(range = 7) {
        console.log('🔄 REFRESH CHART CALLED with range:', range);
        
        // Tunggu sebentar untuk memastikan Alpine data sudah terupdate
        setTimeout(() => {
            const alpineComponent = document.querySelector('[x-data]');
            if (alpineComponent && Alpine.$data(alpineComponent)) {
                const alpineData = Alpine.$data(alpineComponent);
                console.log('🦄 Alpine data found, updating chart with range:', range);
                
                if (alpineData.tamu && Array.isArray(alpineData.tamu)) {
                    console.log('👥 Using existing tamu data:', alpineData.tamu.length, 'records');
                    window.updateChartWithData(alpineData.tamu, range);
                } else {
                    console.warn('⚠️ No tamu data in Alpine, waiting for data load...');
                    // Jika tidak ada data, tunggu dan coba lagi
                    setTimeout(() => {
                        if (alpineData.tamu && Array.isArray(alpineData.tamu)) {
                            window.updateChartWithData(alpineData.tamu, range);
                        }
                    }, 500);
                }
            } else {
                console.warn('⚠️ Alpine component not found during refresh');
            }
        }, 300);
    };

    // Fungsi untuk reload data dari server dengan range baru - FUNGSI BARU
    window.reloadChartDataWithRange = function(range) {
        console.log('🔄 RELOADING DATA from server with range:', range);
        
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && Alpine.$data(alpineComponent)) {
            const alpineData = Alpine.$data(alpineComponent);
            
            // Simpan range yang dipilih
            alpineData.dashboardRange = range;
            
            // Panggil fungsi refreshChart Alpine yang akan reload data dari server
            if (typeof alpineData.refreshChart === 'function') {
                console.log('📡 Calling Alpine refreshChart to reload server data...');
                alpineData.refreshChart();
            } else {
                console.warn('⚠️ Alpine refreshChart function not found');
                // Fallback: update chart dengan data yang ada
                if (alpineData.tamu && Array.isArray(alpineData.tamu)) {
                    window.updateChartWithData(alpineData.tamu, range);
                }
            }
        }
    };

    // Initialize chart ketika DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('📊 DOM Ready - Initializing dashboard chart...');
        
        // Tunggu Alpine.js selesai load dan data tersedia
        const initChart = function() {
            const alpineComponent = document.querySelector('[x-data]');
            if (alpineComponent && Alpine.$data(alpineComponent)) {
                const alpineData = Alpine.$data(alpineComponent);
                console.log('🦄 Alpine data loaded, initializing chart...');
                
                // Simpan reference ke Alpine component untuk akses global
                window.alpineApp = alpineData;
                
                // Override fungsi refreshChart Alpine untuk memanggil fungsi kita
                const originalRefreshChart = alpineData.refreshChart;
                if (typeof originalRefreshChart === 'function') {
                    alpineData.refreshChart = function() {
                        console.log('🎯 Alpine refreshChart called, reloading data from server...');
                        // Panggil fungsi original dulu
                        originalRefreshChart.call(alpineData);
                        
                        // Tunggu data selesai reload, lalu update chart
                        setTimeout(() => {
                            console.log('📊 Server data reloaded, updating chart...');
                            if (alpineData.tamu && Array.isArray(alpineData.tamu)) {
                                window.updateChartWithData(alpineData.tamu, alpineData.dashboardRange || 7);
                            }
                        }, 800);
                    };
                }
                
                // Update chart dengan data dari Alpine
                if (alpineData.tamu && Array.isArray(alpineData.tamu)) {
                    console.log('👥 Tamu data available:', alpineData.tamu.length, 'records');
                    const range = alpineData.dashboardRange || 7;
                    window.updateChartWithData(alpineData.tamu, range);
                } else {
                    console.log('⏳ Waiting for tamu data to load...');
                    // Coba lagi setelah beberapa saat
                    setTimeout(() => {
                        if (alpineData.tamu && Array.isArray(alpineData.tamu)) {
                            const range = alpineData.dashboardRange || 7;
                            window.updateChartWithData(alpineData.tamu, range);
                        }
                    }, 1000);
                }
            } else {
                console.warn('⚠️ Alpine component not found, retrying...');
                // Coba lagi setelah beberapa saat
                setTimeout(initChart, 1000);
            }
        };

        // Mulai inisialisasi
        setTimeout(initChart, 500);
    });

    // Event listener untuk perubahan range - FUNGSI BARU
    document.addEventListener('change', function(event) {
        if (event.target.matches('select[x-model.number="dashboardRange"]')) {
            const range = parseInt(event.target.value);
            console.log('🎯 Range changed to:', range);
            
            // Panggil fungsi untuk reload data dengan range baru
            window.reloadChartDataWithRange(range);
        }
    });

    // Debug function untuk testing
    window.debugChart = function() {
        console.log('🔍 Chart Debug Info:');
        console.log('Chart instance:', window.dashboardChartInstance);
        console.log('Current range:', window.currentChartRange);
        console.log('Canvas:', document.getElementById('dashboardChart'));
        console.log('Alpine data:', window.alpineApp);
        
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && Alpine.$data(alpineComponent)) {
            const alpineData = Alpine.$data(alpineComponent);
            console.log('Tamu data length:', alpineData.tamu?.length || 0);
            console.log('Current range in Alpine:', alpineData.dashboardRange);
            console.log('Metrics:', alpineData.metrics);
            console.log('Status counts:', alpineData.statusCounts);
            
            // Tampilkan sample data
            if (alpineData.tamu && alpineData.tamu.length > 0) {
                console.log('Sample tamu data:', alpineData.tamu.slice(0, 3));
            }
        }
    };

    // Force refresh function untuk testing
    window.forceRefreshChart = function(range = null) {
        const actualRange = range || window.currentChartRange || 7;
        console.log('🔧 Force refreshing chart with range:', actualRange);
        window.refreshDashboardChart(actualRange);
    };
  </script>
@endsection