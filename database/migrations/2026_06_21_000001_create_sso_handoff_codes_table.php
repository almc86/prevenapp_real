<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SSO handoff codes: single-use, short-lived codes used to hand a user off
 * from the Laravel login (identity provider) to the React/NestJS app without
 * ever putting a token in the URL.
 *
 * Flow: Laravel creates a row, redirects to {frontend}/auth/callback?code=...,
 * NestJS exchanges the code (POST /auth/sso) for its own JWT and marks it used.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sso_handoff_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 128)->unique();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['code', 'used_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sso_handoff_codes');
    }
};
