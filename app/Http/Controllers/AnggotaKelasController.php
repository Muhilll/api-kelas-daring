<?php

namespace App\Http\Controllers;

use App\Models\agt_kelas;
use App\Models\kelas;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AnggotaKelasController extends Controller
{
    public function getDataKelas(Request $request){

        
        try{
            $getId = $request->validate([
                'id' => 'required',
            ]);

            $dataKelas = kelas::Leftjoin('users', 'kelas.id_user', 'users.id')
            ->leftjoin('agt_kelas', 'kelas.id', 'agt_kelas.id_kelas')
            ->select('kelas.id','kelas.nama as namaKelas', 'users.nama as pemilik')
            ->where('agt_kelas.id_user', $getId['id'])
            ->get();
            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $dataKelas
            ], Response::HTTP_OK);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getPemilikKelas(Request $request){
        
        try{
            $request->validate([
                'id_kelas'
            ]);

            $pemilik = kelas::where('id', $request->id_kelas)->first();
            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $pemilik->user
            ], Response::HTTP_OK);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDataAgtKelas(Request $request){
        
        try{
            $request->validate([
                'id_kelas' => 'required',
                'id_user' => 'required'
            ]);

            $dataAgt = agt_kelas::where('id_kelas', $request->id_kelas)->where('id_user', $request->id_user)->first();
            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $dataAgt
            ], Response::HTTP_OK);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
