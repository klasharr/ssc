<!-- safety-teams.php in ssc plugin -->

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
<?php endif; ?>
<p>
	<?php
	/**
	 * @todo smarter way to check for the lack of the group_by_field field. This will cause an endless loop
	 */
	if ( ! $data->config['group_by_field'] ) {
		echo __( 'No group by field', 'openclub_csv' );

		return;
	}

	$race_officers = $data->output_data->get_rows( 'race_officers' );


	/**
	 * @see ssc_prep_safety_teams_shortcode_data() which is where data for this template is prepared
	 *
	 * $team_day = Weekend or Thursday
	 * $grouped_field_value = a Team e.g 1,2,3,4,A,B,C,D
	 */
	foreach ( $data->output_data->get_rows( 'teams' ) as $team_day => $grouped_field_group ) {

		echo "<a name='$team_day'></a>";
		echo "<h3>$team_day Safety Teams</h3>";

		/**
		 * $grouped_field_value = a Team e.g 1,2,3,4,A,B,C,D
		 * $grouped_rows = members of the team
		 *
		 */
		foreach ( $grouped_field_group as $grouped_field_value => $grouped_rows ) {

			echo "<table class='ssc-safety-team-table'>";
			echo "<thead><tr><th colspan='2'>Team $grouped_field_value</th><th>Role</th></tr></thead>";

			foreach ( $grouped_rows as $row ) {

				if ( $row['error'] == 0 || ( $row['error'] == 1 && $data->config['error_lines'] == 'yes' ) ) {

					echo "<tr class='" . $row['class'] . " '>";
					echo "<td>" . $row['data']['First Name']['formatted_value'] . "</td>";
					echo "<td>" . $row['data']['Second name']['formatted_value'] . "</td>";
					echo "<td>" . $row['data']['type']['formatted_value'] . "</td>";
					echo "</tr>";
				}
			}
			echo "</table>";
		}

		echo "<div style='clear: both;'>";
		echo '<h3>' . $team_day . ' Race Officers</h3><p>';

		foreach ( $race_officers[ $team_day ] as $ro ) {

			esc_html_e( $ro['data']['Team']['formatted_value'] . '. ' . $ro['data']['First Name']['formatted_value'] . '  ' . $ro['data']['Second name']['formatted_value'] . ', ' );

			if ( is_user_logged_in() ) {

				echo '<a href="mailto:' . $ro['data']['Email Address']['formatted_value'] . '">';
				echo $ro['data']['Email Address']['formatted_value'] . '</a>';

			} else {
				echo '<span style="color: gray">log in to see contact details</span>';
			}
			echo '<br/>';
		}
		echo '</p>';

		echo '</div>';
	}
	?>
</p>


