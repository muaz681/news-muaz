<?php

namespace App\Http\Controllers\Api\v0;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use JWTAuth;
use App\Http\Resources\Api\v0\MenuResource;
use App\Models\Menu;

class MenuController extends Controller
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
        $menu = Menu::orderBy('id' , 'desc')->get();
        return MenuResource::Collection($menu);
    }

    public function create()
    {
        //
    }


    public function store(MenuRequest $request)
    {
        $menu  = new Menu();
        $menu->fill($request->all());

        if($menu->save()){
            $menus = Menu::orderBy('id' , 'desc')->get();
            $data['menus'] = MenuResource::Collection($menus);

            return $data;
        }
    }


    public function show($id)
    {
        return Menu::where('id' , $id)->firstOrfail();
    }


    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return $menu;
    }


    public function update(MenuRequest $request , $id)
    {
        $menu = Menu::findOrFail($id);

        try{
            $menu->fill($request->all());
            $menu->save();
            return response(['Data Updated Successfully']);

        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'Manu does not exist'
            ]);
        }
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        try{
            $menu->delete();
            $menus = Menu::orderBy('id' , 'desc')->get();
            return response([
                'msg' => 'Data Deleted Successfully',
                'allMenu' => MenuResource::Collection($menus)
            ]);

        }catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'Menu does not exists'
            ]);
        }
    }
}
