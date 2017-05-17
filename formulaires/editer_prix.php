<?php
/**
 * Gestion du formulaire de d'édition de prix
 *
 * @plugin     Bigaille
 * @copyright  2015
 * @author     C.R
 * @licence    GNU/GPL
 * @package    SPIP\Bigaille\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/actions');
include_spip('inc/editer');


/**
 * Chargement du formulaire d'édition de prix
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @param int|string $id_prix_objet
 *     Identifiant du prix. 'new' pour un nouveau prix.
 * @param string $objet
 *     Type d'objet à associer
 * @param int | string $id_objet
 *     Identifiant de l'objet à associer
 * @param string $retour
 *     URL de redirection après le traitement
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_prix_charger_dist($id_prix_objet='new', $objet='', $id_objet=0, $retour=''){

	// nouveau prix ?
	$creation = !intval($id_prix_objet);

	// en cas de création, le formulaire n'est pas éditable si :
	// - il n'y a aucune taxe
	// - il n'y a auune devise d'activé dans la conf
	if ($creation) {
		include_spip('inc/config');
		$nb_taxes = sql_countsel('spip_taxes');
		$nb_devises = count(lire_config('bigaille/devises', array()));
		$msg_erreur = '';
		if ($nb_taxes == 0) {
			$msg_erreur[] = _T('prix:champ_taxe_aucune_explication');
		}
		if ($nb_devises == 0) {
			$msg_erreur[] = _T('prix:champ_devise_aucune_explication');
		}
		if ($msg_erreur) {
			$valeurs['message_erreur'] .= implode('<br />',$msg_erreur);
			$valeurs['editable'] = false;
			return $valeurs;
		}
	}

	// valeurs du formulaires

	// 1. valeurs passées en paramètre
	$valeurs['id_prix_objet']  = $id_prix_objet;
	$valeurs['objet']    = $objet;
	$valeurs['id_objet'] = $id_objet;

	// 2. autres valeurs : par défaut (nouveau prix) ou récupérées (édition d'un prix existant)
	// valeurs par défaut
	$defauts = array(
		'prix' =>    '',
		'prix_ht' => '',
		'taxe' =>    null,
		'devise' =>  null
	);
	$champs = array_keys($defauts);
	// récupérer les valeurs si on édite un prix
	if (!$creation) {
		$set = sql_allfetsel($champs, 'spip_prix_objets', 'id_prix_objet='.$id_prix_objet);
		$set = reset($set); //
	}
	// 3. on injecte les valeurs
	foreach($champs as $champ) {
		$valeurs[$champ] = (isset($set) and isset($set[$champ])) ? $set[$champ] : $defauts[$champ];
	}

	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de prix
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @param int|string $id_prix_objet
 *     Identifiant du prix. 'new' pour un nouveau prix.
 * @param string $objet
 *     Type d'objet à associer
 * @param int | string $id_objet
 *     Identifiant de l'objet à associer
 * @param string $retour
 *     URL de redirection après le traitement
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_prix_verifier_dist($id_prix_objet='new', $objet='', $id_objet=0, $retour=''){

	$erreurs = array();

	// on récupère les valeurs
	$champs = array('prix_ht', 'prix', 'taxe', 'devise');
	foreach ($champs as $champ) {
		${$champ} = _request($champ);
	}

	// on vérifie que la taxe soit cohérente
	if ($taxe < 0 or $taxe > 1) {
		$erreurs['taxe'] = _T('prix:champ_taxe_erreur_intervale');
	}

	// on vérifie que le prix ttc soit cohérent avec la taxe et le prix HT
	$prix_theorique = round($prix_ht + ($prix_ht * $taxe), 2);
	if ($prix != $prix_theorique) {
		$erreurs['prix'] = _T('prix:champ_prix_ttc_erreur', array('prix'=>$prix_theorique));
	}

	return $erreurs;
}

/**
 * Traitement du formulaire d'édition de prix
 *
 * Traiter les champs postés
 *
 * @param int|string $id_prix_objet
 *     Identifiant du prix. 'new' pour un nouveau prix.
 * @param string $objet
 *     Type d'objet à associer
 * @param int | string $id_objet
 *     Identifiant de l'objet à associer
 * @param string $retour
 *     URL de redirection après le traitement
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_prix_traiter_dist($id_prix_objet='new', $objet='', $id_objet=0, $retour=''){

	$res = array();

	if (_request('enregistrer')) {

		// nouveau prix ?
		$creation = !intval($id_prix_objet);

		// on récupère les valeurs
		$set = array();
		$champs = array('prix_ht', 'prix', 'taxe', 'devise');
		foreach ($champs as $champ) {
			$set[$champ] = _request($champ);
		}

		// création
		if ($creation) {
			// on ajoute l'objet aux valeurs
			$set['objet'] = $objet;
			$set['id_objet'] = $id_objet;
			// insertion
			$id_nouveau_prix = sql_insertq('spip_prix_objets', $set);
			if ($id_nouveau_prix) {
				$res['id_nouveau_prix'] = $id_nouveau_prix;
			}
		}
		// édition
		else {
			sql_updateq('spip_prix_objets', $set, 'id_prix_objet='.intval($id_prix_objet).' AND objet='.sql_quote($objet).' AND id_objet='.intval($id_objet));
		}

	}

	// redirection
	// on revient dans tous les cas vers le mode visualisation
	// sans prix et id_prix_objet dans l'URL
	$redirect = parametre_url(parametre_url(sinon($retour,self()), 'prix', ''), 'id_prix_objet', '');
	$res['redirect'] = $redirect;

	return $res;
}


?>
