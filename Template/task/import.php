<div class="page-header">
    <h2><?= $this->text->e($project['name']) ?> &gt; <?= t('Add GLPI Ticket') ?></h2>
</div>

<?= $this->form->label(t('Ticket ID'), 'id') ?>
<?= $this->form->text('id', $values, array(), array('required', 'autofocus')) ?>
