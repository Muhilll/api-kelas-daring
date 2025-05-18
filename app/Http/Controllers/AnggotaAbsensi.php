<?php

namespace App\Http\Controllers;

use App\Models\absen;
use App\Models\kehadiran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnggotaAbsensi extends Controller
{

    public function getAbsensi(Request $request)
    {
        try {
            $request->validate([
                'id_kelas' => 'required'
            ]);

            $absensi = absen::where('id_kelas', $request->id_kelas)->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $absensi
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function cekKehadiran(Request $request)
    {
        try {
            $request->validate([
                'id_absensi' => 'required',
                'id_agtkelas' => 'required',
            ]);

            $ada = kehadiran::where([
                ['id_absensi', '=', $request->id_absensi],
                ['id_agtkelas', '=', $request->id_agtkelas]
            ])->exists();

            return response()->json([
                'success' => true,
                'sudah_absen' => $ada
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function submitKehadiran(Request $request)
    {
        try {
            $request->validate([
                'id_absensi' => 'required',
                'id_agtkelas' => 'required',
                'keterangan' => 'required',
            ]);

            kehadiran::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
