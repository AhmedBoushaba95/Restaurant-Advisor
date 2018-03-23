<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menus;
use App\Resto;
use DB;
use Validator;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $result = DB::select('select * from menuses where id = :menu_id LIMIT 1', ['menu_id' => $menu_id]);
        if (!empty($result)) {
          $menu = Menus::find($result[0]->id);
          if ($menu != null) {
            if ($menu->fill($request->all())->save())
              return response()->json(['success' => "The menu is update"], $this->successStatus);
            else
              return response()->json(['error'=> "error while the update"], $this->successStatus);
          }
        } else {
          return response()->json(['error'=> "Restaurant not found"], $this->successStatus);
        }
    }

    /**
     * Insert function Menus
     *
     * @return \Illuminate\Http\Response
     */
    private function insertMenu($request) {
      $input = $request->all();
      $results = DB::select('select * from restos where name = :name OR id = :id', ['name' => $input['resto'], 'id' => $input['resto']]);
      if (!empty($results) && (($resto = Resto::find($results[0]->id)) != null)) {
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
      $toDelete = Menus::find($idMenu);
      if ($toDelete != null) {
        if ($toDelete->delete())
          return response()->json(['success'=> "The Menu has been deleted"], $this->successStatus);
        else
          return response()->json(['success'=> "error while the deleting"], $this->successStatus);
      } else
        return response()->json(['success'=> "Menu Not Found"], $this->successStatus);
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
        return response()->json(['error'=> "Menu Not Found"], $this->successStatus);
    }
}
