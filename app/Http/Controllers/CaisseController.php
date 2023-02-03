<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\caisse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Mail\Mailable;
use Mail;
use App\Models\User;
use App\Models\UserCaisse;
use Session;
use Illuminate\Support\Facades\Response;
use Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserCaisseResource;
use App\Http\Resources\GetCaisse;


class CaisseController extends Controller
{
    public function index(Request $request){
         $regle = array(            
            'admin'=> 'required',
            'compta'=> 'required',
            'stock'=> 'required',
            'com'=> 'required',
            'paie'=> 'required',
            'immos'=> 'required',
            'budget'=> 'required',
            'rap'=> 'required',
            );
        $validator = Validator::make($request->all(), $regle);
         
        if($validator->fails()){
          return response()->json($validator->errors(), 400);
        }
        
        
        $compta = ["achat", "trans_di", "dettes", "transCpt", "remb_dettes", "ventes"];
        $stock = ["achat", "ventes"];
        $com = ["facture", "report"];
        $paie = ["paie"];
        $immos = ["immos"];
        $budget = ["budjet"];
        $rap =["report"];
        
        $data;
        
         if($request->admin == "oui"){
            $data[] = $compta;
            $data[] = $stock;
            $data[] = $com;
            $data[] = $paie;
            $data[] = $immos;
            $data[] = $budget;
            $data[] = $rap;
        };
        
        if($request->compta == "oui"){
            $data[] = $compta;
        };
        
         if($request->stock == "oui"){
            $data[] = $stock;
        };
        
        if($request->com == "oui"){
           $data[] = $com;
        };
        
        if($request->paie == "oui"){
            $data[] = $paie;
        };
        
        if($request->immos == "oui"){
            $data[] = $immos;
        };
        
        if($request->budget == "oui"){
            $data[] = $budget;
        };
        
        if($request->rap == "oui"){
            $data[] = $rap;
        };
        return $data;
         
    }
    
    
    public function get(Request $request)
    {
         $regle = array(            
             'id',
            'raison_social'=>'required',
            );
        $validator = Validator::make($request->all(), $regle);
         
        if($validator->fails()){
          return response()->json($validator->errors(), 400);
        }
        $caisse = DB::table('caisses')->where('raison_social', $request->raison_social)->get();
        $user = DB::table('users')->where('id', $request->id)->get();
        $compte = count($caisse);
        //return $caisse;
        
        if($compte == 1){
             $userCaisse = UserCaisse::where(array("user_id"=>$request->id, "caisse_id"=>$caisse[0]->id))->first();
             if(isset($userCaisse)){
                  return [[$userCaisse, "raison_social"=>$caisse[0]->raison_social, "email_inviter"=>$user[0]->email]];
             }else {
                 return ["aucun lien la caisse n'existe pas"];
             }
            
        }else{
            return ["la caisse n'existe pas"];
        }
           
    }
    
    
    
     
       public function getId($id)
    {
               
        return $caisse[] = GetCaisse::collection(UserCaisse::where('user_id' ,  $id)->get());
 
    }
    
    
    
    public function store(Request $request){
        
        $regle = array(
            'logo' => 'required|string',
            'nif',
            'rccm' => 'required|string',
            'raison_social' => 'required|string',
            'id'=> 'required',
            'token'=> 'required|string',
            'nom_prenom',
            'email',
            'adresse',
            'cnss',
            'telephone',
            'compte_actif',
            );
        $validator = Validator::make($request->all(), $regle);
        
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $selectUser = User::where('token', $request->token)->get();
      
         if($selectUser->count() == 1){
             
             try{
             $caisse = new caisse();
             $caisse->logo = $request ->logo;
             $caisse->nif = $request ->nif;
             $caisse->rccm = $request ->rccm;
             $caisse->raison_social = $request->raison_social;
             $caisse->nom_prenom = $request ->nom_prenom;
             $caisse->email = $request ->email;
             $caisse->adresse = $request ->adresse;
             $caisse->cnss = $request ->cnss;
             $caisse->telephone = $request ->telephone;
             $caisse->compte_actif = $request ->compte_actif;
             $caisse->user_id = $request ->id;
             $cais = $caisse -> save();
               
             if($cais){
                 
                //  $userCaisse = UserCaisse::create([
                //         'user_id' => $request->id, 
                //         'caisse_id' => $caisse->id,
                //         'admin' => "oui/modif/visual",
                //         'compta' => "oui",
                //         'stock' => "oui",
                //         'com' => "oui",
                //         'paie' => "oui",
                //         'immos' => "oui",
                //         'budget' => "oui",
                //         'rap' => "oui",
                //     ]);
                    
                 $user = User::findOrFail($selectUser[0]->id);
                 $user->proprietaire = true;
                 $user->caisse_id =  $caisse->id;
                 $upUser = $user->update();
                
                 return ['msg'=> "bien enregistrer", 'code'=> 200, "raison_social" => $caisse->raison_social, "email" => $caisse->email, "id" => $request->token, "token" =>$request->id, "ID" =>$caisse->id];
            
                };
            }catch(\exception $es){
                return ['msg'=> "existe deja", 'code'=> 405];
             }
             
         }else {
             
             return ['msg'=> "mauvais token", 'code'=> 405];
       
        }
    }
  
    
    
