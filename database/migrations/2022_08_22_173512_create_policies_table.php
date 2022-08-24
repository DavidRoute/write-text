<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('RowCount');
            $table->string('VehiclePlateNumber');
            $table->string('Status');
            $table->string('CommencementDate');
            $table->string('PolicyEndDate');
            $table->string('PolicyHolderFullName');
            $table->string('PolicyIssuedDate');
            $table->string('PolicyNo');
            $table->string('PolicyHolderNRIC');
            $table->string('VehicleChasisNumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policies');
    }
};
