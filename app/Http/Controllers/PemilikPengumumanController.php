<?php

namespace App\Http\Controllers;

use App\Models\pengumuman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PemilikPengumumanController extends Controller
{

    public function buatPengumuman(Request $request){
        try{
            $request->validate([
                'id_kelas' => 'required',
                'nama' => 'required',
                'desk' => 'required',
            ]);
            
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('files', 'public');
            }

            pengumuman::create([
                'id_kelas'=> $request->id_kelas,
                'nama'=> $request->nama,
                'desk'=> $request->desk,
                'file'=> $filePath,
            ]);
    
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

    public function getDataPengumuman(Request $request){

        $getId = $request->validate([
            'id' => 'required',
        ]);

        try{
            $dataPengumuman = pengumuman::where('id_kelas', $getId['id'])
            ->get();
            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $dataPengumuman
            ], Response::HTTP_OK);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDetailPengumuman(Request $request){
        try{
            $getId = $request->validate([
                'id' => 'required',
            ]);
    
            $detail = pengumuman::find($getId['id']);
            if($detail){
                return response()->json([
                    'success' => true,
                    'message' => 'Successfull',
                    'data' => $detail
                ], Response::HTTP_OK);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Pengumuman not found with id: '+$getId['id'],
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editPengumuman(Request $request){
        try{
            $dataInput = $request->validate([
                'id' => 'required',
                'nama' => 'required',
                'desk' => 'required'
            ]);
            
            $edit = pengumuman::find($dataInput['id']);
            if($edit){
                $edit->nama = $dataInput['nama'];
                $edit->desk = $dataInput['desk'];
                if($request->hasFile('file')){
                    $filePath = $request->file('file')->store('files', 'public');
                    $edit->file = $filePath;
                }
                $edit->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Successfull',
                ], Response::HTTP_OK);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Pengumuman not found with id: '+$edit['id'],
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function hapusPengumuman(Request $request){
        try{
            $getId = $request->validate([
                'id' => 'required',
            ]);
    
            $hapus = pengumuman::find($getId['id']);
            if($hapus){
                $hapus->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Successfull'
                ], Response::HTTP_NO_CONTENT);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Pengumuman not found with id: '+$getId['id'],
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
