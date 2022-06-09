<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('permission_id');
            $table->boolean('is_default');
            $table->foreignId('group_permission_id')->nullable();
            $table->primary(['user_id', 'permission_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('group_permission_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
