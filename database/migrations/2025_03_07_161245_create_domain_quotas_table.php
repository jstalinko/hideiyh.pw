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
        Schema::create('domain_quotas', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->integer('user_id');
            $table->integer('current_domain_quota');
            $table->integer('amount');
            $table->integer('total_price');
            $table->integer('kode_unik');
            $table->enum('status',['pending','success','process','failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_quotas');
    }
};
