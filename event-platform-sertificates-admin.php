<?php if (!defined('ABSPATH')) die ?>
<div class="container-fluid">
    <h1 class="h3 text-center my-5">Настройки сертификата участника</h1>
    <div class="centered-column-400">
        <h4 class="my-3 text-center">Загрузка файла шаблона</h4>
        <p class="text-center">Файл загружен: <?= apply_filters('epserts-file-uploaded', 'нет') ?>.</p>
        <form action="" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('epserts-template-upload', 'epserts-template-upload-wpnp') ?>
            <label for="epserts-template-file-upload" class="form-label">Загрузить новый:</label>
            <div class="mb-3 input-group">
                <input type="file" class="form-control form-control-sm" name="epserts-template-file-upload" id="epserts-template-file-upload">
                <button class="button button-primary">Загрузить</button>
            </div>
        </form>
    </div>
</div>