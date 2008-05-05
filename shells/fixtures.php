<?php
/**
 * Fixtures is a CakePHP shell script that imports data from your YAML files
 *
 * Run 'cake fixtures' for more info and help on using this script.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright 2008, Georgi Momchilov
 * @link        http://ovalpixels.com
 * @author      Georgi Momchilov
 * @since       CakePHP(tm) v 1.2
 * @license     http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
*/

uses('file', 'folder');
App::import('vendor','fixtures');


class FixturesShell extends Shell {

    var $sConnection = 'default';
    var $oFixtures;

    /**
    * Initializes some paths and checks for the required classes
    */
    function startup(){
        define('FIXTURES_PATH', APP_PATH .'config' .DS. 'fixtures');

        if(isset($this->params['c'])) $this->sConnection = $this->params['c'];
        if(isset($this->params['connection'])) $this->sConnection = $this->params['connection'];
        
        if( !class_exists( 'Fixtures' ) )
            $this->error( 'File not found', 'Fixtures class is needed for this shell to run. Could not be found - exiting.' );
        
        $this->oFixtures = new Fixtures( $this->sConnection );
        
        $this->_welcome();
    }

    /**
    * Main method: Imports all fixtures from the fixtures path
    */
    function main(){
        if( !class_exists('Spyc') )
            $this->error( 'File not found', 'YAML class is needed for this shell to run. Could not be found - exiting.' );
        
        $oFolder = new Folder(FIXTURES_PATH);
        $aFixtures = $oFolder->find('.+_fixture\.yml');
        $oFixtures = new Fixtures( $this->sConnection );
        $iCount = 0;
        foreach( $aFixtures as $sFixture ){
            $iCount++;
            if( $oFixtures->import( FIXTURES_PATH. DS . $sFixture ) !== true ){
                $this->error( 'Import failed.', 'Sorry, there was an error inserting the data into your database' );
            }
            else{
                $this->out( 'Importing '.$sFixture.'...' );
                $this->out( '' );
            }
        }
        $this->out($iCount. ' fixture(s) successfully imported');
    }
}