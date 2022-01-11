<?php
/**
 * The template for displaying meta box in page/post
 *
 * This adds Select Sidebar, Header Featured Image Options, Single Page/Post Image Layout
 * This is only for the design purpose and not used to save any content
 *
 * @package High_Responsive
 */



/**
 * Class to Renders and save metabox options
 *
 * @since High Responsive 1.0
 */
class Highresponsive_Metabox {
	private $meta_box;

	private $fields;

	/**
	* Constructor
	*
	* @since High Responsive 1.0
	*
	* @access public
	*
	*/
	public function __construct( $meta_box_id, $meta_box_title, $post_type ) {

		$this->meta_box = array (
							'id' 		=> $meta_box_id,
							'title' 	=> $meta_box_title,
							'post_type' => $post_type,
							);

		$this->fields = array(
			'highresponsive-header-image',
			//'highresponsive-sidebar-option',
			//'highresponsive-featured-image',
		);


		// Add metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add' ) );

		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	* Add Meta Box for multiple post types.
	*
	* @since High Responsive 1.0
	*
	* @access public
	*/
	public function add( $post_type ) {
		add_meta_box( $this->meta_box['id'], $this->meta_box['title'], array( $this, 'show' ), $post_type, 'side', 'high' );
	}

	/**
	* Renders metabox
	*
	* @since High Responsive 1.0
	*
	* @access public
	*/
	public function show() {
		global $post;

		$sidebar_options = array(
			'default-sidebar'        => esc_html__( 'Default Sidebar', 'high-responsive' ),
			'optional-sidebar-one'   => esc_html__( 'Optional Sidebar One', 'high-responsive' ),
			'optional-sidebar-two'   => esc_html__( 'Optional Sidebar Two', 'high-responsive' ),
			'optional-sidebar-three' => esc_html__( 'Optional Sidebar three', 'high-responsive' ),
		);

		$header_image_options 	= array(
			'default' => esc_html__( 'Default', 'high-responsive' ),
			'enable'  => esc_html__( 'Enable', 'high-responsive' ),
			'disable' => esc_html__( 'Disable', 'high-responsive' ),
		);
		//$featured_image_options	= Highresponsive_Metabox_featured_image_options();


	    // Use nonce for verification
	    wp_nonce_field( basename( __FILE__ ), 'highresponsive_custom_meta_box_nonce' );

	    // Begin the field table and loop  ?>
	   <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="highresponsive-header-image"><?php esc_html_e( 'Header Featured Image Options', 'high-responsive' ); ?></label></p>
		<select class="widefat" name="highresponsive-header-image" id="highresponsive-header-image">
			 <?php
				$meta_value = get_post_meta( $post->ID, 'highresponsive-header-image', true );
				
				if ( empty( $meta_value ) ){
					$meta_value='default';
				}
				
				foreach ( $header_image_options as $field =>$label ) {	
				?>
					<option value="<?php echo esc_attr( $field ); ?>" <?php selected( $meta_value, $field ); ?>><?php echo esc_html( $label ); ?></option>
				<?php
				} // end foreach
			?>
		</select>
		<?php
	}

	/**
	 * Save custom metabox data
	 *
	 * @action save_post
	 *
	 * @since High Responsive 1.0
	 *
	 * @access public
	 */
	public function save( $post_id ) {
		global $post_type;

		$post_type_object = get_post_type_object( $post_type );

	    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                      // Check Autosave
	    || ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        // Check Revision
	    || ( ! in_array( $post_type, $this->meta_box['post_type'] ) )                  // Check if current post type is supported.
	    || ( ! check_admin_referer( basename( __FILE__ ), 'highresponsive_custom_meta_box_nonce') )    // Check nonce - Security
	    || ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) )  // Check permission
	    {
	      return $post_id;
	    }

	    foreach ( $this->fields as $field ) {
			$new = $_POST[ $field ];

			delete_post_meta( $post_id, $field );

			if ( '' == $new || array() == $new ) {
				return;
			} else {
				if ( ! update_post_meta ( $post_id, $field, sanitize_key( $new ) ) ) {
					add_post_meta( $post_id, $field, sanitize_key( $new ), true );
				}
			}
		} // end foreach
	}
}

$Highresponsive_Metabox = new Highresponsive_Metabox(
	'highresponsive-options', 					//metabox id
	esc_html__( 'High Responsive Options', 'high-responsive' ), //metabox title
	array( 'page', 'post' )				//metabox post types
);