    public function show($id){
        
        $caisse = DB::select("SELECT * FROM caisses where user_id = $id");
        return $caisse;
    }
    
    
    public function showCaisseUser(Request $request){
        $regle = array(            
            'raison_social'=>'required',
            );
        $validator = Validator::make($request->all(), $regle);
         
        if($validator->fails()){
          return response()->json($validator->errors(), 400);
        }
        
        //$caisse = caisse::where('raison_social', $request->raison_social)->get();
        
        
        $caisse = DB::table('caisses')->where('raison_social', $request->raison_social)->get();
        $compte = count($caisse);
        
        if($compte > 0){
            
            
             return UserCaisseResource::collection(UserCaisse::where('caisse_id' ,  $caisse[0]->id)->get());
           // $inviter = unserialize($caisse[0]->inviter);
           
            
            // foreach ($inviter as $valeur) {
               
            //     $user[] = DB::table('users')->where('id', $valeur)->get();
            //      $select[] = DB::table('UserCaisse')->where('user_id', $valeur)->get();
            //   }
              
             
            //  foreach ($user as $use){
            //     $utilisateur[] = $use[0];
            //  }
             
            //   foreach ($select as $selects){
            //     $droit[] = $selects[0];
            //  }
           
        }else{
            return ["caisse2"=>"Caisse n'existe pas", "code"=>400];
        }
        
    }
    
    
   
    
    
