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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('maintenance_cost', 10, 2);
            $table->timestamp('maintenance_date')->useCurrent();
            $table->string('description')->nullable();
            $table->timestamps();
    
            // Foreign keys
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
