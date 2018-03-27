<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Resto;
use App\Http\Resources\Resto as RestoResource;
use Illuminate\Support\Facades\Auth;
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
      return response()->json(['error'=> "Restaurant Not Found"], 404);
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
      return response()->json(['error'=> "Restaurant Not Found"], 404);
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
      $user = Auth::user();
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

      if ($validator->fails())
          return response()->json(['error'=>$validator->errors()], 401);

      $input = $request->all();
      if (!empty(DB::select('select * from restos where name = :name LIMIT 1', ['name' => $input['name']])))
        return response()->json(['error'=> "The name of your restaurant has the same of another"], 400);
      else if ($resto = Resto::create($input)) {
        $user->resto()->save($resto);
        return response()->json(['success'=> "The restaurant has been created"], $this->successStatus);
      }
      else
        return response()->json(['error'=> "error while the creating please try again"], 400);
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
      return response()->json($this->updateRestoAction($request), $this->successStatus);
  }

  private function updateRestoAction($request) {
    $user = Auth::user();
    $input = $request->all();
    $result = DB::select('select * from restos where name = :name LIMIT 1', ['name' => $input['name']]);
    if (empty($result))
      return ['error'=> "Restaurant not found"];
    else
      $resto = Resto::find($result[0]->id);
    if ($resto != null && $resto->user_id == $user->id) {
      if ($resto->fill($request->all())->save())
        return ['success'=> "The restaurant is update"];
      else
        return ['error'=> "error while the updating"];
    } else {
      return ['error'=> "Restaurant not found"];
    }
  }

  /**
   * Delete restos
   *
   * @return \Illuminate\Http\Response
   */
  public function deleteRestos($id)
  {
    $user = Auth::user();
    $toDelete = Resto::find($id);
    if ($toDelete != null && $user->id == $toDelete->user_id) {
      $toDelete->menus()->delete();
      $toDelete->avis()->delete();
      if ($toDelete->delete())
        return response()->json(['success'=> "The restaurant has been deleted"], $this->successStatus);
      else
        return response()->json(['error'=> "error while the deleting"], 400);
    } else
      return response()->json(['error'=> "Restaurant Not Found"], 404);
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
      return response()->json(['error'=> "Restaurant Not Found"], 404);
  }

  public function getAllInformations($idResto) {
    $resto = Resto::find($idResto);
    if ($resto != null)
      return response()->json(['Information' => $resto, 'menus_resto' => $resto->menus()->get(),
        'avis_resto' => $resto->avis()->get()], $this->successStatus);
    else
      return Response()->json(['error' => 'Restaurant not Found'], 404);
  }

  public function getAll_MostRecent(Request $request) {
    return response()->json(['restos' => Resto::orderBy('created_at', 'desc')->get()], $this->successStatus);
  }

  public function getAll_ByNote(Request $request) {
    return response()->json(['restos' => Resto::orderBy('note', 'desc')->get()], $this->successStatus);
  }

  public function getAll_ByCategorie($categorie) {
    return response()->json(['restos' =>  DB::select(DB::raw("SELECT * FROM restos WHERE categorie = :categorie"),
    array('categorie' => $categorie))], $this->successStatus);
  }
}
