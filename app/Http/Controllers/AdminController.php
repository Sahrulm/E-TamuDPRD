<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\KategoriPihak;
use App\Models\StatusKunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /* ================= View ================= */
    public function index()
    {
        // return blade admin (file blade Anda)
        return view('admin.index'); // ganti 'admin' sesuai nama file blade Anda
    }

    /* ================= Users ================= */
    public function usersIndex(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function($q) use ($request){
                $s = $request->string('search');
                $q->where(fn($w)=>$w->where('email','like',"%{$s}%")
                                    ->orWhere('full_name','like',"%{$s}%"));
            })
            ->orderByDesc('created_at')
            ->get(['id','full_name','email','role','is_active']);

        return response()->json($users);
    }

    public function usersStore(Request $request)
    {
        $data = $request->validate([
            'email'     => ['required','email','max:200','unique:users,email'],
            'password'  => ['required','string','min:8'],
            'role'      => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_HOST, User::ROLE_RESEPSIONIS])],
            'full_name' => ['nullable','string','max:200'],
            'is_active' => ['sometimes','boolean'],
        ]);

        $user = User::create([
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'full_name' => $data['full_name'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json($user, 201);
    }

    public function usersUpdate(Request $request, User $user)
    {
        $data = $request->validate([
            'email'     => ['required','email','max:200', Rule::unique('users','email')->ignore($user->id)],
            'password'  => ['nullable','string','min:8'],
            'role'      => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_HOST, User::ROLE_RESEPSIONIS])],
            'full_name' => ['nullable','string','max:200'],
            'is_active' => ['sometimes','boolean'],
        ]);

        $payload = [
            'email'     => $data['email'],
            'role'      => $data['role'],
            'full_name' => $data['full_name'] ?? $user->full_name,
            'is_active' => $data['is_active'] ?? $user->is_active,
        ];
        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);
        return response()->json($user);
    }

    public function usersDestroy(User $user)
    {
        $user->delete();
        return response()->json(['deleted'=>true]);
    }

    /* ================= Kunjungan (flatten untuk UI) ================= */
    public function kunjunganIndex(Request $request)
    {
        $rows = Kunjungan::query()
            ->with(['tamu','kategoriPihak','host'])
            ->when($request->filled('status'), fn($q)=>$q->where('status_sekarang',$request->string('status')))
            ->orderByDesc('tanggal_kunjungan')
            ->orderBy('waktu_kunjungan')
            ->get();

        // Flatten: gabung data tamu + kunjungan + nama host (bertemu_dengan)
        $mapped = $rows->map(function(Kunjungan $k){
            $hostName = $k->host?->full_name ?: ($k->host?->email);
            return [
                'id'                => $k->id,
                'nama'              => $k->tamu->nama ?? '—',
                'email'             => $k->tamu->email ?? '—',
                'no_hp'             => $k->tamu->no_hp ?? '—',
                'jumlah_peserta'    => $k->jumlah_peserta,
                'instansi_kategori' => $k->tamu->instansi_kategori ?? null,
                'instansi_nama'     => $k->tamu->instansi_nama ?? null,
                'tanggal_kunjungan' => optional($k->tanggal_kunjungan)->format('Y-m-d'),
                'waktu_kunjungan'   => $k->waktu_kunjungan,
                'bertemu_dengan'    => $hostName ?: '—', // hanya tampil, tidak disimpan
                'dokumen'           => $k->dokumen ? asset('storage/'.$k->dokumen) : null,
                'status_sekarang'   => $k->status_sekarang,
                'host_id'           => $k->host_id,
                'tamu_id'           => $k->tamu_id,
            ];
        });

        return response()->json($mapped);
    }

    public function kunjunganStore(Request $request)
    {
        // validasi gabungan (tamu + kunjungan). 'bertemu_dengan' diabaikan (bukan kolom)
        $data = $request->validate([
            // tamu baru selalu dibuat dari form UI ini
            'nama'               => ['required','string','max:200'],
            'email'              => ['required','email','max:200'],
            'no_hp'              => ['required','string','max:50'],
            'instansi_kategori'  => ['nullable','string','max:100'],
            'instansi_nama'      => ['nullable','string','max:200'],

            'jumlah_peserta'     => ['nullable','integer','min:1','max:1000'],
            'tanggal_kunjungan'  => ['required','date'],
            'waktu_kunjungan'    => ['required','date_format:H:i'],
            'status_sekarang'    => ['nullable', Rule::in(['menunggu','diterima','ditolak','selesai'])],
            'host_id'            => ['nullable','integer','exists:users,id'],

            'dokumen'            => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
            'keperluan'          => ['sometimes','nullable','string','max:500'],
            'bertemu_dengan'     => ['sometimes','nullable','string','max:200'], // hanya lewat, tidak disimpan
        ]);

        // Buat tamu
        $tamu = Tamu::create([
            'nama'               => $data['nama'],
            'email'              => $data['email'],
            'no_hp'              => $data['no_hp'],
            'instansi_kategori'  => $data['instansi_kategori'] ?? null,
            'instansi_nama'      => $data['instansi_nama'] ?? null,
        ]);

        // Dokumen
        $path = null;
        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('kunjungan', 'public');
        }

        // Buat kunjungan
        $kunjungan = Kunjungan::create([
            'tamu_id'           => $tamu->id,
            'kategori_pihak_id' => null, // tidak muncul di UI, biarkan null
            'jumlah_peserta'    => $data['jumlah_peserta'] ?? 1,
            'keperluan'         => $data['keperluan'] ?? null,
            'tanggal_kunjungan' => $data['tanggal_kunjungan'],
            'waktu_kunjungan'   => $data['waktu_kunjungan'],
            'status_sekarang'   => $data['status_sekarang'] ?? 'menunggu',
            'dokumen'           => $path,
            'host_id'           => $data['host_id'] ?? null,
        ]);

        // Riwayat status awal
        StatusKunjungan::create([
            'kunjungan_id'       => $kunjungan->id,
            'old_status'         => null,
            'new_status'         => $kunjungan->status_sekarang,
            'changed_by_user_id' => null, // belum pakai auth
            'note'               => null,
            'changed_at'         => now(),
        ]);

        return response()->json(['created'=>true]);
    }

    public function kunjunganUpdate(Request $request, Kunjungan $kunjungan)
    {
        // Update Kunjungan + (opsional) data tamu terkait
        $data = $request->validate([
            // tamu (opsional)
            'nama'               => ['sometimes','required','string','max:200'],
            'email'              => ['sometimes','required','email','max:200'],
            'no_hp'              => ['sometimes','required','string','max:50'],
            'instansi_kategori'  => ['sometimes','nullable','string','max:100'],
            'instansi_nama'      => ['sometimes','nullable','string','max:200'],

            'jumlah_peserta'     => ['sometimes','nullable','integer','min:1','max:1000'],
            'tanggal_kunjungan'  => ['sometimes','required','date'],
            'waktu_kunjungan'    => ['sometimes','required','date_format:H:i'],
            'status_sekarang'    => ['sometimes','nullable', Rule::in(['menunggu','diterima','ditolak','selesai'])],
            'host_id'            => ['sometimes','nullable','integer','exists:users,id'],

            'dokumen'            => ['sometimes','nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
            'keperluan'          => ['sometimes','nullable','string','max:500'],
            'bertemu_dengan'     => ['sometimes','nullable','string','max:200'], // diabaikan
        ]);

        // dokumen baru?
        if ($request->hasFile('dokumen')) {
            if ($kunjungan->dokumen) {
                Storage::disk('public')->delete($kunjungan->dokumen);
            }
            $kunjungan->dokumen = $request->file('dokumen')->store('kunjungan', 'public');
        }

        // update kunjungan
        $fill = [];
        foreach (['jumlah_peserta','keperluan','tanggal_kunjungan','waktu_kunjungan','host_id'] as $f) {
            if ($request->exists($f)) $fill[$f] = $data[$f] ?? null;
        }
        $kunjungan->fill($fill)->save();

        // ganti status?
        if ($request->exists('status_sekarang') && $data['status_sekarang'] !== $kunjungan->status_sekarang) {
            $old = $kunjungan->status_sekarang;
            $kunjungan->status_sekarang = $data['status_sekarang'];
            $kunjungan->save();

            StatusKunjungan::create([
                'kunjungan_id'       => $kunjungan->id,
                'old_status'         => $old,
                'new_status'         => $kunjungan->status_sekarang,
                'changed_by_user_id' => null,
                'note'               => null,
                'changed_at'         => now(),
            ]);
        }

        // update tamu?
        if ($kunjungan->tamu && ($request->exists('nama') || $request->exists('email') || $request->exists('no_hp') || $request->exists('instansi_kategori') || $request->exists('instansi_nama'))) {
            $kunjungan->tamu->update([
                'nama'               => $data['nama']              ?? $kunjungan->tamu->nama,
                'email'              => $data['email']             ?? $kunjungan->tamu->email,
                'no_hp'              => $data['no_hp']             ?? $kunjungan->tamu->no_hp,
                'instansi_kategori'  => array_key_exists('instansi_kategori',$data) ? $data['instansi_kategori'] : $kunjungan->tamu->instansi_kategori,
                'instansi_nama'      => array_key_exists('instansi_nama',$data)     ? $data['instansi_nama']     : $kunjungan->tamu->instansi_nama,
            ]);
        }

        return response()->json(['updated'=>true]);
    }

    public function kunjunganDestroy(Kunjungan $kunjungan)
    {
        DB::transaction(function() use ($kunjungan){
            if ($kunjungan->dokumen) {
                Storage::disk('public')->delete($kunjungan->dokumen);
            }
            $kunjungan->riwayatStatus()->delete();
            $kunjungan->delete();
        });

        return response()->json(['deleted'=>true]);
    }
}
