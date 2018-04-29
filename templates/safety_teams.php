
<!-- safety-teams.php in ssc plugin -->

<?php

if( $data->config[ 'error_messages' ] == 'yes' && $data->output_data->get_errors()) : ?>
	<div class='openclub_csv_error'>
		<h3><?php echo __( 'Errors', 'openclub_csv' );?></h3>
		<p class='openclub_csv'>
			<?php foreach($data->output_data->get_errors() as $line_number => $error ) { ?>
				Line: <?php echo  ( $line_number + 1 ). ' ' . $error ?>
				<br/>
			<?php } ?>
		</p>
	</div>
<?php endif; ?>
<p>
	<?php
	/**
	 * @todo smarter way to check for the lack of the group_by_field field. This will cause an endless loop
	 */
	if( !$data->config[ 'group_by_field' ] ) {
		echo __( 'No group by field', 'openclub_csv' );
		return;
	}

	//echo "<p class='ssc_safety_teams_team_link'>Jump to a team: ";

	foreach ( $data->output_data->get_rows() as $grouped_field_value => $grouped_rows ) {
		//echo '<a href="#'.$grouped_field_value.'">'.$grouped_field_value.'</a> ';
	}
	//echo '</p>';
    echo '<h3>Weekend Teams</h3>';

	$thursday_heading = false;

	foreach ( $data->output_data->get_rows() as $grouped_field_value => $grouped_rows ) {

		if( is_string($grouped_field_value) && $thursday_heading == false ){
			echo '<h3 style="clear: both;">Thursday Teams</h3>';
			$thursday_heading = true;
		}

		//echo "<a name='" . $grouped_field_value . "'/></a>";
		echo "<table class='ssc-safety-team-table'>";
		echo "<thead><tr><th colspan='2'>Team $grouped_field_value</th><th>Role</th></tr></thead>";

		foreach ( $grouped_rows as $row ) {

			if ( $row['error'] == 0 || ( $row['error'] == 1 && $data->config['error_lines'] == 'yes' ) ) {

				echo "<tr class='". $row['class'] ." '>";
				echo "<td>" . $row['data']['First Name']['formatted_value'] . "</td>";
				echo "<td>" . $row['data']['Second name']['formatted_value'] . "</td>";
				echo "<td>" . $row['data']['type']['formatted_value'] . "</td>";
				echo "</tr>";

			}

		}

		echo "</table>";
	}
	?>
</p>


