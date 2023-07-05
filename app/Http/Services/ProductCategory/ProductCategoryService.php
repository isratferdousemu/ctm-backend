<?php

namespace App\Http\Services\ProductCategory;

use App\Models\ProductCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryService
{
    public function createProductCategory(Request $request){
        DB::beginTransaction();
        try {

            $cat                       = new ProductCategories;
            $cat->name                = $request->name;
            $cat->description                = $request->description;
            $cat->save();
            DB::commit();
            return $cat;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateCategoryService(Request $request, ProductCategories $cat){
        DB::beginTransaction();
        try {
            $cat->name                = $request->name;
            $cat->description    = $request->description;
            $cat->save();
            DB::commit();
            return $cat;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function deleteProductCategory(ProductCategories $cat)
    {
        DB::beginTransaction();
        try {
            ProductCategories::whereId($cat->id)->delete();
            // $cat->leads->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function ForceDeleteProductCategory(Request $request)
    {
        DB::beginTransaction();
        try {
            ProductCategories::onlyTrashed()->find($request->id)->forceDelete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
