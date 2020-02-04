<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->mediumText('photo')->default('img/avatar.png');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('users')->insert(
            array(
                'name' => 'Apurv',
                'email' => 'superadmin@fortius.com',
                'phone' => '7698686327',
                'password' => bcrypt('Admin123'),
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        $role = Role::create(['name' => 'superadmin', 'guard_name' => 'api']);
        $permission = Permission::create(['name' => 'view', 'guard_name' => 'api']);

        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $user = User::find(1);
        $user->assignRole('superadmin');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
