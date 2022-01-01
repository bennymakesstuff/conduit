<?php

namespace Database\Behaviors;

use Illuminate\Database\Schema\Blueprint;

class Auditable
{
    /**
     * Adds all fields that are used for auditing purposes
     * @param Blueprint $table
     * @return Blueprint
     */
    public static function addAuditablesToTable(Blueprint $table): Blueprint
    {
        $precision = 0;
        $table->id();
        $table->uuid()->unique();
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('updated_by')->references('id')->on('users');
        $table->timestamp('created_at', $precision)->nullable();
        $table->timestamp('updated_at', $precision)->nullable();
        $table->foreign('deleted_by')->references('id')->on('users');
        $table->softDeletes();
        return $table;
    }
}
