<?php if (!empty($success)) { ?>
    <div class="card p-3">
        <?php foreach ($success as $suc) { ?>
            <span class="text-success font-weight-bold">-&nbsp;<?= $suc ?></span>
        <?php } ?>
    </div>
    <br>
<?php } ?>