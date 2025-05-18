<?php

namespace App\Http\Controllers;

use App\Models\agt_kelas;
use App\Models\kelas;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $dataInput = $request->validate([
                'nim' => 'required',
                'password' => 'required'
            ]);

            $user = User::where('nim', $dataInput['nim'])->first();

            if ($user && Hash::check($dataInput['password'], $user->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successfull',
                    'idUser' => $user->id
                ], Response::HTTP_OK);
            }

            return response()->json([
                'success' => false,
                'message' => 'Login failed, please check your nim and password!',
            ], Response::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function register(Request $request)
    {
        try {
            $dataInput = $request->validate([
                'nim' => 'required',
                'nama' => 'required',
                'id_jurusan' => 'required',
                'jkel' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);

            User::create($dataInput);

            return response()->json([
                'success' => true,
                'message' => 'Signup successfull',
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Signup failed: ' . $e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDataKelas(Request $request)
    {

        $getId = $request->validate([
            'id' => 'required',
        ]);

        try {
            $userId = $getId['id'];

            $dataKelas = kelas::Leftjoin('users', 'kelas.id_user', 'users.id')
                ->leftjoin('agt_kelas', function ($join) use ($userId) {
                    $join->on('kelas.id', '=', 'agt_kelas.id_kelas')
                        ->where('agt_kelas.id_user', '=', $userId);
                })
                ->select('kelas.id', 'kelas.nama as namaKelas', 'users.nama as pemilik')
                ->where('kelas.jenis', 'Public')
                ->where('kelas.id_user', '!=', $userId)
                ->whereNull('agt_kelas.id')
                ->groupBy('kelas.id', 'kelas.nama', 'users.nama')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $dataKelas
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function gabungKelas(Request $request)
    {
        try {
            $request->validate([
                'id_kelas' => 'required',
                'id_user' => 'required',
            ]);

            agt_kelas::create($request->all());
            $agt_kelas = agt_kelas::where('id_user', $request->id_user)->first();

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'id_agtkelas' => $agt_kelas->id
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function getDataUser(Request $request)
    {
        try {
            $request->validate([
                'id_user' => 'required'
            ]);

            $dataUser = User::leftjoin('jurusans', 'users.id_jurusan', 'jurusans.id')
            ->select('users.id', 'users.nim', 'users.nama', 'jurusans.nama as jurusan', 'users.jkel', 'users.alamat', 'users.no_hp', 'users.email')
            ->where('users.id', $request->id_user)->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
                'data' => $dataUser
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editUser(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'nim' => 'required',
                'nama' => 'required',
                'id_jurusan' => 'required',
                'jkel' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'email' => 'required',
            ]);

            $user = User::find($request->id);
            $user->nim = $request->nim;
            $user->nama = $request->nama;
            $user->id_jurusan = $request->id_jurusan;
            $user->jkel = $request->jkel;
            $user->alamat = $request->alamat;
            $user->no_hp = $request->no_hp;
            $user->email = $request->email;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Successfull',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Edit failed: ' . $e,
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}