<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "category_id" => ["sometimes", "integer", "exists:categories,id"],
            "q" => ["sometimes", "string", "min:2"],
            "sort" => ["sometimes", "in:name,-name,price,-price"],
            "per_page" => ["sometimes", "integer", "min:1", "max:100"],
        ]);

        $sort = $request->input("sort", "name");
        $direction = str_starts_with($sort, "-") ? "desc" : "asc";

        $products = Product::with("category")
            ->when($request->filled("category_id"), fn ($query) =>
                $query->where("category_id", $request->integer("category_id")))
            ->when($request->filled("q"), fn ($query) => 
                $query->where("name", "like", "%".$request->input("q")."%"))
            ->orderBy(ltrim($sort, "-"), $direction)
            ->paginate($request->integer("per_page", 15))
            ->withQueryString();

        return ProductResource::collection($products);
    }
}
