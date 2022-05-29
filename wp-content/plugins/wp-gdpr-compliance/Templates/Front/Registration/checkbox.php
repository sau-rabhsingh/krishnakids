<?php

/**
 * @var string $name
 * @var string $label
 */

?>

<p>
	<label>
		<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1"/> <?php echo esc_html( $label ); ?>
	</label>
</p>
<br>
