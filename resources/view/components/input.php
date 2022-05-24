<div class="form-group row formele">
    <label for="input<?php echo ucfirst($data['name']); ?>"
           class="col-sm-2
           col-form-label
           <?php if ($data['error']) {echo ' text-danger';} elseif ($data['conf']) {echo 'text-success';} ?>">
        <?php echo $data['text']; ?>
    </label>
    <div class="col-sm-7">
        <input type="<?php echo $data['type'];?>"
               <?php if ($data['old']) {echo 'value="' .  $data['old'] . '"';} ?>
               name="<?php echo $data['name']; ?>"
               class="form-control
               <?php if ($data['error']) {echo ' is-invalid';} elseif ($data['conf']) {echo 'is-valid';} ?>"
               id="<?php echo $data['name']; ?>">
    </div>
    <div class="col-sm-3">
        <?php if($data['error']): ?>
            <small id="<?php echo $data['name']; ?>Help" class="text-danger">
                <?php echo $data['error']; ?>
            </small>
        <?php endif; ?>
    </div>
</div>
