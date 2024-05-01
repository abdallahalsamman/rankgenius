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
        Schema::create('integration_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('integrations', function (Blueprint $table) {
            $table->foreignId('integration_type_id')->constrained();
        });

        $integrationTypes = [
            'wordpress',
            'shopify',
            'ghost',
        ];

        foreach ($integrationTypes as $integrationType) {
            \App\Models\IntegrationType::create([
                'name' => $integrationType,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integration_types');
    }
};
