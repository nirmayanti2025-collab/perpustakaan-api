<?php Schema::create('log_aktivitas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->string('aktivitas');
    $table->text('keterangan');
    $table->timestamps();
});
