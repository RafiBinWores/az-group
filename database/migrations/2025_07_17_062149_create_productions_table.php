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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('cutting_id')->constrained('cuttings')->onDelete('cascade');
            $table->foreignId('embroidery_print_id')->nullable()->constrained('embroidery_prints')->onDelete('set null');
            $table->foreignId('wash_id')->nullable()->constrained('washes')->onDelete('set null');
            $table->string('factory');
            $table->string('line');
            $table->integer('input')->nullable();
            $table->integer('total_input')->nullable();
            $table->integer('output')->nullable();
            $table->integer('total_output')->nullable();
            $table->date('date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
