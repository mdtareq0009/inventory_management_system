<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->id();
            $table->string('name');
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->enum('role', ['Admin', 'General'])->default('General')->index();
            $table->longText('permissions')->nullable();
            $table->rememberToken();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('ip_address', 100)->nullable();
            $table->char('status', 2)->default('a')->comment('a=active, d=deactive')->index();
            $table->integer('branch_id')->default(1)->index();
            $table->boolean('logout')->default(false);
        });

        User::create(
            array(
                'name'      => 'Admin',
                'username'  => 'admin@gmail.com',
                'role'      => 'Admin',
                'password'  => bcrypt(1),
                'branch_id' => 1,
            )
        );
       
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
