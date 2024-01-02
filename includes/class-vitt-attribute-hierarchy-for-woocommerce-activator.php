<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/vittfiles
 * @since      1.0.0
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/includes
 * @author     Vittfiles <email@email.com>
 */
class Vitt_Attribute_Hierarchy_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.2.0
	 */
	public static function activate() {
		add_option( "attribute_connection", "");
	}

}
