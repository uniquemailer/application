<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_audits', function (Blueprint $table) {
            $table->id();
            $table->text('message');           
            $table->json('to')->nullable();
            $table->json('cc')->nullable();
            $table->string('subject')->nullable();  
            $table->string('service')->nullable();  
            $table->string('template')->nullable();  
            $table->string('transaction_id')->index()->nullable();                    
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
        Schema::dropIfExists('email_audits');
    }
}
