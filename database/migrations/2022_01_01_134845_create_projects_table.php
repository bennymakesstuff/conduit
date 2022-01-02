<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {

            self::addAuditablesToTable($table);

          // Dolibarr Fields
            $table->string('title', 255)->nullable();
            $table->string('dolibarr_ref', 50)->nullable();
            $table->longText('description')->nullable;
            $table->integer('dolibarr_id')->unique()->nullable();
            $table->integer('dolibarr_entity')->nullable();
            $table->jsonb('dolibarr_json')->nullable();
            $table->datetime('dolibarr_last_sync')->nullable();

          // Quickbooks Time Fields
            $table->integer('qbt_id')->nullable();
            $table->datetime('qbt_last_sync')->nullable();
            $table->jsonb('qbt_json')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }

    /**
     * Adds all fields that are used for auditing purposes
     * @param Blueprint $table
     * @return Blueprint
     */
    public static function addAuditablesToTable(Blueprint $table): Blueprint
    {
        $precision = 0;
        $table->id();
        $table->uuid('uuid')->unique();
        $table->integer('created_by')->nullable();
        $table->integer('updated_by')->nullable();
        $table->integer('deleted_by')->nullable();
        $table->timestamp('created_at', $precision)->nullable();
        $table->timestamp('updated_at', $precision)->nullable();
        $table->softDeletes();

        // Add foreign constraints
        $table->foreign('deleted_by')->references('id')->on('users');
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('updated_by')->references('id')->on('users');
        return $table;
    }
}
