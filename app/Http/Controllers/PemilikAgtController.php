<?php

namespace App\Http\Controllers;

use App\Models\agt_kelas;
use App\Models\kelas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PemilikAgtController extends Controller
{
    public function getDataAgt(Request $request){

        $getId = $request->validate([
            'id' => 'required',
        ]);

        try{
            $dataAgt = agt_kelas::leftjoin('users', 'agt_kelas.id_user', 'users.id')
            ->select('agt_kelas.id','agt_kelas.id_user','users.nama', 'users.no_hp')
            ->where('id_kelas', $getId['id'])
            ->get();
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

    public function hapusAgt(Request $request){
        try{
            $getId = $request->validate([
                'id' => 'required',
            ]);
    
            $hapus = agt_kelas::find($getId['id']);
            if($hapus){
                $hapus->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Successfull'
                ], Response::HTTP_NO_CONTENT);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota not found with id: '+$getId['id'],
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
