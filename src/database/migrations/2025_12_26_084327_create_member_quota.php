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
        Schema::create('member_quota', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")->references("id")->on("users")->onDelete("cascade");
            $table->unsignedInteger("quota")->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_quota');
    }
};
