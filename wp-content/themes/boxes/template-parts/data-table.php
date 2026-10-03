<?php
$page_id = $args['page_id'];
?>
<section class="table-scroll" aria-labelledby="<?php echo esc_attr( $args['caption_id'] ); ?>" tabindex="0">
	<table class="data-table">
		<caption class="visually-hidden" id="<?php echo esc_attr( $args['caption_id'] ); ?>"><?php echo esc_html( boxes_text( 'table_caption', $page_id ) ); ?></caption>
		<thead>
			<tr>
				<?php foreach ( boxes_rows( 'table_columns', $page_id ) as $column ) : ?>
					<th scope="col"><?php echo esc_html( boxes_row_text( $column, 'label' ) ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( boxes_rows( 'table_rows', $page_id ) as $row ) : ?>
				<tr><th scope="row"><?php echo esc_html( boxes_row_text( $row, 'cell_1' ) ); ?></th><td><?php echo esc_html( boxes_row_text( $row, 'cell_2' ) ); ?></td><td><?php echo esc_html( boxes_row_text( $row, 'cell_3' ) ); ?></td></tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</section>
