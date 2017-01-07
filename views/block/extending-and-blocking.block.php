<?= $this->extend('layout-with-block') ?>

<?= $this->section('content') ?>
  <?= $this->parent() ?>
  <p>
    <?= $e($message) ?>
  </p>
<?= $this->stop() ?>