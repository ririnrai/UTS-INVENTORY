<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->integer('no_barang')->unique();
            $table->integer('jumlah_barang');
            $table->enum('jenis_barang', ['Elektronik', 'Pakaian', 'Makanan', 'Alat Tulis', 'Kendaraan']);
            $table->date('tanggal_masuk_keluar');
            $table->enum('role', ['Admin', 'Petugas']);
            $table->integer('session');
            $table->time('timestamp');
            $table->dateTime('date_session');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
