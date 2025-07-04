<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchedCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matched_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company')->nullable();   
            $table->string('sheet_name')->nullable();     
            $table->string('corporate_number')->nullable();     
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
        Schema::dropIfExists('matched_companies');
    }
}
