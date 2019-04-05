<?php

add_action( 'wp_head', function () {
	?>
	<style>

		table.ssc-safety-team-table {
			width: 400px;
			margin: 1em;
			float: left;
			margin-bottom: 1.5em;
		}

		table.ssc-safety-team-table th {
			background-color: #EFEFEF;
			font-size: 1.1em;
			padding: 0.5em 0.5em 0.5em 0.5em;
		}

		table.ssc-safety-team-table tr.ssc-safety-teams-ro td {
			font-weight: bold;
			font-size: 0.9em;
			padding: 0.2em 0.2em 0.2em 0.5em;
		}

		table.ssc-safety-team-table td {
			font-size: 0.9em;
			padding: 0.2em 0.2em 0.2em 0.5em;
		}

		p.ssc_safety_teams_team_link {
			font-size: 1.2em;
		}


		.results-list ol {
			padding-left: 0;
			margin-left: 0;
		}
	</style>
	<?php
} );
