<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeviceOwnershipShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_ownership_share', function (Blueprint $table) {
            
            $table->string('case_id', 255)->unique()->primary();
            $table->string('device_id', 45);
            $table->string('share_to_user_id', 20);
            $table->string('right', 45);
            $table->string('created_time', 60);

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
