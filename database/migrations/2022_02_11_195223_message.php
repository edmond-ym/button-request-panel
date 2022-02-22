<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Message extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('message', function (Blueprint $table) {
            $table->string('msg_id', 100)->unique()->primary();
            $table->string('datetime', 45);
            $table->string('device_case_id',40);
            $table->text('message')->nullable();
            $table->string('pin', 20)->nullable();
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
