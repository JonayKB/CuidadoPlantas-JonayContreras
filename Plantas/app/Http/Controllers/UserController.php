<?php

namespace App\Http\Controllers;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function indexDashboard(){
        $trash = false;
        $userRepository = new UserRepository();
        $users = $userRepository->getPagination();
        return view('dashboard', compact('users','trash'));
    }
    public function edit($id){
        $userRepository = new UserRepository();
        $user = $userRepository->findById($id);
        if(!auth()->user()->roles->contains('name', 'admin')){
            return redirect('/')->with('error','Unathorized edition');
        }
        return view('editUser',compact('user'));
    }

    public function delete($id){
        $userRepository = new UserRepository();
        $userRepository->delete($id);
        return redirect()->route('dashboard')->with('message', 'User deleted correctly');
    }
    public function getTrash(){
        $trash = true;
        $userRepository = new UserRepository();
        $users = $userRepository->getOnlyTrash();
        return view('dashboard', compact('users','trash'));
    }
    public function restore($id){
        $userRepository = new UserRepository();
        $userRepository->restore($id);
        $trash = true;
        $users = $userRepository->getOnlyTrash();
        return view('dashboard',compact('users','trash'))->with('message', 'User restored correctly');
    }
    public function update(Request $request,$id){
        $userRepository = new UserRepository();
        $user = $userRepository->findById((int)$id);
        $name = $request->name;
        $password= $request->password;
        $email = $request->email;
        if(!empty($password)){
            $user->password = Hash::make($password);
        }
        $user->name = $name;
        $user->email = $email;
        $userRepository->update($user);

        return redirect()->route('dashboard')->with('message', 'User updated correctly');
    }

    public function getNotVerified(){
        $trash = false;
        $userRepository = new UserRepository();
        $users = $userRepository->getNotVerified();
        return view('dashboard', compact('users','trash'));
    }
    public function verifyUser($id){

        $userRepository = new UserRepository();
        $userRepository->verify($id);
        return redirect('dashboardVerification')->with('message', 'User verified correctly');
    }
}
