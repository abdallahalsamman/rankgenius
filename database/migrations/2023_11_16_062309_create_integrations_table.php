<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('integration_type');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
        $this->addCheckConstraint();
    }

    protected function addCheckConstraint()
    {
        $sql = <<<SQL
            CREATE TRIGGER integrations_check
            BEFORE INSERT ON integrations
            FOR EACH ROW
            BEGIN
                SELECT CASE
                    WHEN NEW.integration_type IN ('wordpress', 'shopify') THEN
                        NULL
                    ELSE
                        RAISE(ABORT, 'Invalid integration_type');
                END;
            END;
        SQL;

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
