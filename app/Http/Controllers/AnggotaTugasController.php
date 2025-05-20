<?php

namespace App\Http\Controllers;

use App\Models\pengumpulan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AnggotaTugasController extends Controller
{
    public function cekTugas(Request $request)
    {

        try {
            $request->validate([
                'id_tugas' => 'required',
                'id_agtkelas' => 'required',
            ]);

            $tugasExists = pengumpulan::where('id_tugas', $request->id_tugas)
                ->where('id_agtkelas', $request->id_agtkelas)
                ->exists();
            $bisa = !$tugasExists;


            $pengumpulan = pengumpulan::where('id_tugas', $request->id_tugas)
                ->where('id_agtkelas', $request->id_agtkelas)
                ->first();


            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'bisa' => $bisa,
                'id_pengumpulan' => $pengumpulan->id ?? 0,
                'tanggal' => $pengumpulan->tgl ?? ""
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function prosesUploadTugas(Request $request)
    {
        try {
            $request->validate([
                'id_tugas' => 'required',
                'id_agtkelas' => 'required',
                'file' => 'required',
            ]);

            $file = $request->file('file')->store('files', 'public');

            pengumpulan::create([
                'id_tugas' => $request->id_tugas,
                'id_agtkelas' => $request->id_agtkelas,
                'file' => $file,
                'tgl' => now()->toDateString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function hapusTugas(Request $request)
    {
        try {
            $request->validate([
                'id_pengumpulan'
            ]);

            $pengumpulan = pengumpulan::find($request->id_pengumpulan);
            $pengumpulan->delete();
            Storage::delete('public/' . $pengumpulan->file);

            return response()->json([
                'success' => true,
                'message' => 'Successfull'
            ], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
