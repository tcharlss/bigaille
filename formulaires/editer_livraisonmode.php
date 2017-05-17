<?php
/**
 * Gestion du formulaire de d'édition de livraisonmode
 *
 * Surcharge :
 * - traitement de la saisie pays
 * - traitelent de la saisie des pays exclus
 *
 * @plugin     Livraison
 * @copyright  2015
 * @author     Cédric
 * @licence    GNU/GPL
 * @package    SPIP\Livraison\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/actions');
include_spip('inc/editer');

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $id_livraisonmode
 *     Identifiant du livraisonmode. 'new' pour un nouveau livraisonmode.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un livraisonmode source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du livraisonmode, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string
 *     Hash du formulaire
 */
function formulaires_editer_livraisonmode_identifier_dist($id_livraisonmode='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	return serialize(array(intval($id_livraisonmode)));
}

/**
 * Chargement du formulaire d'édition de livraisonmode
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_livraisonmode
 *     Identifiant du livraisonmode. 'new' pour un nouveau livraisonmode.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un livraisonmode source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du livraisonmode, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_livraisonmode_charger_dist($id_livraisonmode='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){

	// valeurs génériques
	$valeurs = formulaires_editer_objet_charger('livraisonmode',$id_livraisonmode,'',$lier_trad,$retour,$config_fonc,$row,$hidden);

	// préparation des valeurs pour la saisie pays
	if ($pays = $valeurs['zone_pays']) {
		$valeurs['zone_pays'] = explode(',', $pays);
	}

	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de livraisonmode
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $id_livraisonmode
 *     Identifiant du livraisonmode. 'new' pour un nouveau livraisonmode.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un livraisonmode source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du livraisonmode, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_livraisonmode_verifier_dist($id_livraisonmode='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){

	return formulaires_editer_objet_verifier('livraisonmode',$id_livraisonmode, array('titre'));

}

/**
 * Traitement du formulaire d'édition de livraisonmode
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $id_livraisonmode
 *     Identifiant du livraisonmode. 'new' pour un nouveau livraisonmode.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un livraisonmode source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du livraisonmode, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_livraisonmode_traiter_dist($id_livraisonmode='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){

	// Traitement de la valeur de la saisie zone_pays
	if ($zone_pays = _request('zone_pays')) {
		set_request('zone_pays', implode(",", $zone_pays));
	}

	// Traitements génériques
	$res = formulaires_editer_objet_traiter('livraisonmode',$id_livraisonmode,'',$lier_trad,$retour,$config_fonc,$row,$hidden);

	return $res;
}


?>
