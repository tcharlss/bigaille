<?php
/**
 * fonctions utiles au plugin Bigaille
 *
 * @plugin     Bigaille
 * @copyright  2015
 * @author     C.R
 * @licence    GNU/GPL
 * @package    SPIP\Bigaille\Fonctions
 */


/*
 * Surcharge de la balise prix HT
 *
 * Ajout du formatage au moyen de formater_prix_objet()
 *
 * @param object $p
 * @return object $p
 */
function balise_PRIX_HT($p) {
	if (!$_type = interprete_argument_balise(1,$p)){
		$_type = sql_quote($p->type_requete);
		$_id = champ_sql($p->boucles[$p->id_boucle]->primary,$p);
	}
	else
		$_id = interprete_argument_balise(2,$p);
	$connect = $p->boucles[$p->id_boucle]->sql_serveur;
	$prix = "prix_ht_objet(intval(".$_id."),".$_type.','.sql_quote($connect).")";
	// pour les prix d'objets, sans étoile on formate avec la fonction maison
	if (
		$_type == "'prix_objets'"
		and !$p->etoile
	) {
		$prix = "formater_prix_objet(". $prix .",". $_id .")";
	}
	$p->code = $prix;
	$p->interdire_scripts = false;
	return $p;
}


/*
 * Surcharge de la balise prix
 *
 * Ajout du formatage au moyen de formater_prix_objet()
 *
 * @param object $p
 * @return object $p
 */
function balise_PRIX($p) {
	if (!$_type = interprete_argument_balise(1,$p)){
		$_type = _q($p->type_requete);
		$_id = champ_sql($p->boucles[$p->id_boucle]->primary,$p);
	}
	else
		$_id = interprete_argument_balise(2,$p);
	$connect = $p->boucles[$p->id_boucle]->sql_serveur;
	$prix = "prix_objet(intval(".$_id."),".$_type.','.sql_quote($connect).")";
	// pour les prix d'objets, sans étoile on formate avec la fonction maison
	if (
		$_type == "'prix_objets'"
		and !$p->etoile
	) {
		$prix = "formater_prix_objet(". $prix .",". $_id .")";
	}
	$p->code = $prix;
	$p->interdire_scripts = false;
	return $p;
}


/**
 * Formater un prix d'objet avec la devise qui lui correspond
 *
 * Le formatage normal de l'API est désactivé pour les "prix d'objets",
 * on utilise cette fonction à la place.
 *
 * @param float $prix Valeur du prix à formater
 * @return string Retourne une chaine contenant le prix formaté avec une devise (par défaut l'euro)
 */
function formater_prix_objet($prix, $id_prix_objet=0){

	if (!intval($id_prix_objet)) return $prix;

	if (!$devise = sql_getfetsel('devise', 'spip_prix_objets', 'id_prix_objet='.intval($id_prix_objet))) {
		include_spip('inc/config');
		$devise = lire_config('bigaille/devise_defaut');
	}

	if ($devise) {
		$prix = number_format($prix, 2) . '&nbsp;' . $devise;
	}

	return $prix;
}


/*
 *  Déport de la fonction pour pouvoir au besoin la surcharger avec
 *  function filtres_prix_formater
 *
 */
function filtres_prix_objet_formater_dist($prix){

	// Pouvoir débrayer la devise de référence
	if (! defined('PRIX_DEVISE')) {
	  define('PRIX_DEVISE','fr_FR.utf8');
	}

	// Pouvoir débrayer l'écriture de la devise par défaut
	if (! defined('DEVISE_DEFAUT')) {
	  define('DEVISE_DEFAUT','&nbsp;&euro;');
	}

	setlocale(LC_MONETARY, PRIX_DEVISE);

	if(function_exists(money_format)) {
		$prix = floatval($prix);
		$prix = money_format('%i', $prix);
		// Afficher la devise € si celle ci n'est pas remontée par la fonction money
		if ((strlen(money_format('%#1.0n', 0)) < 2) || ((money_format('%#1.0n', 0) == 0) AND (strlen(money_format('%#1.0n', 0)) == 3)))
		  $prix .= DEVISE_DEFAUT;
	} else {
		 $prix .= DEVISE_DEFAUT;
	}

	return $prix;
}


/**
 * Renvoie la taxe d'un objet donné
 *
 * @note : dans une boucle d'un objet, utiliser #TAXE directement
 * @note : on utilise l'ordre des paramètres comme la fonction prix_objet du plugin prix
 *
 * @param int $id_objet
 * @param string $objet
 * @return float|bool
 */
if (!function_exists('taxe_objet')) {
	function taxe_objet($id_objet, $objet){

		$taxe = sql_getfetsel(
			'taxe',
			'spip_prix_objets',
			array(
				'objet = ' . sql_quote($objet),
				'id_objet = ' . intval($id_objet), 
			)
		);

		return floatval($taxe);
	}
}