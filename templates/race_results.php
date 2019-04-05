<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_array( $data->results ) ) {
	echo "<!-- no results -->\n";

	return;
}
?>
<div class="results-list">
	<ol>
	<?php foreach ( $data->results as $item ) {
		echo sprintf( '<li><a href="%s">%s</a></li>',
			esc_url( $item->link ),
			esc_html( $item->friendly_path )
		);
	} ?>
	</ol>
</div>
<?php echo sprintf( "<a href='%s'>%s</a>", $data->more_link, $data->more_text );