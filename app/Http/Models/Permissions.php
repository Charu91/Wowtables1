<?php namespace WowTables\Http\Models;

use DB;

class Permissions {

    /**
     * Map of all permissions to its main group
     *
     * @var array
     */
    protected $permissions = [];

    public function fetchAll(){
        $permissionResults = DB::table('permissions')->select('id', 'action', 'resource')->get();

        if($permissionResults){
            $permissions = [];
            foreach($permissionResults as $permission){
                if(!isset($permissions[$permission->resource])){
                    $permissions[$permission->resource] = [];
                }

                $permissions[$permission->resource][] = [
                    'id' => $permission->id,
                    'action' => $permission->action
                ];
            }

            return [
                'status' => 'success',
                'permissions' => $permissions
            ];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Fetch all permissions from the permissions table',
                'message' => 'No permissions were returned. Please contact the sys admin'
            ];
        }
    }


    public function add($action, $resource)
    {
        $query = 'INSERT IGNORE INTO permissions (`action`, `resource`) VALUES (?, ?)';

        if(DB::insert($query, [$action, $resource])){
            return ['status' => 'success'];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Add a new permission into the system',
                'message' => 'The permission was not inserted. Please contact the sys admin'
            ];
        }
    }

    public function remove($permission_id)
    {
        $rolePermissionMapCount = DB::table('role_permissions')->where('permission_id', $permission_id)->count();

        if(!$rolePermissionMapCount){
            DB::table('permissions')->where('id', $permission_id)->delete();

            return ['status' => 'success'];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check if the permission is mapped to any existing roles',
                'message' => 'The permission cannot be deleted as it is mapped to existing roles'
            ];
        }
    }
}