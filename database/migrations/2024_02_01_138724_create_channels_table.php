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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(\App\Models\Office\Area::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(\App\Models\Office\Branch::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('manage_id');
            $table->string('manage_type');
            $table->enum('type', ['ATM', 'CRM'])->default('ATM');
            $table->string('manage_by');
            $table->string('brand');
            $table->string('brand_vendor');
            $table->string('sn');

            $table->string('location');
            $table->string('location_category');
            $table->string('location_category_group');
            $table->enum('site', ['ONSITE', 'OFFSITE'])->default('ONSITE');

            $table->date('live_date')->nullable();
            $table->string('warranty')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
