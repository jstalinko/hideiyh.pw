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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->boolean('feature_acs_cloaking_script')->default(false);
            $table->boolean('feature_api_geolocation')->default(false);
            $table->boolean('feature_api_blocker')->default(false);
            $table->integer('domain_quota')->default(5);
            $table->integer('visitor_quota_perday')->default(1000);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
