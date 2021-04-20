<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Psy\CodeCleaner\UseStatementPass;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $usuarios = User::all();
        return $this->showAll($usuarios);
       // return response()->json(['data' => $usuarios], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        //$campos['verification_token'] = 'PbrxMkJNfGCfiDMeQPwFPj3FPYDEenjXog2Cp8S5';
        $campos['admin'] = User::USUARIO_REGULAR;
        //dd($campos);
        $usuario = User::create($campos);
        return response()->json(['data' => $usuario], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user){
        //$user = User::findOrFail($id);
        return response()->json(['data' => $user], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //$user = User::findOrFail($id);

        $reglas = [
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in'. User::USUARIO_REGULAR. ',' . User::USUARIO_REGULAR
        ];

        $this->validate($request, $reglas);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if($request->has('admin')){
            if(!$user->esVerificado()){
               // return response()->json(['error' => 'Unicamento los usuarios verificados pueden cambiar su valor de administrador', 'code' => 409], 409);
               return $this->errorResponse('Unicamento los usuarios verificados pueden cambiar su valor de administrador', 409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){
            return response()->json(['error' => 'Se debe especificar por lo menos un valor para actualizar', 'code' => 422], 422);
        }

        $user->save();

        return response()->json(['data' => $user], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['data' => $user], 200);
    }

    #http://127.0.0.1:8000/users/verify/827ccb0eea8a706c4c34a16891f84e7b
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;
        $user->save();

       return $this->successMessage('La cuenta ha sido verificada!');
    }


    /**
     * Remove the specified resource from storage.
     * http://127.0.0.1:8000/users/1/resend -> get
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resend(User $user)
    {
        if($user->esVerificado()){
            return $this->errorResponse('Este usuario ya ha sido verificado', 409);
        }

        #reintenta enviar hasta 5 veces por si llega a fallar
        retry(5, function() use ($user){
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->successMessage('El correo de verificacion se ha renenviado!');
    }

}