    public  function update(Request $request, $id){
        $regle = array(
            'id',
            'token',
            );
        $validator = Validator::make($request->all(), $regle);
        
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $selectUser = User::where('token', $request->token)->get();
        
        if($selectUser){
            $caisse = caisse::findOrFail($id);
    
            $caisse->update($request->all());
    
             return response()->json([
                    'msg' => 'caisse est bien modifiée',
                    'status_code' => 200,
                    'id' => $caisse,
                ]);
        }
    }
    
    
    public function destroy($id){
          $regle = array(
            'id',
            'token',
            );
        $validator = Validator::make($request->all(), $regle);
        
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $selectUser = User::where('token', $request->token)->get();
        
        if($selectUser){
            $caisse = caisse::destroy($id);
            
            return response()->json([
                    'msg' => 'caisse est bien suprimée',
                    'status_code' => 200,
                    'id' => $caisse,
                ]);
        }
    }
    
    
    public function LienInvitation(Request $request){
        $regle = array(
            'id',
            'token',
            'email' => 'required|string|email',
            'admin'=> 'required',
            'compta'=> 'required',
            'stock'=> 'required',
            'com'=> 'required',
            'paie'=> 'required',
            'immos'=> 'required',
            'budget'=> 'required',
            'rap'=> 'required',
            'raison_social',
        );
        $validator = Validator::make($request->all(), $regle);
        if($validator->fails()){
            return ['msg1'=>"Veuillez remplir tous les champs", "code"=>400];
        };
        $user = User::findOrFail($request->id);
        
           if($user->token == $request->token){
             
            $utilisateur = User::where('email', $request->email)->get();
            $caisse = caisse::where('raison_social', $request->raison_social)->get();
         
             if(count($caisse) == 1){
            
                if(count($utilisateur) == 1){
                  //try{
                      
                    $lien = 'https://caisse-zero.vercel.app/inviter';
                    
                    $mail = Mail::send('lien', ["lien"=>$lien, "raison_social"=>$caisse[0]->raison_social], function($message) use($request){
                          $message->to($request->email);
                          $message->subject("Lien d'invitation");
                    });
                    
                    
                     $arr = unserialize($caisse[0]->inviter);
                    $id_user = $utilisateur[0]->id;
                     $arr[] = (int)$id_user;
                     
                     $utilisateur = User::findOrFail($utilisateur[0]->id);
                     $utilisateur->inviter = serialize($arr);
                     $upUsers = $utilisateur->update();
                    
                    $cais = caisse::findOrFail($caisse[0]->id);
                    $cais->inviter = serialize($arr);
                    $upCais = $cais->update();
                    
                    $userCaisse =  UserCaisse::where("user_id", $utilisateur->id)->where("caisse_id", $caisse[0]->id)->first();
                    
                    if (isset($userCaisse)){
                          UserCaisse::where("user_id", $utilisateur->id)->where("caisse_id", $caisse[0]->id)->update([
                        'user_id' => $id_user,
                        'caisse_id' => $caisse[0]->id,
                        'admin' => $request->admin,
                        'compta' => $request->compta,
                        'stock' => $request->stock,
                        'com' => $request->com,
                        'paie' => $request->paie,
                        'immos' => $request->immos,
                        'budget' => $request->budget,
                        'rap' => $request->rap
                        ]);  
                    } else {
                         $userCaisse = UserCaisse::create([
                                'user_id' => $id_user, 
                                'caisse_id' => $caisse[0]->id,
                                'admin' => $request->admin,
                                'compta' => $request->compta,
                                'stock' => $request->stock,
                                'com' => $request->com,
                                'paie' => $request->paie,
                                'immos' => $request->immos,
                                'budget' => $request->budget,
                                'rap' => $request->rap,
                            ]);
                    }
                    
                    return ['msg'=>"Email envoyer", "code"=>200, "caisseUser"=>$userCaisse, 'email'=>$request->email/*, 'admin'=>$userCaisse->admin, 'compta'=>$userCaisse->compta, 'stock'=>$userCaisse->stock, 'com'=>$userCaisse->com, 'paie'=>$userCaisse->paie, 'immos'=>$userCaisse->immos, 'budget'=>$userCaisse->budget, 'rap'=>$userCaisse->rap*/];
                    
                
                
                // }catch(\exception){
                //     return ['msg'=>"Email non envoyer"];
                // }
              
                
              }else {
                  $random = str_shuffle('abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ23456789!@#$%*');
                    $password = substr($random, 0, 10);
                      
                    $lien = 'https://caisse-zero.vercel.app/inviter';
                    
                    $mail = Mail::send('liens', ['password' => $password, "lien"=>$lien, "raison_social"=>$caisse[0]->raison_social], function($message) use($request){
                          $message->to($request->email);
                          $message->subject("Lien d'invitation");
                    });
                 
                $userAdd = new User();
                $userAdd->email = $request->email;
                $userAdd->password = Hash::make($password);
                //$userAdd->caisse_id = $caisse[0]->id;
                $upUser = $userAdd->save();
                
                $arr = array((int)$userAdd->id);
                
                
                 $arr = unserialize($caisse[0]->inviter);
                 $id_user = $userAdd->id;
                 $arr[] = (int)$id_user;
                
                $userCaisse = UserCaisse::create([
                        'user_id' => $userAdd->id, 
                        'caisse_id' => $caisse[0]->id,
                        'admin' => $request->admin,
                        'compta' => $request->compta,
                        'stock' => $request->stock,
                        'com' => $request->com,
                        'paie' => $request->paie,
                        'immos' => $request->immos,
                        'budget' => $request->budget,
                        'rap' => $request->rap,
                    ]);
                    
                    
                    $cais = caisse::findOrFail($caisse[0]->id);
                    $cais->inviter = serialize($arr);
                    $upCais = $cais->update();
            
                    $token = $userAdd->createToken('API TOKEN')->plainTextToken;
                
                    if($upUser){
                        $users = User::findOrFail($userAdd->id);
                        $users->token = $token;
                        $users->inviter = serialize($arr);
                        $upUsers = $users->update();
                        return ['msg'=>'Email envoyer', "code"=>200, 'email'=>$request->email,
                        'admin'=>$userCaisse->admin, 'compta'=>$userCaisse->compta, 'stock'=>$userCaisse->stock, 'com'=>$userCaisse->com, 
                        'paie'=>$userCaisse->paie, 'immos'=>$userCaisse->immos, 'budget'=>$userCaisse->budget, 'rap'=>$userCaisse->rap];
                    }
                }
            
            }else {
                 return ["msg"=>"la caisse n'existe pas", 'code'=> 405];
            }
          
             
             
        }else {
             return ["msg"=>"token non verifier", 'code'=> 405];
        }
          
    }
    
    
    public function loginInviter(Request $request){
         $regle = array(
           'email' => 'required|string',
           'password' => 'required|string'
        );
        $validator = Validator::make($request->all(), $regle);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        };
        
        $user = DB::table('users')->where('email', $request->email)->get();
       
        if ($user->count() == 0){
            return ['msg'=>'utilisateur nexiste pas', 'status_code' => 405];
        }else {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
            
                $caisse = caisse::where('user_id', $user->id)->get();
                
                    
                return response()->json([
                    'msg' => 'Utilisateur connectée',
                    'status_code' => 200,
                    'token' => $user->token,
                    'id' => $user->id,
                    'email' => $user->email,
                ]);
            }else {
                return ['msg'=>'adresse email ne correspond pas au mot de passe', 'status_code' => 405];
            }
        }
    }
    
}
