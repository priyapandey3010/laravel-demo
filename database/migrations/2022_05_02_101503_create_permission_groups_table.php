<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->foreignId('group_permission_id');
            $table->foreignId('permission_id');
            $table->primary(['group_permission_id', 'permission_id']);
            $table->foreign('group_permission_id')->references('id')->on('permissions');
            $table->foreign('permission_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_groups');
    }
}
