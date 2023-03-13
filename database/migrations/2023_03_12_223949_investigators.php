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
        Schema::create('investigators', function (Blueprint $table) {
            $table->string('orcid');
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->string('principal_email', 60)
                ->unique()
                ->nullable();

            $table->primary('orcid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigators');
    }
};
