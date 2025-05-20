<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PemilikKelasController extends Controller
{
    public function buatKelas(Request $request){
        try{
            $dataInput = $request->validate([
                'nama' => 'required',
                'id_user' => 'required',
                'deskripsi' => 'required',
                'jenis' => 'required'
            ]);
            
            kelas::create($dataInput);
    
            return response()->json([
                'success' => true,
                'message' => 'Successfull',
            ], Response::HTTP_CREATED);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDataKelas(Request $request){

        $getId = $request->validate([
            'id' => 'required',
        ]);

        try{
            $dataKelas = kelas::Leftjoin('users', 'kelas.id_user', 'users.id')
            ->select('kelas.id','kelas.nama as namaKelas', 'users.nama as pemilik')
            ->where('kelas.id_user', $getId['id'])
            ->get();
            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $dataKelas
            ], Response::HTTP_OK);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDetailKelas(Request $request){
        try{
            $getId = $request->validate([
                'id' => 'required',
            ]);
    
            $detail = kelas::find($getId['id']);
            if($detail){
                return response()->json([
                    'success' => true,
                    'message' => 'Successfull',
                    'data' => $detail
                ], Response::HTTP_OK);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas not found with id: '+$getId['id'],
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editKelas(Request $request){
        try{
            $dataInput = $request->validate([
                'id' => 'required',
                'nama' => 'required',
                'deskripsi' => 'required',
                'jenis' => 'required'
            ]);
            
            $edit = kelas::find($dataInput['id']);
            if($edit){
                $edit->nama = $dataInput['nama'];
                $edit->deskripsi = $dataInput['deskripsi'];
                $edit->jenis = $dataInput['jenis'];
                $edit->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Successfull',
                ], Response::HTTP_OK);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas not found with id: '+$edit['id'],
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function hapusKelas(Request $request){
        try{
            $getId = $request->validate([
                'id' => 'required',
            ]);
    
            $hapus = kelas::find($getId['id']);
            if($hapus){
                $hapus->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Successfull'
                ], Response::HTTP_NO_CONTENT);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas not found with id: '+$getId['id'],
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