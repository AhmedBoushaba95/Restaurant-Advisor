<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Resto;
use App\Http\Resources\Resto as RestoResource;
use Validator;

class RestoController extends Controller
{
  public $successStatus = 200;

  /**
   * Get resto by ID
   *
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $resto = Resto::find($id);
    if ($resto != null)
      return new RestoResource(Resto::find($id));
    else {
      return response()->json(['error'=> "Restaurant Not Found"], $this->successStatus);
    }
  }

  /**
   * Show Resto by name
   *
   * @return \Illuminate\Http\Response
   */
  public function showbyname(Request $request) {
    $input = $request->all();
    $name = $input['name'];
    $results = DB::select('select * from restos where name = :name', ['name' => $name]);
    if ($results == null)
      return response()->json(['error'=> "Restaurant Not Found"], $this->successStatus);
    else
      return new RestoResource(Resto::find($results[0]->id));
  }

  /**
   * Insert Menus
   *
   * @return \Illuminate\Http\Response
   */
  public function registerRestos(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'categorie' => 'required',
          'description' => 'required',
          'note' => 'numeric',
          'address' => 'required',
          'phone' => 'required|regex:/[0-9]{10}/',
          'website' => 'required|url',
          'open_week' => 'required',
          'close_week' => 'required',
          'open_weekend' => 'required',
          'close_weekend' => 'required'
      ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }

      $input = $request->all();
      if ($resto = Resto::create($input))
        return response()->json(['success'=> "The restaurant has been created"], $this->successStatus);
      else
        return response()->json(['error'=> "error while the creating please try again"], $this->successStatus);
  }

  /**
   * Update restos
   *
   * @return \Illuminate\Http\Response
   */
  public function updateResto(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'categorie' => 'required',
          'description' => 'required',
          'note' => 'numeric',
          'address' => 'required',
          'phone' => 'required|regex:/[0-9]{10}/',
          'website' => 'required|url',
          'open_week' => 'required',
          'close_week' => 'required',
          'open_weekend' => 'required',
          'close_weekend' => 'required'
      ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }

      $input = $request->all();
      $result = DB::select('select * from restos where name = :name LIMIT 1', ['name' => $input['name']]);
      $resto = Resto::find($result[0]->id);
      if ($resto != null) {
        if ($resto->fill($request->all())->save())
          return response()->json(['success'=> "The restaurant is update"], $this->successStatus);
        else
          return response()->json(['error'=> "error while the updating"], $this->successStatus);
      } else {
        return response()->json(['error'=> "Restaurant not found"], $this->successStatus);
      }
  }

  /**
   * Delete restos
   *
   * @return \Illuminate\Http\Response
   */
  public function deleteRestos($id)
  {
    $toDelete = Resto::find($id);
    if ($toDelete != null) {
      $toDelete->menus()->delete();
      $toDelete->avis()->delete();
      if ($toDelete->delete())
        return response()->json(['success'=> "The restaurant has been deleted"], $this->successStatus);
      else
        return response()->json(['error'=> "error while the deleting"], $this->successStatus);
    } else
      return response()->json(['error'=> "Restaurant Not Found"], $this->successStatus);
  }

  /**
   * get all restos
   *
   * @return \Illuminate\Http\Response
   */
  public function getDetails(Request $request) {
    return response()->json(['restos' => Resto::all()], $this->successStatus);
  }

  /**
   * get Menus restos
   *
   * @return \Illuminate\Http\Response
   */
  public function getMenus($idResto)
  {
    $resto = Resto::find($idResto);
    if ($resto != null)
      return response()->json(['menus_resto' => $resto->menus()->get()], $this->successStatus);
    else
      return response()->json(['error'=> "Restaurant Not Found"], $this->successStatus);
  }

  public function getAllInformations($idResto) {
    $resto = Resto::find($idResto);
    if ($resto != null)
      return response()->json(['Information' => $resto, 'menus_resto' => $resto->menus()->get(),
        'avis_resto' => $resto->avis()->get()], $this->successStatus);
    else
      return Response()->json(['error' => 'Restaurant not Found'], $this->successStatus);
  }

  public function getAll_MostRecent(Request $request) {
    return response()->json(['restos' => Resto::orderBy('created_at', 'desc')->get()], $this->successStatus);
  }

  public function getAll_ByNote(Request $request) {
    return response()->json(['restos' => Resto::orderBy('note', 'desc')->get()], $this->successStatus);
  }
}
