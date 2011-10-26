<?php

/**
 * Base class for running unit tests using in-memory SQLite database
 * 
 * Running tests this way you can get rid of creating database files on a hard
 * drive (your tests will run faster) and allows you don't make changes in a 
 * development database.
 *
 * PHP 5.3+ 
 * 
 * @link https://github.com/sergey-s/yii-abstract-sqlite-test-cases
 * @author Sergey Sytsevich <laa513@gmail.com>
 */
abstract class AbstractSqliteTestCase extends CDbTestCase {
    
    /**
     * Called before the first test of the test case class is run
     */
    public static function setUpBeforeClass() {
        if (!self::_isSqliteLoaded()) {
            markTestSkipped('PDO and SQLite extensions are required.');
        }
        self::_configureApplication();
        static::_setUpDatabase();
    }

    /**
     * Called after the last test of the test case class is run
     * 
     * Closes DB connection
     */
    public static function tearDownAfterClass() {
        if (Yii::app()->getDb()) {
            Yii::app()->getDb()->active = false;
        }
    }
    
    /**
     * Check if SQLite is loaded
     * @return bool 
     */
    protected static function _isSqliteLoaded() {
        return (extension_loaded('pdo') && extension_loaded('pdo_sqlite'));
    }
    
    /**
     * Configure application
     * 
     * Sets up in-memory SQLite database connection and sets base path for 
     * fixtures to "path_to_your_unit_test_class/fixtures" folder
     */
    protected static function _configureApplication() {
        $config = array(
            'basePath' => dirname(__FILE__),
            'components' => array(
                'db' => array(
                    'class' => 'system.db.CDbConnection',
                    'connectionString' => 'sqlite::memory:',
                ),
                
                'fixture' => array(
                    'class' => 'system.test.CDbFixtureManager',
                    'basePath' => dirname(__FILE__) . '/fixtures'
                ),
            )
        );
        Yii::app()->configure($config);
    }

    /**
     * Creates needed tables for running tests
     */
    protected abstract static function _setUpDatabase();
}
