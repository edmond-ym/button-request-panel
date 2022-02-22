<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MobileAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('mobile_access', function (Blueprint $table) {
            $table->string('case_id', 45)->unique()->primary();
            $table->string('access_token',100)->unique()/*->primary()*/;
            $table->string('user_id',45);
            $table->string('nickname', 45);
            $table->string('deleted_from_phone', 45)->nullable();
            $table->string('last_access', 45)->nullable();
            $table->string('mobile_info', 100)->nullable();
            $table->string('phone_token',45)->nullable();
        });
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
