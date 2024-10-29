<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('can_have_invoice')->default(true)->after('village_id');
            $table->boolean('can_have_maintenance')->default(true)->after('can_have_invoice');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['can_have_invoice', 'can_have_maintenance']);
        });
    }
};
