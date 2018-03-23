<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resto;
use App\User;
use App\Avis;
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
  public function registerAvis($idResto, $idUser,Request $request)
  {
    $validator = Validator::make($request->all(), [
        'description' => 'required',
        'note' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 401);
    } else {
      return response()->json($this->insertAvis($idResto, $idUser, $request), $this->successStatus);
    }
  }

  /**
   * update avis
   *
   * @return \Illuminate\Http\Response
   */
  public function updateAvis($idAvis, $idUser,Request $request)
  {
    $validator = Validator::make($request->all(), [
        'description' => 'required',
        'note' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 401);
    } else {
      $avis = Avis::find($idAvis);
      if ($avis != null) {
        if ($avis->user_id == $idUser) {
          if ($avis->fill($request->all())->save())
            return response()->json(['success' => "Opinion is update"], $this->successStatus);
          else
            return response()->json(['error'=> "error while the update"], $this->successStatus);
        }
        else
          return response()->json(['error'=> "You re not the user who post this opinion"], $this->successStatus);
      } else
        return response()->json(['error'=> "Opinion not found"], $this->successStatus);
    }
  }

  /**
   * delete avis
   *
   * @return \Illuminate\Http\Response
   */
  public function deleteAvis($idAvis, $idUser,Request $request)
  {
    $avis = Avis::find($idAvis);
    if ($avis != null) {
      if ($avis->user_id == $idUser) {
        if ($avis->delete())
          return response()->json(['success' => "Opinion is delete"], $this->successStatus);
        else
          return response()->json(['error'=> "error while the deleting"], $this->successStatus);
      }
      else
        return response()->json(['error'=> "You re not the user who post this opinion"], $this->successStatus);
    } else
      return response()->json(['error'=> "Opinion not found"], $this->successStatus);
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
}
