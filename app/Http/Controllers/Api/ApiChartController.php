<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Mission;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Carbon;

class ApiChartController extends Controller
{
    public function index()
    {
        try {

            $incomeData = Transaction::orderBy('created_at')
                ->where('type', 'income')
                ->where('user_id', auth()->user()->id)
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });

            $spendingData = Transaction::orderBy('created_at')
                ->where('type', 'spending')
                ->where('user_id', auth()->user()->id)
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });

            $chartData = [
                'income' => $incomeData->map(function ($item, $key) {
                    return $item->sum('amount');
                }),
                'spending' => $spendingData->map(function ($item, $key) {
                    return $item->sum('amount');
                }),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Chart data retrieved successfully',
                'data' => $chartData,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
    }

    // public function getAllDailyTask()
    // {
    //     $data = Task::orderBy('created_at')->where('category_id', '2')->where('user_id', auth()->user()->id)->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data berhasil diload',
    //         'data' => $data
    //     ], 200);
    // }

    // public function getAllIncomes()
    // {
    //     $data = Transaction::orderBy('created_at')->where('type', 'income')->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data berhasil diload',
    //         'data' => $data
    //     ], 200);
    // }
}
