<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeviceList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('device_list', function (Blueprint $table) {
            
            $table->string('case_id', 40)->unique()->primary();
            $table->string('device_id', 45)->unique();
            $table->string('bearer_token', 200);
            $table->string('datetime', 45);
            
            $table->string('nickname', 45);
            $table->string('repeated_message', 45)->nullable();
            $table->string('status', 45);
            //$table->string('user_id', 45);
            $table->bigInteger('user_id');
            $table->text('info')->nullable();

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
