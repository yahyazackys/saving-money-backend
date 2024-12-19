<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ApiTransactionController extends Controller
{
    public function addIncome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 400);
        }

        try {
            $data = Transaction::create([
                'user_id' => auth()->user()->id,
                'type' => 'income',
                'amount' => $request->amount,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Add Income Successfully!',
                'data' => $data,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Add Income Failed!',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function addSpending(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id|integer',
            'missions_id' => 'nullable|exists:missions,id|integer',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation ',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $mission = Transaction::create([
                'user_id' => auth()->user()->id,
                'type' => 'spending',
                'amount' => $request->amount,
                'category_id' => $request->category_id,
                'missions_id' => $request->missions_id,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Spending Created Successfully!',
                'data' => $mission,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to Create Mission!',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function getAllIncomes()
    {
        $data = Transaction::orderBy('created_at')->where('user_id', auth()->user()->id)->where('type', 'income')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diload',
            'data' => $data
        ], 200);
    }

    public function getAllSpendings()
    {
        $data = Transaction::with(['category', 'mission'])
            ->where('type', 'spending')
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diload',
            'data' => $data
        ], 200);
    }

    public function getSpendingByMission($missionId)
    {
        // Panggil fungsi getByMissionId dari model Spending dengan parameter $missionId
        $spendings = Transaction::where('missions_id', $missionId)->where('user_id', auth()->user()->id)->get();

        // Jika tidak ada data yang ditemukan
        if ($spendings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No spendings found for this mission.',
            ], 404);
        }

        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'data' => $spendings,
        ]);
    }
}
