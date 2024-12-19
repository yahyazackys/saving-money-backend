<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mission;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;


class ApiMissionController extends Controller
{
    public function addMission(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'target_amount' => 'required|numeric',
            'target_date' => 'required|date',
            'category_id' => 'required|exists:categories,id|integer',
            // 'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Membuat entri baru dalam tabel missions
            $mission = Mission::create([
                'user_id' => auth()->user()->id,
                'title' => $request->title,
                'target_amount' => $request->target_amount,
                'target_date' => $request->target_date,
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mission Created Successfully!',
                'data' => $mission,
            ], 200);
        } catch (Exception $error) {
            Log::error('Failed to create mission: ' . $error->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to Create Mission!',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function getAllMissions()
    {
        // $data = Mission::orderBy('created_at')->get();
        $data = Mission::with(['category'])
            ->orderBy('created_at')
            ->where('user_id', auth()->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diload',
            'data' => $data
        ], 200);
    }
}
