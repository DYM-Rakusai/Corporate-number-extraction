<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCorporateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_corporate_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('corporate_number')->nullable(); 
            $table->string('company')->nullable();        
            $table->string('prefectures')->nullable();
            $table->string('cities')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('all_corporate_data');
    }
}
