<h1><?= _xls_get_conf('ENABLE_FAMILIES_MENU_LABEL') ?></h1>
<div class="familylist">
    <ul>
    <?php foreach ($model as $family): ?>
        <li><a href="<?= $family->request_url ?>" ><?= $family->family ?></a></li>
    <?php endforeach; ?>
    </ul>
</div>