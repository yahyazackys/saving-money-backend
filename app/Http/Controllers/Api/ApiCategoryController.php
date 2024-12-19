<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Mission;
use Illuminate\Support\Facades\Validator;
use Exception;

class ApiCategoryController extends Controller
{
    public function getAllCategories()
    {
        $data = Category::orderBy('created_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diload',
            'data' => $data
        ], 200);
    }
}
