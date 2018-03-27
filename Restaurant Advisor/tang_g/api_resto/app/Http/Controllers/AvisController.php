<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resto;
use App\User;
use App\Avis;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;

class AvisController extends Controller
{
  public $successStatus = 200;

  /**
   * register avis
   *
   * @return \Illuminate\Http\Response
   */
  public function registerAvis($idResto, Request $request)
  {
    $user = Auth::user();
    $validator = Validator::make($request->all(), [
        'description' => 'required',
        'note' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 401);
    } else {
      return response()->json($this->insertAvis($idResto, $user->id, $request), $this->successStatus);
    }
  }

  /**
   * update avis
   *
   * @return \Illuminate\Http\Response
   */
  public function updateAvis($idAvis, Request $request)
  {
    $user = Auth::user();
    $validator = Validator::make($request->all(), [
        'description' => 'required',
        'note' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 401);
    } else {
      $avis = Avis::find($idAvis);
      if ($avis != null && $user != null) {
        if ($avis->user_id == $user->id) {
          if ($avis->fill($request->all())->save())
            return response()->json(['success' => "Opinion is update"], $this->successStatus);
          else
            return response()->json(['error'=> "error while the update"], 400);
        }
        else
          return response()->json(['error'=> "You re not the user who post this opinion"], 405);
      } else
        return response()->json(['error'=> "Opinion not found"], 404);
    }
  }

  /**
   * delete avis
   *
   * @return \Illuminate\Http\Response
   */
  public function deleteAvis($idAvis, Request $request)
  {
    $avis = Avis::find($idAvis);
    $user = Auth::user();
    if ($avis != null && $user != null) {
      if ($avis->user_id == $user->id) {
        if ($avis->delete())
          return response()->json(['success' => "Opinion is delete"], $this->successStatus);
        else
          return response()->json(['error'=> "error while the deleting"], 400);
      }
      else
        return response()->json(['error'=> "You re not the user who post this opinion"], 405);
    } else
      return response()->json(['error'=> "Opinion not found"], 404);
  }

  private function insertAvis($idResto, $idUser, $request) {
    $input = $request->all();
    $user = User::find($idUser);
    $resto = Resto::find($idResto);
    if ($user != null && $resto != null) {
      $avis = new Avis([
        'description' => $input['description'],
        'note' => $input['note'] ]);
        if ($avis != null) {
          $user->avis()->save($avis);
          $resto->avis()->save($avis);
          $avis->save();
          return ["success" => "The opinion successfully insert"];
        } else
          return ["error" => "Can't insert the opinion, please try again later"];
    } else {
      return ["error" => "Restaurant or User not found"];
    }
  }

  public function getAllAvis() {
    return response()->json(['avis'=> Avis::all()], $this->successStatus);
  }

  public function getAllAvisOfRestaurant($idResto) {
    $resto = Resto::find($idResto);
    if ($resto != null)
      return response()->json(['avis' => $resto->avis()->get()], $this->successStatus);
    else
      return response()->json(['error'=> "Restaurant not found"], 404);
  }

  public function getOpinion($idAvis) {
    return response()->json(['avis' => Avis::find($idAvis)], $this->successStatus);
  }
}
