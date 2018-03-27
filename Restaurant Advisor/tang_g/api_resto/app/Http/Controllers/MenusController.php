<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menus;
use App\Resto;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;

class MenusController extends Controller
{
    private $successStatus = 200;

    /**
     * Insert Menus
     *
     * @return \Illuminate\Http\Response
     */
    public function registerMenu(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'resto' => 'required',
          'description' => 'required',
          'price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
      ]);

      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 401);
      } else {
        return response()->json($this->insertMenu($request), $this->successStatus);
      }
    }

    /**
     * Update Menus
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMenu($menu_id, Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
        ]);

        if ($validator->fails())
            return response()->json(['error'=>$validator->errors()], 401);

        $input = $request->all();
        $result = DB::select('select * from menuses where id = :menu_id LIMIT 1', ['menu_id' => $menu_id]);
        if (!empty($result)) {
          $menu = Menus::find($result[0]->id);
          if ($menu != null && ((Resto::find($menu->resto_id))->user_id == $user->id)) {
            if ($menu->fill($request->all())->save())
              return response()->json(['success' => "The menu is update"], $this->successStatus);
            else
              return response()->json(['error'=> "error while the update"], 400);
          } else
            return response()->json(['error'=> "User's restaurant not found"], 404);
        } else {
          return response()->json(['error'=> "Restaurant not found"], 404);
        }
    }

    /**
     * Insert function Menus
     *
     * @return \Illuminate\Http\Response
     */
    private function insertMenu($request) {
      $user = Auth::user();
      $input = $request->all();
      $results = DB::select('select * from restos where name = :name OR id = :id', ['name' => $input['resto'], 'id' => $input['resto']]);
      if (!empty($results) && (($resto = Resto::find($results[0]->id)) != null) && $user->id == $resto->user_id) {
        $menu = new Menus([
          'name' => $input['name'],
          'description' => $input['description'],
          'price' => $input['price'] ]);
        if ($menu != null) {
          $resto->menus()->save($menu);
          $menu->save();
          return ["success" => "The menu is insert in the restaurant"];
        }
        else
          return ["error" => "error while the menu's insert"];
      } else
          return ["error" => "Your restaurant is not found"];
    }

    /**
     * delete Menus
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMenu($idMenu)
    {
      $user = Auth::user();
      $toDelete = Menus::find($idMenu);
      if ($toDelete != null && (($resto = Resto::find($toDelete->resto_id)) != null) && $user->id == $resto->user_id) {
        if ($toDelete->delete())
          return response()->json(['success'=> "The Menu has been deleted"], $this->successStatus);
        else
          return response()->json(['error'=> "error while the deleting"], 400);
      } else
        return response()->json(['error'=> "Menu Not Found"], 404);
    }

    /**
     * Get One Menu
     *
     * @return \Illuminate\Http\Response
     */
    public function showMenu($idMenu)
    {
      $menu = Menus::find($idMenu);
      if ($menu != null)
        return response()->json(['Menu'=> $menu], $this->successStatus);
      else
        return response()->json(['error'=> "Menu Not Found"], 404);
    }
}
