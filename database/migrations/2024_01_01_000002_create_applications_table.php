<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('company_name');
            $table->string('job_title');
            $table->string('job_url', 2048)->nullable();

            $table->enum('status', ['wishlist', 'applied', 'interview', 'offer', 'rejected'])
                  ->default('wishlist');
            $table->enum('priority', ['low', 'medium', 'high'])
                  ->default('medium');

            $table->date('applied_date')->nullable();
            $table->date('follow_up_date')->nullable();

            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();

            $table->string('location')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes for common filter queries
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'priority']);
            $table->index(['user_id', 'applied_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};