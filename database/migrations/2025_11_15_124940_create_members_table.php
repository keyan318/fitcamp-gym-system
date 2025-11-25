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
        Schema::create('members', function (Blueprint $table) {
         //Tells laravel you want to make a table called members
            $table->id();
            $table->string('member_id')->unique();
            $table->string('full_name');
            $table->string('facebook_name');
            $table->string('email')->unique();
            $table->string('membership_plan');
            $table->string('id_photo');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
