<?php if (isset($_SESSION['flash_success_message'])): ?>
    <div class="bg-green-100 text-center border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?= $_SESSION['flash_success_message'] ?></span>
        <?php unset($_SESSION['flash_success_message']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error_message'])): ?>
    <div class="bg-red-100 text-center border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">error!</strong>
        <span class="block sm:inline"><?= $_SESSION['flash_error_message'] ?></span>
        <?php unset($_SESSION['flash_error_message']); ?>
    </div>
<?php endif; ?>