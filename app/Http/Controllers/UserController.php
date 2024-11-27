<?php

namespace App\Http\Controllers;

use App\Repository\PostRepository;
use App\Repository\RolRepository;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Returns to admins user dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexDashboard()
    {
        $userRepository = new UserRepository();
        $userNeedsVerification = $userRepository->getNotVerified()->total();
        $postRepository = new PostRepository();
        $postsReporteds = $postRepository->getReportedPosts()->total();
        $trash = false;
        $users = $userRepository->getPagination();
        return view('dashboard', compact('users', 'trash', 'userNeedsVerification', 'postsReporteds'));
    }
    /**
     * Returns to edit user view
     * @param mixed $id to edit
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $userRepository = new UserRepository();
        $rolRepository = new RolRepository();
        $roles = $rolRepository->findAll();
        $user = $userRepository->findById($id);
        if (!auth()->user()->roles->contains('name', 'admin')) {
            return redirect('/')->with('error', 'Unathorized edition');
        }
        return view('editUser', compact('user', 'roles'));
    }

    /**
     * Delete a user
     * @param mixed $id to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $userRepository = new UserRepository();
        $userRepository->delete($id);
        return redirect()->route('dashboard')->with('message', 'User deleted correctly');
    }
    /**
     * Returns to only deleted users to view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getTrash()
    {
        $userRepository = new UserRepository();
        $userNeedsVerification = $userRepository->getNotVerified()->total();
        $postRepository = new PostRepository();
        $postsReporteds = $postRepository->getReportedPosts()->total();
        $trash = true;
        $users = $userRepository->getOnlyTrash();
        return view('dashboard', compact('users', 'trash', 'userNeedsVerification', 'postsReporteds'));
    }
    /**
     * Restore a user
     * @param mixed $id user id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $userRepository = new UserRepository();
        $userRepository->restore($id);
        return redirect()->route('users.trash')->with('message', 'User restored correctly');
    }
    /**
     * Updates a user
     * @param Request $request request data
     * @param mixed $id user id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $userRepository = new UserRepository();
        $rolRepository = new RolRepository();
        $user = $userRepository->findById((int)$id);
        $name = $request->name;
        $password = $request->password;
        $email = $request->email;
        if (!empty($password)) {
            $user->password = Hash::make($password);
        }
        $user->name = $name;
        $user->email = $email;
        $user->roles()->detach();


        DB::connection($userRepository->connection2)->table('user_rol')
            ->where('user_id', $user->id)
            ->delete();

        try {
            DB::beginTransaction();
            foreach ($request->roles as $rol_id) {
                $rol = $rolRepository->findById($rol_id);
                $user->roles()->attach($rol);
                DB::connection($userRepository->connection2)->table('user_rol')->insert([
                    'user_id' => $user->id,
                    'rol_id' => $rol->id,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->route('dashboard')->with('message', 'Error updating user');
        }

        $userRepository->update($user);

        return redirect()->route('dashboard')->with('message', 'User updated correctly');
    }

    /**
     * Returns only not verified users to view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getNotVerified()
    {
        $userRepository = new UserRepository();
        $postRepository = new PostRepository();
        $postsReporteds = $postRepository->getReportedPosts()->total();
        $trash = false;
        $users = $userRepository->getNotVerified();
        $userNeedsVerification = $users->total();
        return view('dashboard', compact('users', 'trash', 'postsReporteds', 'userNeedsVerification'));
    }
    /**
     * Verifies a user
     * @param mixed $id user id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyUser($id)
    {

        $userRepository = new UserRepository();
        $userRepository->verify($id);
        return redirect('dashboardVerification')->with('message', 'User verified correctly');
    }
}
