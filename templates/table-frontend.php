<?php
/**
 * The Template for displaying size chart table
 *
 * This template can be overridden by copying it to yourtheme/product-size-chart-for-woocommerce/table-frontend.php.
 *
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$table_class 		= array( 'wpb-psc-table' );
$thead_class 		= array( 'wpb-psc-thead' );
$table_type  		= wpb_psc_get_option( 'wpb_psc_table_type', 'wpb_psc_table_style', 'default' );
$table_head_type  	= wpb_psc_get_option( 'wpb_psc_table_head_type', 'wpb_psc_table_style', 'default' );

if( $table_type != 'default' ){
	$table_class[] = $table_type;
}
if( $table_type == 'no_style' ){
	$table_class = array( 'wpb-psc-table-theme-style' );
}

if( $table_type == 'default' ){
	$thead_class[] = $table_head_type;
}
?>

<table class="<?php echo esc_attr( implode(' ', $table_class) ); ?>">
	<?php if( isset($table['thead']) && !empty($table['thead']) ): ?>
		<thead class="<?php echo esc_attr( implode(' ', $thead_class) ); ?>">
			<tr>
				<?php foreach ($table['thead'] as $table_thead): ?>
				<th><?php echo esc_html( $table_thead ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
	<?php endif; ?>
	
	<?php if( isset($table['tbody']) && !empty($table['tbody']) ): ?>
		<tbody>
			<?php foreach ($table['tbody'] as $key => $table_tbody): ?>
				<tr>
					<?php if( isset($table_tbody) && !empty($table_tbody) ): ?>
						<?php foreach ($table_tbody as $table_tbody_item): ?>
							<td><?php echo esc_html( $table_tbody_item ); ?></td>
						<?php endforeach; ?>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	<?php endif; ?>
</table>