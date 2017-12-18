<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class odroidc2ups3 extends eqLogic {

   

    public static $_widgetPossibility = array('custom' => true);


    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

     */
  

    /**
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
    public static function cron() {
        $today = date('H');
        $frequence = config::byKey('frequence', 'odroidc2ups3');
       

        if ($frequence == '1min') {
            
			
			log::add('odroidc2ups3', 'debug', 'Position : Fin de boucle pour chaque équipement');
        }
    }

     // Fonction exécutée automatiquement toutes les heures par Jeedom
    public static function cronHourly() {
     
	}
	

    /*     * *********************Méthodes d'instance************************* */

    
    

    public function preInsert() {

    }

    public function postInsert() {

    }

    public function preSave() {

    }

    public function postSave() {
        $this->updateSigne();
    }

    public function preUpdate() {
       
    }

    public function postUpdate()
    {
        //$this->updateodroidc2ups3();
    }


    public function preRemove() {

    }

    public function postRemove() {

    }

    public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $version = jeedom::versionAlias($_version);
        if ($this->getDisplay('hideOn' . $version) == 1) {
            return '';
        }
        foreach ($this->getCmd('info') as $cmd) {
            $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
            $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
            $replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
            if ($cmd->getIsHistorized() == 1) {
                $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
            }
        }

        log::add('odroidc2ups3','debug', $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'odroidc2ups3', 'odroidc2ups3'))));

        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'odroidc2ups3', 'odroidc2ups3')));
    }

    /*     * **********************Getteur Setteur*************************** */
}

class odroidc2ups3Cmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {

    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
