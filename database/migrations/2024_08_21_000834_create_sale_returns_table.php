<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales');
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('return_date')->index();
            $table->float('total_amount');
            $table->text('remark')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('ip_address', 100)->nullable();
            $table->char('status', 2)->default('a')->comment('a=active, d=deactive')->index();
            $table->integer('branch_id')->default(1)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_returns');
    }
}
