<?php

namespace App\Http\Services\Admin\Menu;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuService
{
    public function createMenu(Request $request){

        DB::beginTransaction();
        try {
            $menu                       = New Menu;
            $menu->parent_id              = $request->parent_id;
            $menu->label_name_en          = $request->label_name_en;
            $menu->label_name_bn                = $request->label_name_bn;
            $menu->order                = $request->order;
            $menu->page_link_id                   = $request->page_link_id;
            $menu->link_type                   = $request->link_type;
            $menu->link                   = $request->link;
            $menu->save();
            DB::commit();
            return $menu;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
