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
                <input type="file" class="form-control form-control-sm" name="epserts-template-file-upload" id="epserts-template-file-upload" required="true">
                <button class="button button-primary">Загрузить</button>
            </div>
        </form>
    </div>
    <h4 class="my-5 text-center">Настройка шаблона</h4>
    <form action="" method="post">
        <?php wp_nonce_field('epserts-template-settings', 'epserts-template-settings-wpnp') ?>
        <div class="row centered-column-900">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="centered-column-400">
                    <h5 class="text-center my-3">Разрешение</h5>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                                <label for="epserts-template-width" class="form-label">Ширина:</label>
                                <input type="number" class="form-control form-control-sm" name="epserts-template-width" id="epserts-template-width" value="<?= apply_filters('epserts-template-width', 0) ?>">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                                <label for="epserts-template-height" class="form-label">Высота:</label>
                                <input type="number" class="form-control form-control-sm" name="epserts-template-height" id="epserts-template-height" value="<?= apply_filters('epserts-template-height', 0) ?>">
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center my-3">Координаты ФИО</h5>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                                <label for="epserts-template-coordinate-x" class="form-label">Координата X:</label>
                                <input type="number" class="form-control form-control-sm" name="epserts-template-coordinate-x" id="epserts-template-coordinate-x" value="<?= apply_filters('epserts-template-coordinate-x', 0) ?>">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                                <label for="epserts-template-coordinate-y" class="form-label">Координата Y:</label>
                                <input type="number" class="form-control form-control-sm" name="epserts-template-coordinate-y" id="epserts-template-coordinate-y" value="<?= apply_filters('epserts-template-coordinate-y', 0) ?>">
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center my-3">Настройки шрифта</h5>
                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="centered-column-400">
                    <h5 class="text-center my-3">Сопоставление метаданных</h5>
                    <div class="mb-3">
                        <label for="epserts-template-user-lastname" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" name="epserts-template-user-lastname" id="epserts-template-user-lastname" list="epserts-users-metadata" value="<?= apply_filters('epserts-template-user-lastname', '') ?>" placeholder="Фамилия">
                    </div>
                    <div class="mb-3">
                        <label for="epserts-template-user-name" class="form-label">Имя</label>
                        <input type="text" class="form-control" name="epserts-template-user-name" id="epserts-template-user-name" list="epserts-users-metadata" value="<?= apply_filters('epserts-template-user-name', '') ?>" placeholder="Имя">
                    </div>
                    <div class="mb-3">
                        <label for="epserts-template-user-middlename" class="form-label">Отчество</label>
                        <input type="text" class="form-control" name="epserts-template-user-middlename" id="epserts-template-user-middlename" list="epserts-users-metadata" value="<?= apply_filters('epserts-template-user-middlename', '') ?>" placeholder="Отчество">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>