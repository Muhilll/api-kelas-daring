<?php

namespace App\Http\Controllers;

use App\Models\absen;
use App\Models\kehadiran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PemilikAbsensi extends Controller
{
    public function buatAbsen(Request $request)
    {
        try {
            $request->validate([
                'id_kelas' => 'required',
                'nama' => 'required',
                'batas' => 'required',
            ]);

            absen::create([
                'id_kelas' => $request->id_kelas,
                'nama' => $request->nama,
                'tgl' => now()->toDateString(),
                'batas' => $request->batas,
            ]);

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

    public function getDataAbsensi(Request $request)
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

    public function getDataKehadiran(Request $request)
    {
        try {
            $request->validate([
                'id_absensi' => 'required'
            ]);

            $kehadiran = kehadiran::with('agtkelas.user')
                ->where('id_absensi', $request->id_absensi)
                ->get();

            $data = $kehadiran->map(function ($item) {
                return $item->agtkelas->user;
            });

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}