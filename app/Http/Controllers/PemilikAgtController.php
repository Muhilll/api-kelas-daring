<?php

namespace App\Http\Controllers;

use App\Models\agt_kelas;
use App\Models\kelas;
use App\Models\User;
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
                'message' => 'Failed: '.$e->getMessage(),
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
                    'message' => 'Anggota not found with id: '.$getId['id'],
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getUser(Request $request){
        try{
        $request->validate([
            'id' => 'required'
        ]);

        $id_kelas = $request->id;
        $kelas = kelas::with('anggota')->find($id_kelas);   

        if($kelas){
            $anggotaIds = $kelas->anggota->pluck('id');
            $id_pemilik = $kelas->id_user;

            $users = User::whereNotIn('id', $anggotaIds)
                        ->where('id', '!=', $id_pemilik)
                        ->get();

            return response()->json([
                    'success' => true,
                    'message' => 'Successfull',
                    'data' => $users
                ], Response::HTTP_OK);
        } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas not found with id: '.$id_kelas,
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getUserByNim(Request $request){
        try{
            $request->validate([
                'id_kelas' => 'required',
                'nim' => 'required'
            ]);

            $idKelas = $request->id_kelas;
            $nim = $request->nim;

            $kelas = kelas::find($idKelas);

            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas not found with id: '.$idKelas,
                ], Response::HTTP_BAD_REQUEST);
            }

            $idPemilik = $kelas->id_user;

            $dataUser = User::where('nim', $nim)
                ->where('id', '!=', $idPemilik)
                ->whereDoesntHave('kelasDiikuti', function($query) use ($idKelas) {
                    $query->where('id_kelas', $idKelas);
                })
                ->first();

            if($dataUser){
                return response()->json([
                    'success' => true,
                    'message' => 'Successfull',
                    'data' => $dataUser
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with nim: '.$request->nim,
                ], Response::HTTP_BAD_REQUEST);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function tmbhAgt(Request $request){
        try{
            $request->validate([
                'id_kelas' => 'required',
                'id_user' => 'required'
            ]);

            $dataKelas = kelas::find($request->id_kelas);
            $dataUser = User::find($request->id_user);
            if($dataKelas){
                if($dataUser){
                    agt_kelas::create($request->all());
                    return response()->json([
                        'success' => true,
                        'message' => 'Successfull',
                    ], Response::HTTP_CREATED);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found with id: '.$request->id_user,
                    ], Response::HTTP_BAD_REQUEST);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas not found with id: '.$request->id_kelas,
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
