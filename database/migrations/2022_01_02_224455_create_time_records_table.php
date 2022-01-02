<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_records', function (Blueprint $table) {
          self::addAuditablesToTable($table);

          // Conduit Fields


          // Dolibarr Fields
          $table->integer('dolibarr_id')->unique()->nullable();
          $table->integer('dolibarr_fkt_task')->nullable();
          $table->date('dolibarr_task_date')->nullable();
          $table->datetime('dolibarr_task_datehour')->nullable();
          $table->integer('dolibarr_task_duration')->nullable;
          $table->longText('dolibarr_note')->nullable();
          $table->integer('dolibarr_task_user')->nullable();
          $table->jsonb('dolibarr_task_json')->nullable();
          $table->datetime('dolibarr_last_sync')->nullable();

          // Quickbooks Time Fields
          $table->integer('qbt_id')->nullable();
          $table->datetime('qbt_last_sync')->nullable();
          $table->jsonb('qbt_json')->nullable();

          // Foreign Fields
          $table->foreign('dolibarr_task_user')->references('dolibarr_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_records');
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
