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
        Schema::create('flows', function (Blueprint $table) {
            $table->id();
            $table->string('uniqid')->unique();
            $table->string('name');
            $table->string('render_white_page')->default('local_file');
            $table->string('white_page_url');
            $table->string('render_bot_page')->default('local_file'); // header_redirect,meta_redirect,script_redirect,local_file 
            $table->string('bot_page_url');
            $table->string('render_offer_page')->default('header_redirect'); // header_redirect , meta_redirect,script_redirect,local_file.
            $table->string('offer_page_url');
            $table->json('allowed_countries')->nullable();
            $table->boolean('block_vpn')->default(false);
            $table->boolean('block_no_referer')->default(false);
            $table->boolean('allowed_params')->default(true);
            $table->boolean('acs')->default(false);
            $table->boolean('blocker_bots')->default(true);
            $table->text('lock_isp')->nullable();
            $table->text('lock_referers')->nullable();
            $table->json('lock_device')->nullable();
            $table->json('lock_browser')->nullable();

            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flows');
    }
};
