<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/vittfiles
 * @since      1.0.0
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/includes
 * @author     Vittfiles <email@email.com>
 */
class Vitt_Attribute_Hierarchy_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.2.0
	 * @access   protected
	 * @var      Vitt_Attribute_Hierarchy_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.2.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.2.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		if ( defined( 'VITT_ATTRIBUTE_HIERARCHY_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = VITT_ATTRIBUTE_HIERARCHY_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.2.0';
		}
		$this->plugin_name = 'vitt-attribute-hierarchy-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Vitt_Attribute_Hierarchy_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Vitt_Attribute_Hierarchy_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Vitt_Attribute_Hierarchy_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Vitt_Attribute_Hierarchy_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.2.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vitt-attribute-hierarchy-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vitt-attribute-hierarchy-for-woocommerce-i18n.php';
		
		/**
		 * The class responsible to show the plugin menu in the admin page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vitt-attribute-hierarchy-menu.php';
		
		/**
		 * The class responsible to show the plugin sub menu in the admin page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vitt-attribute-hierarchy-sub-menu.php';
		
		/**
		 * The class responsible to create a page to delete an attribute connection
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vittfiles-ahfw-delete-connection-page.php';
		
		/**
		 * The class responsible for define the responses for the admin menu page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vittfiles-ahfw-menu-responses.php';
		
		/**
		 * The class responsible for define the responses for the admin sub menu page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vittfiles-ahfw-sub-menu-responses.php';
		
		/**
		 * The class responsible for define the responses for the admin sub menu page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vittfiles-ahfw-custom-sittings-products.php';

		$this->loader = new Vitt_Attribute_Hierarchy_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Vitt_Attribute_Hierarchy_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.2.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Vitt_Attribute_Hierarchy_For_Woocommerce_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.2.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$menu = new Vitt_Attribute_Hierarchy_Menu( $this->get_plugin_name());

		$sub_menu = new Vitt_Attribute_Hierarchy_Sub_Menu( $this->get_plugin_name());

		$sub_menu_delete = new Vittfiles_Ahfw_Delete_Connection_Page();

		$menu_responses = new Vittfiles_Ahfw_Menu_Responses();

		$sub_menu_responses = new Vittfiles_Ahfw_Sub_Menu_Responses();

		$custom_sittings_products = new Vittfiles_Ahfw_Custom_Sittings_Products();

		if(isset($_GET['page'])){
			if( $sub_menu->get_menu_slug() == trim(sanitize_text_field($_GET['page']))){
				$this->loader->add_action( 'admin_enqueue_scripts', $sub_menu, 'enqueue_styles');
				$this->loader->add_action( 'admin_enqueue_scripts', $sub_menu, 'enqueue_scripts');
			}else if( $menu->get_menu_slug() == trim(sanitize_text_field($_GET['page']))){
				$this->loader->add_action( 'admin_enqueue_scripts', $menu, 'enqueue_scripts');
			}
		}

		$this->loader->add_action( 'admin_menu', $menu, 'add_menu' );
		$this->loader->add_action( 'admin_menu', $sub_menu, 'add_sub_menu' );
		$this->loader->add_action( 'admin_menu', $sub_menu_delete, 'add_sub_menu' );
		$this->loader->add_action( $menu_responses->action, $menu_responses, 'create_attribute_connection' );
		$this->loader->add_action( $sub_menu_responses->action, $sub_menu_responses, 'rest_api_update_attributes' );
		$this->loader->add_action( $custom_sittings_products->action, $custom_sittings_products, 'save_my_custom_settings' );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.2.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Vitt_Attribute_Hierarchy_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
