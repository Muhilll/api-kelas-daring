<?php

namespace App\Http\Controllers;

use App\Models\pengumpulan;
use App\Models\pengumuman;
use App\Models\tugas;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PemilikTugasController extends Controller
{
    public function getDataTugas(Request $request)
    {

        $getId = $request->validate([
            'id' => 'required',
        ]);

        try {
            $datatugas = tugas::where('id_kelas', $getId['id'])
                ->get();
            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $datatugas
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function buatTugas(Request $request)
    {
        try {
            $dataInput = $request->validate([
                'id_kelas' => 'required',
                'nama' => 'required',
                'tgl_selesai' => 'required',
            ]);

            $tugas = new tugas();
            $tugas->id_kelas = $dataInput['id_kelas'];
            $tugas->nama = $dataInput['nama'];
            $tugas->tgl_mulai = Carbon::now()->format('Y-m-d');
            $tugas->tgl_selesai = $dataInput['tgl_selesai'];
            $tugas->save();

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

    public function getDataPengumpulanTugas(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required'
            ]);

            $dataPengumpulanTugas = pengumpulan::leftjoin('agt_kelas', 'pengumpulans.id_agtkelas', 'agt_kelas.id')
                ->leftjoin('users', 'agt_kelas.id_user', 'users.id')
                ->select('pengumpulans.id', 'users.nama', 'pengumpulans.file', 'pengumpulans.tgl')
                ->where('pengumpulans.id_tugas', $request->id)->get();

            return response()->json([   
                'success' => true,
                'message' => 'Successfull',
                'data' => $dataPengumpulanTugas
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function downloadTugas(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all());

            $request->validate([
                'id' => 'required'
            ]);

            $pengumpulan = pengumpulan::find($request->id);
            if (!$pengumpulan) {
                Log::error("Data dengan id {$request->id} tidak ditemukan.");
                return response()->json(['message' => 'Data tidak ditemukan.'], 404);
            }

            $filePath = $pengumpulan->file;
            $fullPath = storage_path("app/public/{$filePath}");
            Log::info("File path: {$fullPath}");

            if (!file_exists($fullPath)) {
                Log::error("File {$fullPath} tidak ditemukan.");
                return response()->json(['message' => 'File tidak ditemukan.'], 404);
            }

            return response()->download($fullPath);
        } catch (Exception $e) {
            Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
