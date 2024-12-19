<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ApiUserController extends Controller
{
    public function updateImageUser(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|file|max:2048',
        ], [
            'image.required' => 'Masukkan Gambar!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors(),
            ], 400);
        }

        try {
            // Ambil ID user yang sedang login
            $userId = Auth::id();

            if ($request->file('image')) {
                $path = $request->file('image');
                $pathGambar = $path->getClientOriginalName();
                $path->move(public_path('user-images'), $pathGambar);
            }

            // Update data user yang sedang login
            $user = User::findOrFail($userId);
            $user->update([
                'image' => $pathGambar,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Update User Image Success!',
                'data' => $user,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Update User Image Failed!',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    public function editPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            "password" => [
                "required",
                'confirmed',
                'regex:/[a-z]/',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 400);
        }
        try {
            $user = User::find($request->id);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 403);
            }

            User::where('id', $request->id)->update([
                'password' => Hash::make($request->password),
            ]);

            $updatedPassword = User::find($request->id);

            return response()->json([
                'success' => true,
                'message' => 'Update Password Successfully!',
                'data' => $updatedPassword,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Failed To Update Password!',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function getUserById(Request $request)
    {
        try {
            $getUserById = User::find($request->id);

            return response()->json([
                'success' => true,
                'message' => 'Get Data User Successfully!',
                'data' => $getUserById,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Failed To Get Data User!',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "occupation" => "required",
            "phone_number" => "required|min:9|max:13",
            // "gambar" => "image|mimes:jpeg,png,jpg|max:2048",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 400);
        }

        try {
            User::where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'occupation' => $request->occupation,
                'phone_number' => $request->phone_number,
            ]);

            $updatedUser = User::find($request->id);

            return response()->json([
                'success' => true,
                'message' => 'Profile Updated Successfully',
                'data' => $updatedUser,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to Update Profile',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function deleteUser(Request $request, $id)
    {
        try {
            // Cari user berdasarkan ID
            $user = User::find($id);

            // Jika user ditemukan
            if ($user) {
                // Hapus user
                $user->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully',
                ], 200);
            } else {
                // Jika user tidak ditemukan
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }
        } catch (Exception $error) {
            // Jika terjadi kesalahan saat menghapus user
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
