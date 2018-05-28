<?php
/**
 * @var array $nickname_results
 * @var string $nicknames
 */
?>
<h3><?php _e( "Information about nicknames", "saucal" ); ?></h3>
<?php
foreach ( $nickname_results as $key => $result ): ?>
    <p><strong><?php echo $key; ?>:</strong> <?php echo $result; ?></p>
<?php endforeach; ?>
<hr>
<h3><?php _e( "Add your nicknames here", "saucal" ); ?></h3>

<form action="" method="POST">
    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="nicknames_list"><?php _e( "Please enter here your nicknames: (One per line)", "saucal" ); ?></label>
        <textarea name="nicknames_list" id="nicknames_list" rows="5"><?php echo $nicknames; ?></textarea>
    </div>
    <div class="clear"></div>
    <br>
    <p>
		<?php wp_nonce_field( "saucal_retriever", "_wpnonce" ); ?>
        <button type="submit" class="button"><?php _e( "Update", "saucal" ); ?></button>
    </p>
</form>
