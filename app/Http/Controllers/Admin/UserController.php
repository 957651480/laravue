<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminUserResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    const ITEM_PER_PAGE = 15;


    public function index(Request $request)
    {
        $searchParams = $request->all();
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role_id = Arr::get($searchParams, 'role_id', '');
        $keyword = Arr::get($searchParams, 'keyword', '');
        $has_open_id = Arr::get($searchParams,'has_open_id','');
        if (!empty($role_id)) {
            $userQuery->whereHas('roles', function($q) use ($role_id) { $q->where('id', $role_id); });
        }
        if (!empty($keyword)) {
            $userQuery->where('nickName', 'LIKE', '%' . $keyword . '%');
            //$userQuery->where('email', 'LIKE', '%' . $keyword . '%');
        }
        $paginator = $userQuery->paginate($limit);
        $data =[
            'total'=>$paginator->total(),
            'list'=>AdminUserResource::collection($paginator)
        ];
        return $this->renderSuccess('',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        list($data,$role_id,$images) = $this->validateUser($request->all());
        $user = DB::transaction(function ()use($data,$role_id) {
            $data['password']=Hash::make($data['password']);
            $user = User::create($data);
            $role = Role::where('id',$role_id)->first();
            throw_unless($role,ApiException::class,'角色不存在');
            $user->syncRoles($role);
            return $user;
        });
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->renderSuccess('',new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }
        /*if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }*/

        $currentUser = Auth::user();
        /*if (!$currentUser->isAdmin()
            && $currentUser->id !== $user->id
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_USER_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }*/
        if($name = $request->get('name')){
            $found = User::where('name', $name)->first();
            if ($found && $found->id !== $user->id) {
                return  $this->renderError('用户名已存在');
            }
        }
        $form = $request->only(['name','nickName','open_id']);
        if($password = $request->get('password')){
            $form['password'] =Hash::make($password);
        }
        $form['avatarUrl']=$request->get('avatar');
        if($form = array_filter($form)){
            foreach ($form as $key => $value) {
                $user->$key=$value;
            }
        }
        $user->save();
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function($permission) {
                return $permission['id'];
            },

            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not delete admin user'], 403);
        }

        try {
            $user->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(User $user)
    {
        $data = [
            'user' => PermissionResource::collection($user->getDirectPermissions()),
            'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
        ];
        return  $this->renderSuccess('',$data);
    }


    protected function validateUser($from)
    {
        $validator = \Validator::make($from,[
            'name'=>'required',
            'user_region'=>'sometimes',
            'password' => ['required', 'min:6'],
            'confirmPassword' => 'same:password',
            'role_id'=>'sometimes'
        ],
            [
                'name.required'=>'名称必填',
                'house_region.required'=>'区域必填',
            ]
        );
        throw_if($validator->fails(),ApiException::class,$validator->messages()->first());
        $data = $validator->getData();
        $data['city_id'] = $data['user_region'][1]??0;
        $images = Arr::pull($data,'images');
        $role_id = Arr::pull($data,'role_id');
        $confirmPassword = Arr::pull($data,'confirmPassword');
        return [$data,$role_id,$images];
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => $isNew ? 'required|unique:users' : 'required',
        ];
    }
}
