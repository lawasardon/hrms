<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_number')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->decimal('sss', 10, 2)->nullable();
            $table->decimal('pag_ibig', 10, 2)->nullable();
            $table->decimal('phil_health', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_number')->references('id_number')->on('employee')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
            $table->foreign('payroll_id')->references('id')->on('payroll')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deduction');
    }
}
