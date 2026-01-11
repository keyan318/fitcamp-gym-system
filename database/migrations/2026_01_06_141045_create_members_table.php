<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('members', function (Blueprint $table) {
    $table->id();
    $table->string('member_id')->unique();
    $table->string('full_name');
    $table->string('facebook_name');
    $table->string('email')->unique();

    $table->string('membership_type');
    $table->integer('valid_days');
    $table->date('start_date');
    $table->date('end_date');

    $table->string('id_photo');
    $table->string('status');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
