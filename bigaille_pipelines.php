<?php
/**
 * Utilisations de pipelines par Bigaille
 *
 * @plugin     Bigaille
 * @copyright  2015
 * @author     C.R
 * @licence    GNU/GPL
 * @package    SPIP\Bigaille\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Ajout de contenu sur certaines pages.
 *
 * - Prix sur les objets configurés
 *
 * @pipeline affiche_milieu
 *
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function bigaille_affiche_milieu($flux) {

	include_spip('inc/config');
	$texte  = "";
	$e      = trouver_objet_exec($flux['args']['exec']);
	$objets = lire_config('bigaille/prix_objets', array());

	// Prix sur les objets activés
	if (
		$e !== false // page d'un objet éditorial
		AND $e['edition'] === false // pas en mode édition
		AND $table_objet_sql = $e['table_objet_sql']
		AND in_array($table_objet_sql,$objets)
	) {
		$texte .= recuperer_fond('prive/objets/editer/prix', array(
			'objet'    => $e['type'],
			'id_objet' => $flux['args'][$e['id_table_objet']],
			),
			array('ajax'=>'prix')
		);
	}

	if ($texte) {
		if ($p=strpos($flux['data'],"<!--affiche_milieu-->"))
			$flux['data'] = substr_replace($flux['data'],$texte,$p,0);
		else
			$flux['data'] .= $texte;
	}

	return $flux;
}


/**
 * Optimiser la base de données en supprimant les liens orphelins
 * de l'objet vers quelqu'un et de quelqu'un vers l'objet.
 *
 * @pipeline optimiser_base_disparus
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function bigaille_optimiser_base_disparus($flux){
	include_spip('action/editer_liens');
	$flux['data'] += objet_optimiser_liens(array('prix'=>'*'),'*');
	return $flux;
}


/**
 * Prix TTC des prix d'objets.
 *
 * @pipeline prix
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function bigaille_prix($flux){

	$prix_ht  = $flux['args']['prix_ht'];
	$prix_ttc = $flux['args']['prix'];

	if (
		$flux['args']['type_objet'] == 'prix_objet'
		and $id_prix_objet = $flux['args']['id_objet']
		and $ligne = sql_fetsel('prix_ht,prix,taxe', 'spip_prix_objets', 'id_prix_objet ='.$id_prix_objet)
	){
		$flux['args']['prix'] = $flux['data'] = $ligne['prix'];
	}

	return $flux;
}


/**
 * Intervenir après l'édition d'un objet
 *
 * - Après le remplissage d'une commande depuis un panier, 
 * mettre à jour les taxes des détails pour éviter les erreurs d'arrondi.
 *
 * @pipeline post_edition
 * @param array $flux
 *     Données du pipeline
 * @return array
 *     Données du pipeline
 **/
function bigaille_post_edition($flux) {

	if (isset($flux['args']['table'])
		and $flux['args']['table'] == 'spip_commandes'
		and $flux['args']['action'] == 'remplir_commande'
		and $id_commande = intval($flux['args']['id_objet'])
		and $details = sql_allfetsel(
			'id_commandes_detail, objet, id_objet, taxe',
			'spip_commandes_details',
			array(
				'id_commande = '.intval($id_commande),
				'taxe > 0'
			)
		)
	) {
		foreach($details as $detail) {
			if ($vraie_taxe = sql_getfetsel(
				'taxe',
				'spip_prix_objets',
				array(
					'objet = ' . sql_quote($detail['objet']),
					'id_objet = ' . intval($detail['id_objet']))
				)
				and floatval($vraie_taxe) !== floatval($detail['taxe'])
			) {
				$set = array(
					'taxe' => floatval($vraie_taxe),
				);
				sql_updateq(
					'spip_commandes_details',
					$set,
					'id_commandes_detail = ' . intval($detail['id_commandes_detail'])
				);
			}
		}
	}

	return $flux;
}