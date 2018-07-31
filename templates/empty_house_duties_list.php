<!-- empty_house_duties_list.php ssc plugin -->
<?php
if ( $data->config['error_messages'] == 'yes' && $data->output_data->get_errors() ) : ?>
	<div class='openclub_csv_error'>
		<h3><?php echo __( 'Errors', 'openclub_csv' ); ?></h3>
		<p class='openclub_csv'>
			<?php foreach ( $data->output_data->get_errors() as $line_number => $error ) { ?>
				Line: <?php echo ( $line_number + 1 ) . ' ' . $error ?>
				<br/>
			<?php } ?>
		</p>
	</div>
<?php endif;

/**
 * @todo smarter way to check for the lack of the group_by_field field. This will cause an endless loop
 */
if ( ! $data->config['group_by_field'] ) {
	echo __( 'No group by field', 'openclub_csv' );

	return;
}

$count = 1;

echo '<p>';

foreach ( $data->output_data->get_rows() as $grouped_field_value => $grouped_rows ) {

	foreach ( $grouped_rows as $row ) {

		if ( $count <= 3 ) {
			$row['class'] = 'openclub_csv_error bold';
		}

		echo '<span class = ' . $row['class'] . '>' .
		     $row['data']['Duty Date']['formatted_value'] . ',  ' .
		     $row['data']['Duty Time']['formatted_value'] . ',  ' .
		     $row['data']['Duty Type']['formatted_value'] . '</span><br/>';
	}
	$count ++;
}

echo '</p>';
