<!-- grouped_date_table.php ssc plugin -->
<?php
echo \OpenClub\CSV_Display::template_output( $data, 'error_header' );?>

<table class='openclub_csv'>
	<tr>
		<th>
			<?php echo implode( '</th><th>', $data->output_data->get_header_fields() ); ?>
	</tr>
	<?php

	/**
	 * @todo smarter way to check for the lack of the group_by_field field. This will cause an endless loop
	 */
	if ( ! $data->config['group_by_field'] ) {
		echo __( 'No group by field', 'openclub_csv' );

		return;
	}

	$count = 1;
	foreach ( $data->output_data->get_rows() as $grouped_field_value => $grouped_rows ) {

		foreach ( $grouped_rows as $row ) {

			if ( $count <= 3 ) {
				$row['class'] = 'openclub_csv_error bold';
			}

			if ( $row['error'] == 0 || ( $row['error'] == 1 && $data->config['error_lines'] == 'yes' ) ) {
				echo "<tr  class='" . $row['class'] . "'>";
				foreach ( $row['data'] as $fieldname => $values ) {
					echo '<td>' . $values['formatted_value'] . '</td>';
				}
				echo "</tr>\n";
			}
		}
		$count ++;
	}
	?>
</table>
