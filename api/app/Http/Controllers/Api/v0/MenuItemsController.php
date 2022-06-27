<?php

namespace App\Http\Controllers\Api\v0;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\Api\v0\MenuItemsResource;
use JWTAuth;
use App\Http\Resources\Api\v0\MenuResource;
use App\Models\MenuItems;
use App\Http\Requests\MenuItemsRequest;

class MenuItemsController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
//        $this->middleware('permission:menu-list|menu-create|menu-edit|menu-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:menu-create', ['only' => ['create','store']]);
//        $this->middleware('permission:menu-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $menu = MenuItems::orderBy('id' , 'desc')->get();
        return MenuItemsResource::Collection($menu);
    }


    public function create()
    {
        //
    }


    public function store(MenuItemsRequest $request)
    {
        $menu  = new MenuItems();
        $menu->fill($request->all());

        if($menu->save()){
            $menus = MenuItems::orderBy('id' , 'desc')->get();
            $data['allMenuItems'] = MenuItemsResource::Collection($menus);

            return $data;
        }
        // $menu->save();

    }


    public function show($id)
    {
        return MenuItems::where('id' , $id)->firstOrfail();
    }


    public function edit($id)
    {
        $menu = MenuItems::findOrFail($id);
        return $menu;
    }


    public function update(MenuItemsRequest $request , $id)
    {
        $menu = MenuItems::findOrFail($id);

        try{
            $menu->fill($request->all());
            $menu->save();
            return response(['Data Updated Successfully']);

        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'Manu Item does not exist'
            ]);
        }
    }


    public function destroy($id)
    {
        $menu = MenuItems::findOrFail($id);
        try{
            $menu->delete();
            return response(['Data Deleted Successfully']);

        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'Menu Item does not exists'
            ]);
        }
    }

    public function upsert(MenuItemsRequest $request) {
        $data['req'] =  $request->params;
        if (MenuItems::upsert($request->params, ['id'], ['title', 'menu_id', 'link', 'menuable_type', 'menuable_id', 'order', 'parameters', 'parent'])) {
            $data['success'] = 'Saved Successfully!';
        }
        return $data;
    }
}
