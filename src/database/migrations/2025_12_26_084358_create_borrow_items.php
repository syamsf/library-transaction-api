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
        Schema::create('borrow_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("borrow_id")->references("id")->on("borrows")->onDelete("cascade");
            $table->foreignId("book_id")->references("id")->on("books")->onDelete("cascade");
            $table->unsignedInteger("quantity")->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_items');
    }
};
