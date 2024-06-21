<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pinjaman', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_pinjaman')->required();
            $table->foreign('id_pinjaman')->references('id_pinjaman')->on('pinjaman')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_users')->required();
            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('angsuran_ke_');
            $table->decimal('angsuran_pokok', 10, 2);
            $table->decimal('bunga', 10, 2);
            $table->decimal('subtotal_angsuran', 10, 2);
            $table->timestamp('tanggal_jatuh_tempo')->notNull()->useCurrent();
            $table->enum('status_pelunasan', ['Belum Lunas', 'Lunas', 'Lewat Jatuh Tempo']);
            $table->enum('keterangan', ['Tepat Waktu', 'Terlambat'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pinjaman');
    }
};