<h3><i class="fa fa-bug fa-fw"></i><?= t('GLPI Plugin') ?></h3>
<div class="panel">
    <?= $this->form->label(t('GLPI URL'), 'glpi_url') ?>
    <?= $this->form->text('glpi_url', $values) ?>

    <?= $this->form->label(t('GLPI Username'), 'glpi_username') ?>
    <?= $this->form->text('glpi_username', $values) ?>

    <?= $this->form->label(t('GLPI password'), 'glpi_password') ?>
    <?= $this->form->password('glpi_password', $values) ?>

    <?= $this->form->label(t('GLPI User token'), 'glpi_password') ?>
    <?= $this->form->password('glpi_user_token', $values) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
    </div>
</div>
