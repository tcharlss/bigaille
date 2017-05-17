<?php
/**
 * Action : supprimer un prix d'objet
 *
 * @plugin     Bigaille
 * @copyright  2015
 * @author     C.R
 * @licence    GNU/GPL
 * @package    SPIP\Bigaille\Action
 */

// Sécurité
if (!defined("_ECRIRE_INC_VERSION")) return;

/**
 * Supprime un prix d'objet
 *
 * @example
 *     ```
 *     #URL_ACTION_AUTEUR{supprimer_prix_objet,#ID_PRIX_OBJET,#SELF}
 *     ```
 *
 * @param $arg string
 *     Identifiant du prix
 * @return void
 */
function action_supprimer_prix_objet_dist($arg=null) {

	// Si $arg n'est pas donné directement, le récupérer via _POST ou _GET
	if (is_null($arg)) {
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}

	if ($id_prix_objet = intval($arg)) {
		include_spip('inc/abstract_sql');
		sql_delete('spip_prix_objets', 'id_prix_objet='.$id_prix_objet);
	}
}

?>
