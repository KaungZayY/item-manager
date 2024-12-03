<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * get all categories
     * Header: Bearer Token
     * GET /api/v1/categories
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => 'success',
                'categories' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 500,
                'message' => 'An unexpected error occurred. Please try again later.',
            ],500);
        }
    }
}
