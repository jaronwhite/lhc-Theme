<?php


class RGM_PageHierarchyMenuWidget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'rgm-page-hierarchy',
			'description' => 'Display a list with links to a page and all children of that page'
		);
		parent::__construct( 'rgm_page_hierarchy_menu', __( 'Page Hierarchy Menu' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		$list = '';
		foreach (
			get_pages( array(
				'child_of'    => $instance['page_id'],
				'sort_order'  => 'asc',
				'sort_column' => 'menu_order',
			) ) as $t
		) { //need to get current page plus children one level down
			$lnk  = get_post_permalink($t->ID);
			$list .= "<li><a href='{$lnk}'>{$t->post_title}</a></li>";
		}

		$post      = get_post( $instance['page_id'] );
		$postTitle = $post->post_title;
		$postLink  = get_post_permalink($post->ID);
		$postStr   = "<a href='{$postLink}'>{$postTitle}</a>";

		echo $args['before_widget']
		     . $args['before_title'] . $postStr . $args['after_title']
		     . '<ul>'
		     . $list
		     . '</ul>'
		     . $args['after_widget'];
	}

	public function form( $instance ) {
		$defaults = array(
			'title'   => ':(',
			'page_id' => ''
		);

		$title  = $instance['title'];
		$pageId = $instance['page_id'];
		?>
        <p>
            <select id="<?php echo $this->get_field_id( 'page_id' ); ?>" class="widefat"
                    name="<?php echo $this->get_field_name( 'page_id' ); ?>">
				<?php
				$children = '<ul>';
				foreach ( get_pages() as $t ) {
					( $t->ID == $pageId ) ? $selected = 'selected' : $selected = '';
					echo "<option value='{$t->ID}' {$selected}>{$t->post_title}</option>";
					if ( $t->post_parent == $pageId ) {
						$children .= "<li>{$t->post_title}</li>";
					}
				}
				$children .= '</ul>';
				?>
            </select>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>" hidden>
        </p>
		<?php
		echo $children;
	}

	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = ( ! empty( $new_instance['page_id'] ) ) ? get_the_title( $new_instance['page_id'] ) : ':(';
		$instance['page_id'] = ( ! empty( $new_instance['page_id'] ) ) ? $new_instance['page_id'] : '';

		return $instance;
	}

}

add_action( 'widgets_init', 'rgm_page_hierarchy_widget_init' );
function rgm_page_hierarchy_widget_init() {
	register_widget( 'RGM_PageHierarchyMenuWidget' );
}

?>