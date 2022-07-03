<?php

namespace App\Http\Controllers;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\UserRole;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{

    use FileSaver;

    public function __construct()
    {
        parent::__construct();
    }


    public function index(Request $request)
    {
        $users = User::with('user_role.role', 'company')->companies()->notAdmin()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('user_role.role', function ($qr) use($request) {
                        $qr->where('name', 'LIKE', "%{$request->search}%");
                    });
            })
            ->paginate(30);

        return view('admin.user.users.index', compact('users'));
    }

    public function create()
    {
        $this_role_id = UserRole::where('user_id', auth()->id())->firstOrFail()->role_id;

        if($this_role_id == 1) {
            $companies = Company::orderBy('id', 'asc')->pluck('name', 'id');
            $roles = Role::where('id', '>=', 2)->get();
        } else {
            $companies = null;
            $roles = Role::where('fk_company_id', companyId())->where('id', '>=', 2)->get();
        }

        return view('admin.user.users.create', compact('roles', 'companies', 'this_role_id'));
    }

    public function store(Request $request)
    {
        // validate data
        $this->makeValidation();

        try {
            $data['password'] = Hash::make($request->password);
            // store user to users table
            $user = User::create($request->except(['role_id', 'confirm_password']));

            // store user role to user roles table
            $this->createUserRole($request, $user);

            // <!-- update user image -->
            $this->uploadFileWithResize($request->image, $user, 'image', 'uploads/users');

            return redirect()->route('users.index')->withSuccess('User successfully created!');
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    private function createUserRole($request, $user)
    {
        $user_role = UserRole::create([
            'fk_company_id' => $request->fk_company_id,
            'user_id'       => $user->id,
            'role_id'       => $request->role_id
        ]);
    }

    private function makeValidation()
    {
        \request()->validate([
            'name'              => 'required|unique:users,name',
            'phone'             => 'required|unique:users,phone',
            'email'             => 'required|unique:users,email',
            'role_id'           => 'required',
            'password'          => 'required|min:4',
            'confirm_password'  => 'same:password'
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.user.users.show');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if($user->id == Auth::user()->id || Auth::user()->email == 'admin@gmail.com'){
            $roles      = Role::where('fk_company_id', companyId())->where('id', '>=', 2)->get();
            $companies  = Company::pluck('name', 'id');
            return view('admin.user.users.edit', compact(['user', 'roles', 'companies']));
        }
        else{
            return back()->withError('You Are Not Eligible to Access This Location');
        }
    }
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name'  => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id
        ]);

        try {
            // <!-- update user info -->
            $user->update($request->only(['name', 'email', 'phone', 'fk_company_id']));

            // <!-- update user image -->
            $this->uploadFileWithResize($request->image, $user, 'image', 'uploads/users');

            // <!-- update/create user role -->
            if ($request->filled('role_id')) {
                if($user->user_role) {
                    $user->user_role()->update(['role_id' => $request->role_id]);
                } else {
                    $this->createUserRole($request, $user);
                }
            }
            return redirect()->route('users.index')->withSuccess('User Updated Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }


    public function destroy(User $user)
    {
        try{
            DB::transaction(function () use ($user) {
                $imagePath = $user->image;
                UserRole::where('user_id', $user->id)->delete();
                $user->delete();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            });
            return redirect()->back()->withSuccess('User Deleted Successfully!');
        }catch (\Exception $ex) {
            return redirect()->back()->withError('Used in another table Can Not delete it');
        }
    }

    public function changeStatus(User $user)
    {
        $user->update(['status' => $user->status ? 0 : 1]);
        return redirect()->back()->withSuccess('Status successfully changed!');
    }

    public function changePassword(Request $request)
    {
        $request->validate(['password' => 'required|min:4']);

        try {
            User::find($request->user_id_edit)->update(['password' => \Hash::make($request->password)]);
            return redirect()->route('users.index')->withSuccess('User Password Changed Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()-withError($ex->getMessage());
        }
    }
}
