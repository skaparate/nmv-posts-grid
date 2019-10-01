<?php
/**
 * Class to register the auto loadable classes and namespaces.
 *
 * @package Nicomv\PostsGrid\Includes.
 */

namespace Nicomv\PostsGrid\Includes;

/**
 * Registers namespaces and paths to auto load classes.
 */
class Auto_Loader {

	/**
	 * An associative array where the key is a namespace prefix and the value
	 * is an array of base directories for classes in that namespace.
	 *
	 * @var array
	 */
	protected $prefixes = array();

	/**
	 * Base namespace prepended to every path to register.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $base_namespace;

	/**
	 * Base directory used to prepend every path to register.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $base_dir;

	/**
	 * Creates an instance of this class with the specified base namespace and base dir.
	 *
	 * @param string $base_namespace The base namespace to use.
	 * @param string $base_dir The base directory where to find the classes to register.
	 */
	public function __construct( $base_namespace = '', $base_dir = '' ) {
		$this->base_namespace = trim( $base_namespace, '\\' ) . '\\';
		$this->base_dir = rtrim( $base_dir, DIRECTORY_SEPARATOR ) . '/';
	}

	/**
	 * Register loader with SPL autoloader stack.
	 *
	 * @return void
	 */
	public function register() {
		spl_autoload_register( [ $this, 'load_class' ] );
	}

	/**
	 * Adds a base directory for a namespace prefix.
	 *
	 * @param string $prefix The namespace prefix.
	 * @param string $base_dir A base directory for class files in the
	 * namespace.
	 * @param bool   $prepend If true, prepend the base directory to the stack
	 *   instead of appending it; this causes it to be searched first rather
	 *   than last.
	 * @param bool   $override_defaults If true, the configured base_namespace and base_dir are not prepended
	 *   to the resulting prefix/base_dir.
	 * @return void
	 */
	public function add_namespace( $prefix, $base_dir, $prepend = false, $override_defaults = false ) {
		// Normalize namespace prefix.
		$prefix = trim( $prefix, '\\' ) . '\\';

		// Normalize the base directory with a trailing separator.
		$base_dir = rtrim( $base_dir, DIRECTORY_SEPARATOR ) . '/';

		if ( false === $override_defaults ) {
			$prefix = $this->base_namespace . $prefix;
			$base_dir = $this->base_dir . $base_dir;
		}

		// Initialize the namespace prefix array.
		if ( isset( $this->prefixes[ $prefix ] ) === false ) {
			$this->prefixes[ $prefix ] = array();
		}

		// Retain the base directory for the namespace prefix.
		if ( $prepend ) {
			array_unshift( $this->prefixes[ $prefix ], $base_dir );
		} else {
			array_push( $this->prefixes[ $prefix ], $base_dir );
		}
	}

	/**
	 * Loads the class file for a given class name.
	 *
	 * @param string $class The fully-qualified class name.
	 * @return mixed The mapped file name on success, or boolean false on
	 * failure.
	 */
	public function load_class( $class ) {
		// The current namespace prefix.
		$prefix = $class;

		// Work backwards through the namespace names of the fully-qualified
		// class name to find a mapped file name.
		while ( false !== $pos = strrpos( $prefix, '\\' ) ) {
			// retain the trailing namespace separator in the prefix.
			$prefix = substr( $class, 0, $pos + 1 );

			// the rest is the relative class name.
			$relative_class = substr( $class, $pos + 1 );

			// try to load a mapped file for the prefix and relative class.
			$mapped_file = $this->load_mapped_file( $prefix, $relative_class );
			if ( $mapped_file ) {
				return $mapped_file;
			}

			// remove the trailing namespace separator for the next iteration
			// of strrpos().
			$prefix = rtrim( $prefix, '\\' );
		}

		// Never found a mapped file.
		return false;
	}

	/**
	 * Load the mapped file for a namespace prefix and relative class.
	 *
	 * @param string $prefix The namespace prefix.
	 * @param string $relative_class The relative class name.
	 * @return mixed Boolean false if no mapped file can be loaded, or the
	 * name of the mapped file that was loaded.
	 */
	protected function load_mapped_file( $prefix, $relative_class ) {
		// are there any base directories for this namespace prefix?
		if ( isset( $this->prefixes[ $prefix ] ) === false ) {
			return false;
		}

		// Look through base directories for this namespace prefix.
		foreach ( $this->prefixes[ $prefix ] as $base_dir ) {

			// replace the namespace prefix with the base directory,
			// replace namespace separators with directory separators
			// in the relative class name, append with .php.
			$file = $base_dir . $this->build_filename( $relative_class );
			// if the mapped file exists, require it.
			if ( $this->require_file( $file ) ) {
				// yes, we're done.
				return $file;
			}
		}

		// never found it.
		return false;
	}

	/**
	 * If a file exists, require it from the file system.
	 *
	 * @param string $file The file to require.
	 * @return bool True if the file exists, false if not.
	 */
	protected function require_file( $file ) {
		if ( file_exists( $file ) ) {
			require $file;
			return true;
		}
		return false;
	}

	/**
	 * Cleans the class name to conform with the WordPress standards.
	 *
	 * @param string $relative_class The class name.
	 * @return string The built class name.
	 */
	private function build_filename( $relative_class ) {
		$filename = str_replace( '_', '-', $relative_class );
		$filename = str_replace( '\\', '/', $filename );
		$filename = 'class-' . strtolower( $filename ) . '.php';
		error_log( "Loading file name: $filename" );
		return $filename;
	}
}
