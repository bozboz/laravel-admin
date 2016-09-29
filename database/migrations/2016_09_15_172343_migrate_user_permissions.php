<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MigrateUserPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function($table) {
            $table->unsignedInteger('user_id')->nullable()->change();
        });

        DB::statement('SET SESSION group_concat_max_len = 100000');
        $groups = DB::select("
            SELECT GROUP_CONCAT(id) AS users, CONCAT('[', permissions, ']') AS permissions
            FROM (
                SELECT users.id,
                    GROUP_CONCAT(
                        DISTINCT
                        CONCAT('{\"action\":\"', permissions.action, '\",\"param\":', COALESCE(permissions.param, 'NULL'), '}')
                        ORDER BY permissions.action, permissions.param
                    ) AS permissions
                FROM users, permissions
                WHERE users.id = permissions.user_id
                GROUP BY users.id
            ) AS roles
            GROUP BY permissions
        ");

        DB::beginTransaction();
        collect($groups)->each(function($group) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => $group->permissions,
                'created_at' => new Carbon,
                'updated_at' => new Carbon,
            ]);
            $permissions = collect(json_decode($group->permissions))->each(function($permission) use ($roleId) {
                DB::table('permissions')->insert([
                    'action' => $permission->action,
                    'param' => $permission->param,
                    'role_id' => $roleId,
                    'created_at' => new Carbon,
                    'updated_at' => new Carbon,
                ]);
            });
            DB::table('users')->whereIn('id', explode(',', $group->users))->update([
                'role_id' => $roleId,
            ]);
        });
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
