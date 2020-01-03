<h3><i class="fa fa-bug fa-fw"></i><?= t('GLPI Plugin') ?></h3>
<div class="panel">
    <?= $this->form->label(t('GLPI URL'), 'glpi_url') ?>
    <?= $this->form->text('glpi_url', $values) ?>

    <?= $this->form->label(t('GLPI Username'), 'glpi_username') ?>
    <?= $this->form->text('glpi_username', $values) ?>

    <?= $this->form->label(t('GLPI password'), 'glpi_password') ?>
    <?= $this->form->password('glpi_password', $values) ?>

    <?= $this->form->label(t('GLPI User token'), 'glpi_user_token') ?>
    <?= $this->form->password('glpi_user_token', $values) ?>
    <p class="form-help"><?= t('If username and password is blank, User token is use for API authentication') ?></p>

    <?= $this->form->label(t('GLPI Application token'), 'glpi_app_token') ?>
    <?= $this->form->password('glpi_app_token', $values) ?>
    <p class="form-help"><?= t('Provide the "Application token" if required by the GLPI Application') ?></p>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
    </div>
</div>
