<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('monthly_rate')->nullable();
            $table->integer('rate_perday')->nullable();
            $table->string('duration')->nullable();
            $table->integer('total_working_days')->nullable();
            $table->integer('over_time')->nullable();
            $table->integer('salary')->nullable();
            $table->enum('status', ['pending', 'paid', 'hold'])->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll');
    }
}
