<?php if (!empty($mensaje_exito)): ?>
    <div class="mensaje exito">
        ✓ <?php echo e($mensaje_exito); ?>
    </div>
<?php endif; ?>

<?php if (!empty($mensaje_error)): ?>
    <div class="mensaje error">
        ✗ <?php echo e($mensaje_error); ?>
    </div>
<?php endif; ?>
