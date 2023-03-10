<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\Pasien;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Jadwal;
use App\Models\Reservasi;
use DB;
use Session;
use Carbon\Carbon;
session_id("tubes-wad");
session_start();

class AdminController extends Controller
{
    public function cek_reservasi_pasien()
    {
       $reservasi = DB::table('reservasi as a')
            ->join('pasien as b', 'a.no_rekam_medis', '=', 'b.no_rekam_medis')
            ->join('jadwal as c', 'a.id_jadwal', '=', 'c.id')
            ->join('dokter as d', 'a.id_dokter', '=', 'd.id')
            ->join('poli as e', 'd.id_poli', '=', 'e.id')
            ->select('a.*', 'b.nama as nama_pasien', 
            'b.no_telp', 'b.alamat',
            'c.jadwal_mulai','c.jadwal_selesai',
            'd.nama as nama_dokter',
            'e.nama as nama_poli')
            ->orderBy('id_dokter', 'asc')
            ->get();

        return view('pages.admin.cek-reservasi-pasien', compact('reservasi'));
    }

    public function ubah_status(Request $request, $id)
    {
        $res = Reservasi::where('kode_reservasi', $id)->update([
            'status' => '1'
        ]);

        if($res)
        {
            return redirect()->back()->with('success', 'Data berhasil diubah');
        } else {
            return redirect()->back()->with('error', 'Data gagal diubah');
        }
    }
}
