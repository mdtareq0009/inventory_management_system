<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if(!in_array(auth()->user()->role, ['Super Admin', 'Admin'])){
            return redirect()->route('dashboard');
        }
        return view('admin.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:100', 'unique:users'],
            'branch_id' => ['required', 'integer'],
            'role'      => ['required', Rule::in(['Super Admin', 'Admin', 'Doctor', 'Entry User', 'User'])],
            'doctor_id' => ['nullable', 'integer'],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'confirmed'],
        ]);

        User::create([
            'name'       => $request->name,
            'username'   => $request->username,
            'branch_id'  => $request->branch_id,
            'role'       => $request->role,
            'doctor_id'  => $request->doctor_id,
            'password'   => Hash::make($request->password),
            'created_by' => auth()->user()->id,
            'ip_address' => $request->ip(),
        ]);
  
        return response()->json(['success' => true, 'message' => 'User Created!']);
        
        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'        => ['required', 'integer'],
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:100', Rule::unique('users')->ignore($request->id,'id')],
            'branch_id' => ['required', 'integer'],
            'role'      => ['required', Rule::in(['Super Admin', 'Admin', 'Doctor', 'Entry User', 'User'])],
            'doctor_id' => ['nullable', 'integer'],
            'password'  => ['nullable', 'confirmed'],
        ]);

        $data = array(
            'name'       => $request->name,
            'username'   => $request->username,
            'branch_id'  => $request->branch_id,
            'role'       => $request->role,
            'doctor_id'  => $request->doctor_id,
            'updated_by' => auth()->user()->id,
            'ip_address' => $request->ip(),
        );

        if(isset($request->password) && $request->password != '' && $request->password != null){
            $data['password'] = Hash::make($request->password);
            $data['logout']   = true;
        }

        User::where('id', $request->id)->update($data);

        return response()->json(['success' => true, 'message' => 'User Updated!']);
    }
    public function statusUpdate(Request $request)
    {
        $request->validate([
            'id'     => ['required', 'integer'],
            'status' => ['required', Rule::in(['a', 'd'])],
        ]);

        $data = array(
            'status'     => $request->status,
            'logout'     => true,
            'updated_by' => auth()->user()->id,
            'ip_address' => $request->ip(),
        );

        User::where('id', $request->id)->update($data);

        return response()->json(['success' => true, 'message' => 'Status Updated!']);
    }
}
