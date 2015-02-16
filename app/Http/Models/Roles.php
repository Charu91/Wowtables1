<?php namespace WowTables\Http\Models;

use DB;

class Roles {

    /**
     * @param $role_name string
     * @param $permissions array
     *
     * @return bool
     */
    public function add($role_name, array $permissions)
    {
        DB::beginTransaction();

        if(DB::table('roles')->where('name', $role_name)->count()){
            $role_id = DB::table('roles')->insertGetId(['name' => $role_name]);

            if($role_id){
                if(!empty($permissions)){
                    $role_permissions_map = [];
                    foreach($permissions as $permission){
                        $role_permissions_map[] = ['role_id' => $role_id, 'permission_id' => $permission];
                    }

                    if(DB::table('role_permissions')->insert($role_permissions_map)){
                        DB::commit();
                        return ['status' => 'success'];
                    }else{
                        DB::rollback();
                        return [
                            'status' => 'failure',
                            'action' => 'Mapping the role to its permissions',
                            'message' => 'There was a problem inserting the role. Please try again or contact the sys admin'
                        ];
                    }
                } else {
                    DB::commit();
                    return ['status' => 'success'];
                }
            }else{
                DB::rollback();
                return [
                    'status' => 'failure',
                    'action' => 'Insert the new role',
                    'message' => 'There was a problem inserting the role. Please try again or contact the sys admin'
                ];
            }
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Checking if the same role name exists',
                'message' => 'A role with the same name already exists'
            ];
        }
    }

    /**
     * @param $role_id
     * @param $permissions
     *
     * @return bool
     */
    public function update($role_id, $role_name, array $permissions)
    {
        DB::beginTransaction();

        DB::table('roles')->where('id', $role_id)->update(['name' => $role_name]);
        DB::table('role_permissions')->where('role_id', $role_id)->delete();

        if(count($permissions)){
            $role_permissions_map = [];

            foreach($permissions as $permission){
                $role_permissions_map[] = ['role_id' => $role_id, 'permission_id' => $permission];
            }

            if(DB::table('role_permissions')->insert($role_permissions_map)){
                DB::commit();
                return ['status' => 'success'];
            }else{
                DB::rollback();
                return [
                    'status' => 'failure',
                    'action' => 'Mapping the role to its permissions',
                    'message' => 'There was a problem inserting the role. Please try again or contact the sys admin'
                ];
            }
        }else {
            DB::commit();
            return ['status' => 'success'];
        }
    }

    /**
     * @param $role_id
     *
     * @return array
     */
    public function fetch($role_id)
    {
        $query = "
            SELECT
                IF(r.`id` = {$role_id}, r.`id`, null) AS `id`,
                IF(r.`id` = {$role_id}, r.`name`, null) AS `name`,
                p.`id` AS `permission_id`,
                p.`action` AS `permission_action`,
                p.`resource` AS `permission_resource`,
                IF(rp.`role_id` = {$role_id}, 1, 0) as `user_has_permissions`
            FROM permissions AS `p`
            LEFT JOIN role_permissions AS `rp` ON rp.`permission_id` = p.`id`
            LEFT JOIN roles as `r` ON rp.`role_id` = r.`id`
        ";

        $roleResult = DB::select($query);

        if($roleResult){
            $roleDetails = [];
            $permissions = [];
            $role_permissions = [];
            foreach($roleResult as $role){
                if($role->id && count($roleDetails)){
                    $roleDetails['id'] = $role->id;
                    $roleDetails['name'] = $role->name;
                }

                if(!isset($permissions[$role->permission_resource])){
                    $permissions[$role->permission_resource] = [];
                }

                $permissions[$role->permission_resource] = [
                    'action' => $role->permission_action,
                    'id' => $role->permission_id
                ];

                if($role->user_has_permissions){
                    $role_permissions[] = $role->permission_id;
                }
            }

            return [
                'status' => 'success',
                'role_data' => [
                    'role' => $roleDetails,
                    'permissions' => $permissions,
                    'role_permissions' => $role_permissions
                ]
            ];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Fetch all roles and permissions and mapping',
                'message' => 'There was a problem. Please try again or contact the sys admin'
            ];
        }
    }

    public function fetchAll()
    {
        return DB::table('roles')->select('id', 'name')->get();
    }

    public function remove($role_id){

        $userRoleCount = DB::table('users')->where('role_id', $role_id)->count();

        if(!$userRoleCount){
            DB::table('roles')->where('id', $role_id)->delete();

            return ['status' => 'success'];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Selecting the count of users with the given role id',
                'message' => 'The role cannot be deleted as there are users with the above mentioned role'
            ];
        }
    }
}