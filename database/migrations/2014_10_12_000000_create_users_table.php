<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            // Conduit Fields
            self::addAuditablesToTable($table);
            $table->rememberToken();
            $table->datetime('last_conduit_login')->nullable();

            $table->boolean('conduit_user')->default(true)->nullable(false);
            $table->json('roles')->nullable();

            $table->string('email')->unique()->nullable();       // Synced from Dolibarr
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');              // Synced from Dolibarr

            $table->string('firstname')->nullable(); // Synced from Dolibarr
            $table->string('lastname')->nullable();  // Synced from Dolibarr
            $table->integer('mobile')->nullable();   // Synced from Dolibarr

            // Dolibarr Fields
            $table->string('dolibarr_username')->nullable();
            $table->integer('dolibarr_id')->unique()->nullable();
            $table->integer('dolibarr_entity')->nullable();
            $table->jsonb('dolibarr_json')->nullable();
            $table->datetime('dolibarr_last_login')->nullable();
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
        Schema::dropIfExists('users');
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
