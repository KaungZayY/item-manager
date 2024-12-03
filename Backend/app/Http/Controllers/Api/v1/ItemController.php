<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * get all items
     * Header: Bearer Token
     * GET /api/v1/items
     * Filter /api/v1/items?category_id=1&title=item&from_date=2023-01-01&to_date=2023-11-30
     */
    public function index()
    {
        try {
            $items = Item::filter(request(['category_id', 'title', 'from_date', 'to_date']))->with('category')->get();
            return response()->json([
                'status' => 'success',
                'items' => $items,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * add new item
     * Header: Bearer Token
     * POST /api/v1/items
     * @param title,description,category_id
     */
    public function store(ItemRequest $request)
    {
        try {
            $data = $request->validated();
            $item = Item::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'created_date' => now(),
            ]);

            if (!$item) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'An excepted error occured!',
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'item' => $item,
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * get item by id
     * Header: Bearer Token
     * GET /api/v1/items/:id
     */
    public function show($id)
    {
        try {
            $item = Item::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'item' => $item,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Item Not Found!',
                'status' => 404,
            ],404);
        }
    }

    /**
     * update existing item
     * Header: Bearer Token
     * POST /api/v1/items/:id
     * @param title,description,category_id
     */
    public function update(ItemUpdateRequest $request, $id)
    {
        try {
            $item = Item::find($id);
            if (!$item) {
                return response()->json([
                    'message' => 'item not found',
                    'status' => 404,
                ], 404);
            }
            $data = $request->validated();
            $item->update($data);
            return response()->json([
                'status' => 'success',
                'item' => $item,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * delete single item
     * Header: Bearer Token
     * DELETE /api/items/:id
     * @param item_id
     */
    public function destroy($id)
    {
        try {
            $item = Item::find($id);
            if(!$item){
                return response()->json([
                    'message' => 'item not found',
                    'status' => 404,
                ], 404);
            }
            $item->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Item deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500,
            ],500);
        }
    }
}
