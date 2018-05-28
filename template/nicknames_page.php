<?php
/**
 * @var array $nickname_results
 * @var string $nicknames
 */
?>
<h3>Information about nicknames</h3>
<?php
foreach ( $nickname_results as $result ): ?>
    <p><strong></strong></p>
<?php endforeach; ?>
<hr>
<h3>Add your nicknames here</h3>

<form action="" method="POST">
    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="nicknames_list">Please enter here your nicknames: (One per line)</label>
        <textarea name="nicknames_list" id="nicknames_list" rows="5"><?php echo $nicknames; ?></textarea>
    </div>
    <div class="clear"></div>
    <br>
    <p>
		<?php wp_nonce_field( "saucal_retriever", "_wpnonce" ); ?>
        <button type="submit" class="button">Update</button>
    </p>
</form>
