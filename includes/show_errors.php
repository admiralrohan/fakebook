<?php if (! empty($errors)) { ?>
    <div class="card p-3">
        <?php foreach ($errors as $error) { ?>
            <span class="text-danger font-weight-bold">-&nbsp;<?= $error ?></span>
        <?php } ?>
    </div>
    <br>
<?php } ?>