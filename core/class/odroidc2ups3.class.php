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

    
    public static $_url_template = 'http://www.asiaflash.com/horoscope/rss_horojour_%s.xml';

    public static $_widgetPossibility = array('custom' => true);


    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /**
     * Recupere la liste des signes disponibles
     */
    public static function getSignes() {
        return self::$_signes;
    }


    
    /**
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
    public static function cron() {
        $today = date('H');
        $frequence = config::byKey('frequence', 'odroidc2ups3');
       
		
		
        if ($frequence == '1min') {
            
			log::add('odroidc2ups3', 'info', '<----------------- MISE A JOUR DE L\'ETAT DE L\'UPS3 ----------------->');
			log::add('odroidc2ups3', 'debug', '.');
			log::add('odroidc2ups3', 'debug', '====> Position : Début de la boucle pour chaque équipement');
            foreach (eqLogic::byType('odroidc2ups3', true) as $mi_odroidc2ups3) {
                //log::add('odroidc2ups3', 'debug', 'Après chaque élément');

                //Procédure de calcul de l odroidc2ups3
				
                $mi_odroidc2ups3->updateEtat();
				log::add('odroidc2ups3', 'debug', 'MISE A JOUR DU WIDGET');
				
                $mi_odroidc2ups3->refreshWidget();
				
            }
			log::add('odroidc2ups3', 'debug', '====> Position : Fin de boucle pour chaque équipement');
			log::add('odroidc2ups3', 'debug', '.');
        }
    }

     // Fonction exécutée automatiquement toutes les heures par Jeedom
    public static function cronHourly() {
      
    }

    /*     * *********************Méthodes d'instance************************* */

    /**
     * Recuperer l'odroidc2ups3 du jour et met à jour les commandes
     */
    public function updateodroidc2ups3() {
        		log::add('odroidc2ups3', 'debug', '.');
				log::add('odroidc2ups3', 'debug', 'Modification de l\'équipement : '.$this->getName().'.');
				
		
        
		log::add('odroidc2ups3', 'debug', '.');
    }

    /**
     * Met a jour la commande contenant le signe configure
     */
    public function updateEtat() {
        
		
		//Vérification de la présence des ports GPIO (odroid C1 et C2)
		$resultat='';
		
		if (file_exists('/sys/class/gpio/gpio88')) // C1 PIN 11 AC_OK
		{
			$resultat='GPIO';	
		}
		
		
		if (file_exists('/sys/class/gpio/gpio247')) // C2 PIN 11 AC_OK
		{
			$resultat='GPIO';
		}
		
		if (file_exists('/sys/class/gpio/gpio116')) // C1 PIN 13 BAT_OK
		{
			$resultat='GPIO';
		}
		if (file_exists('/sys/class/gpio/gpio239')) // C2 PIN 13 BAT_OK
		{
			$resultat='GPIO';
		}
		if (file_exists('/sys/class/gpio/gpio115')) // C1 PIN 15 POWER_LATCH
		{
			$resultat='GPIO';
		}
		if (file_exists('/sys/class/gpio/gpio225')) // C2 PIN 26 POWER_LATCH
		{
			$resultat='GPIO';
		}
		
		 
		 
		 if ($resultat=='')
		 {
			// Lancement de la commande shell pour récupérer l'état de la batterie.
			$resultat='BATTERIE NON PRESENTE';
		 }
		 else
		 {
			// Lancement de la commande shell pour récupérer l'état de la batterie.
			$resultat=shell_exec ('sh /var/www/html/plugins/odroidc2ups3/3rparty/ups3.sh');
		 }
			 
			  

		
		log::add('odroidc2ups3', 'debug', 'resultat :'.$resultat);
        log::add('odroidc2ups3', 'debug', 'ETAT DE LA BATTERIE : '.$resultat);
		
        $odroidc2ups3Cmd = $this->getCmd(null, 'Etat');
        if (!is_object($odroidc2ups3Cmd)) {
            $odroidc2ups3Cmd = new odroidc2ups3Cmd();
            $odroidc2ups3Cmd->setName(__('Etat', __FILE__));
            $odroidc2ups3Cmd->setEqLogic_id($this->getId());
            $odroidc2ups3Cmd->setLogicalId('Etat');
            $odroidc2ups3Cmd->setIsVisible(false);
            $odroidc2ups3Cmd->setEqType('odroidc2ups3');
            $odroidc2ups3Cmd->setType('info');
            $odroidc2ups3Cmd->setSubType('string');
            $odroidc2ups3Cmd->setIsHistorized(1);
            $odroidc2ups3Cmd->save();
        }
        $odroidc2ups3Cmd->event($resultat);
    }

    public function preInsert() {

    }

    public function postInsert() {

    }

    public function preSave() {

    }

    public function postSave() {
        $this->updateEtat();
    }

    public function preUpdate() {
      
	  
	   $freq = config::byKey('frequence', 'odroidc2ups3');
        if ($freq == '') {
            log::add('odroidc2ups3', 'debug', 'preUpdate: La fréquence n\'a pas été enregistrée dans le menu configuration du plugin.');
            throw new Exception(__("La fréquence n'a pas été enregistrée dans le menu configuration du plugin.", __FILE__));
        }
		
    }

    public function postUpdate()
    {
        $this->updateodroidc2ups3();
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
