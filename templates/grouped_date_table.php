<!-- grouped_date_table.php ssc plugin -->
<?php
if ( 'yes' === $data->config['error_messages'] && $data->output_data->get_errors() ) : ?>
	<div class='openclub_csv_error'>
		<h3><?php esc_html_e( 'Errors', 'openclub_csv' ); ?></h3>
		<p class='openclub_csv'>
			<?php foreach ( $data->output_data->get_errors() as $line_number => $error ) {
				echo esc_html__( 'Line', 'openclub_csv' ) . ':' . esc_html( ( $line_number + 1 ) . ' ' . $error ) . '<br/>';
			} ?>
		</p>
	</div>
<?php endif;

/**
 * @see default template wp-content/plugins/openclub-csv/templates/future_past_toggle.php
 */
echo \OpenClub\CSV_Display::get_past_future_toggle_links( $data->config, 'events' );

// https://stackoverflow.com/questions/4586835/how-to-pass-extra-variables-in-url-with-wordpress

?>
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
		echo esc_html_e( 'No group by field', 'openclub_csv' );

		return;
	}

	foreach ( $data->output_data->get_rows() as $grouped_field_value => $grouped_rows ) {

		foreach ( $grouped_rows as $row ) {

			if ( $row['error'] == 0 || ( $row['error'] == 1 && $data->config['error_lines'] == 'yes' ) ) {
				echo "<tr  class='" . $row['class'] . "'>";
				foreach ( $row['data'] as $fieldname => $values ) {
					echo '<td>' . $values['formatted_value'] . '</td>';
				}
				echo "</tr>\n";
			}

		}
	}
	?>
</table>
