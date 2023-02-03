<?php

namespace App\Http\Controllers;


use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Mailable;
use Mail; 
use App\Models\UserVerify;
use Session;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use Carbon\Carbon;


class UserController extends Controller
{
 
   
   
    public function register(Request $request)
    {
           
        $regle = array(
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        );
        $validator = Validator::make($request->all(), $regle);
        
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        };
        
        $code = Str::random(6);
        
       $mail = Mail::send('text', ['token' => $code], function($message) use($request){
              $message->to($request->email);
              $message->subject("E-mail de vérification");
        });
        
        $user = new User();
        $user->email = $request ->email;
        $user->password = Hash::make($request ->password);
        $rep = $user -> save();
        
         if($rep && $mail){
            $userVerify = UserVerify::create([
                'user_id' => $user->id, 
                'token' => $code
            ]);
            
            
            
            $token = $user->createToken('API TOKEN')->plainTextToken;
            
            $user = User::findOrFail($user->id);
            $user->token = $token;
            $upUser = $user->update();
            
            if($upUser){
              return response()->json([
                'msg' => 'Utilisateur est bien crée',
                'status_code' => 201,
                'id' => $user->id,
                'token' => $user->token,
                'email' => $user->email,
                ]);   
            };
        }else {
            return ['msg'=>'echec'];
        };
    }

    
     
     //validation du code de verification de l'adresse mail
    public function updateUser(Request $request, $id){
        $regle = array(
            'code' => 'required|string|min:6|max:6'
        );
        $validator = Validator::make($request->all(), $regle);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        };
        
        $users = User::all();
        $user = User::findOrFail($id);
        $user->code = $request ->code;
        $upUser = $user->update();
        
        
        $select = UserVerify::where('user_id', $user->id)->get(['user_id','token']);
        $selectVerify = User::where('id', $user->id)->get(['is_email_verified']);
        

        
        if($user->code == $select[0]->token && $selectVerify[0]->is_email_verified == 0){
             Self::updateUserVerify($request, $user->id);
            return ['msg2'=>$select[0]->token, 'msg' => $user->code, 'msg1' => 'bien verifier'];
        }else {
             return ['msg1' => "echec d'authentification"];
        }
        
        
    } 
    
    
    //au cas ou il n'a pas recu le mail
    public function renvoiCode(Request $request, $id){
        $regle = array(
           'email' => 'required|string|email',
        );
        $validator = Validator::make($request->all(), $regle);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        };
        
        
        $token = Str::random(6);
        
        
       $mail = Mail::send('text', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject("E-mail de vérification");
        });
        
        
        if($mail){
             $select = UserVerify::findOrFail($id);
             $select->token = $token;
             $Verify = $select->update(); 
             
         if($Verify){
                 return response()->json([
                'msg' => 'code a été réenvoyé',
                'status_code' => 201,
                'id' => $id,
            ]);
         }else {
             return ['msg'=>'echec'];
         };
        }
    }
    
    
    //modifier la table user quand un utilisateur a verifier son compte
    public function updateUserVerify(Request $request, $id){
        
        
        $user = User::findOrFail($id);
        $user->is_email_verified = true;
        $upUser = $user->update();
       
       return ['msg1' => "utilisateur verifier"];
    }
    
    //afficher les infos d'un utilisateur
    public function get($id){
         $user = User::findOrFail($id);
         
         return $user;
         
    }
     
     //connection d'un utilisateur, proprietaire, gestionnaire...
    public function login(Request $request)
    {
        
        $regle = array(
           'email' => 'required|string',
           'password' => 'required|string'
        );
        $validator = Validator::make($request->all(), $regle);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        };
        
        $user = User::where('email', $request->email)->get();
     
        if ($user->count() == 0){
            return ['msg'=>'utilisateur nexiste pas', 'status_code' => 405];
        }else {
           
          if ($user[0]->is_email_verified == true && $user[0]->proprietaire == false && $user[0]->gestionnaire == false && $user[0]->superAdmin == false ){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
       
                return response()->json([
                    'msg' => 'Utilisateur connectée',
                    'status_code' => 200,
                    'token' => $user->token,
                    'id' => $user->id,
                    'email' => $user->email,
                ]);
            } 
            else{ 
                return response ([
                    'errors'=>'informations fausses',
                    'status_code'=>405
                    ]);
            }  
          }else if ($user[0]->is_email_verified == true && $user[0]->proprietaire == true){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
       
                return response()->json([
                    'msg' => 'Proprietaire connectée',
                    'status_code' => 200,
                    'token' => $user->token,
                    'id' => $user->id,
                    'email' => $user->email,
                ]);
            } 
            else{ 
                return response ([
                    'errors'=>'informations du proprietaire fausses',
                    'status_code'=>405
                    ]);
            }  
          }else if($user[0]->is_email_verified == true && $user[0]->gestionnaire == true){
              if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
       
                return response()->json([
                    'msg' => 'gestionnaire connectée',
                    'token' => $user->token,
                    'status_code' => 200,
                    'utilisateur' => $user->id,
                    'email' => $user->email,
                ]);
            } 
            else{ 
                return response ([
                    'errors'=>'informations du gestionnaire fausses',
                    'status_code'=>405
                    ]);
            } 
          }else if($user[0]->is_email_verified == true && $user[0]->superAdmin == true){
             if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
       
                return response()->json([
                    'msg' => 'superAdmin connectée',
                    'status_code' => 200,
                    'token' => $user->token,
                    'id' => $user->id,
                    'email' => $user->email,
                ]);
            } 
            else{ 
                return response ([
                    'errors'=>'informations du superAdmin fausses',
                    'status_code'=>405
                    ]);
            }  
          }else{
              return response ([
                    'errors'=>"vous n'avez pas valider votre adresse mail",
                    'status_code'=>405
                    ]);
          }
         
       
          
        }
      
    }
   
   //deconnection d'un utilisateur
    public function logout(Request $request)
    {
       $token = $request->user()->token();
        $token->revoke();
        return response([
            "message" => "vous etes déconnecter!",
             'status_code' => 202
            ]);
    }
    
    
     public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();
  
        $message = 'Sorry your email cannot be identified.';
  
        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;
              
            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Votre e-mail est vérifié. vous pouvez vous connecté maintenant.";
            } else {
                $message = "Votre e-mail est déjà vérifié. vous pouvez vous connecté maintenant.";
            }
        }
  
      return redirect()->route('login')->with('message', $message);
    }
    
    //pour le mot de passe oublier
    public function getEmail(Request $request){
            $regle = array(
                'email' => 'required|string|email',
             );
             $validator = Validator::make($request->all(), $regle);
             if($validator->fails()){
                 return response()->json($validator->errors(), 400);
             };

             $user = User::where('email', $request->email)->get(); 
           
        if($user){
            $token = Str::random(6);
            $body1 = 'Nous avons bien reçu votre demande de réinitialisation du mot de passe de votre compte';
            $body2 = 'Vous pouvez utiliser le code suivant pour récupérer votre compte:';
            $body3 = "La durée autorisée du code est d'une heure à partir du moment où le message a été envoyé. Merci!";
            
           
            $mail = Mail::send('mail', ['token' => $token, 'body1' => $body1, 'body2' => $body2, 'body3' => $body3], function($message) use($request){
                $message->to($request->email);
                $message->subject("mot de passe oublier");
          });
            
            $datetimes = Carbon::now()->format('Y-m-d H:i:s');
            PasswordReset::updateOrCreate([
                'email'=>$request->email,
                'token'=>$token,
                'created_at'=>$datetimes,
                ]);

                return response()->json(["message"=>'bien envoyer', 'msg'=>200, 'id'=>$user[0]->id]);
        }else{
            return response()->json(["message"=>'probleme avec email', 'msg'=>400]);
        }
        
      
    }

    //quand on a oublier son mot de passe
    public function renvoieCode(Request $request){
        $regle = array(
            'code' => 'required|string',
            'id' => 'required'
         );
         $validator = Validator::make($request->all(), $regle);
         if($validator->fails()){
             return response()->json($validator->errors(), 400);
         };

         $user = User::where('id', $request->id)->get(); 
         $ok = PasswordReset::where('token', $request->code)->get(); 
         
         if ($user[0]->email == $ok[0]->email)
         {
             return ['msg1' => "code bien recu", 'msg'=>200]; 
         }else {
              return ['msg1' => "l'utilisateur n'existe pas!"]; 
         }
         
        
    }

    //modifier le mot de passe pour y mettre le nouveau
    public function upPass(Request $request, $id){
        $regle = array(
            'password' => 'required|string|min:6'
         );
         $validator = Validator::make($request->all(), $regle);
         if($validator->fails()){
             return response()->json($validator->errors(), 400);
         };

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $upUser = $user->update();
       
       return ['msg1' => "utilisateur update", 'msg'=>200];
    }
    
}