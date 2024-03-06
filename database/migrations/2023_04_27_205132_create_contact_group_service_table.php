<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_group_service', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('contact_group_id');
            $table->unique(['service_id', 'contact_group_id']);
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('contact_group_id')->references('id')->on('contact_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_group_service'); 
    }
};
