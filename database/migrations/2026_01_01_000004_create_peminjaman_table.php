<?php Schema::create('peminjaman', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->foreignId('buku_id');
    $table->date('tanggal_pinjam');
    $table->date('tanggal_kembali')->nullable();
    $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
    $table->timestamps();
});
