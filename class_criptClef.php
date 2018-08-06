<?php

	/* Mixage deux mots données (login et password):
	Les lettres du plus petit mots seront intercalées entre les lettres
	du plus grand mot.
	Le plus petit mot sera complété pour atteindre le même nombre de lettres
	que le grand mot
	ex : login devient glogingg (voir ligne 117) */
	class CriptClef {
		//

/* ##################### */
/* # PRIVATE VARIABLES # */
/* ##################### */

		// Déclaration des variables
		private $_clef = null;

/* ################ */
/* # CONSTRUCTEUR # */
/* ################ */

		// Constructeur
		public function __construct($login, $password) {
			// Appel de la fonction de mixage
			$this->setMixed($login, $password);
		}

/* ################## */
/* # PRIVATE METHOD # */
/* ################## */

	/* ------- */
	/* SETTERS */
	/* ------- */

		// fonction de melange des deux mots
		private function setMixed($login, $password) {
			// Inialisation d'un tableau pour simplifier le traitement de base
			$tmp = array('login'=>$login, 'password'=>$password);

			// Lecture du tableau
			foreach($tmp as $k=>$val) {
				// Initialisation de la variable
				${$k} = (!empty(${$k})) ? $val: $k;

				// Encodage url de la valeur
				${$k} = urlencode(${$k});

				// Initialisation d'un nom de variable temporaire
				$Val = ucfirst($k);

				// Initialisation du nombre de charactères de la variable
				${'nbr'.$Val} = strlen(${$k});

				// Initialisation du tableau des charactères de la variable
				${'tab'.$Val} = str_split(${$k});

				// Suppression de la variable temporaire
				unset($Val);
			}

			// Initialisation des valeur par défaut
			$resultat = null; // Variable du resultat final du mélange
			// $Presultat = null; // Variable du mot à intercaler pour l'exemple
			$n = 0; // Variable du pointeur pour le tableau du petit mot

			/* Initialisation de la différencede nombre de charactères entre les deux mots
			password - login */
			$diff = $nbrPassword - $nbrLogin;
			$diff = abs($diff); // Valeur absolue de la différence

			// Attribution de l'ordre de grandeur des deux mots
			$tmp = ($diff < 0) ? true: false;
			$grand = ($tmp) ? "Login": "Password";
			$petit = ($tmp) ? "Password": "Login";

			// Initialisation des noms de variables
			$grandeTab = 'tab'.$grand;
			$petiteTab = 'tab'.$petit;
			$grandNbr = 'nbr'.$grand;
			$petitNbr = 'nbr'.$petit;

			// Initialisation du nombre de scission du grand mot
			$scission = ${$grandNbr} / ${$petitNbr};

			/* Initialisation d'un chiffre de remplacement
			(Racine carré du nombre du petit arrondis à l'entier inferieur) */
			$sel = (int) sqrt(${$petitNbr});

			/* Initialisation de la fréquence d'intercalage
			Si la différence est inférieur à 1 */
			if($diff < 1) {
				// Initialisation du pas à 0
				$pas = 0;
			}
			else {
				// Si la scission du grand est supérieur à 1 (charactère)
				if($scission > 1) {
					// Initialisation de la fréquence à l'entier inférieur de la scission
					$pas = floor($scission);

					// Initialisation du quotient de la difference par la frequence d'intercalage
					$tmp = $diff / $pas;

					// Si le quotient est inférieur ou égal au pas
					if($tmp <= $pas) {
						// Soustractionde la valeur du quotient à la valeur de la fréquence
						$pas -= $tmp;
					}
				}
				// Si la scission est de 1 ou moins
				else {
					// Initialisation de la fréquence à la valeur de la scission
					$pas = $scission;
				}
			}

			// Conversion int des valeurs de frequence et de scission
			$pas = (int) $pas;
			$scission = (int) $scission;

			// boucle de lecture des tableaux de charactères
			for($i = 0; $i < ${$grandNbr}; $i++) {
				// Initialisation du charactère du grand mot
				$resultat .= ${$grandeTab}[$i];


				// Si le pointeur de la boucle est égal à la valeur de la fréquence
				if($i === $pas) {
					/* Initialisation du pointeur pour le petit tableau
					Si la clef par pré-défini (n) pointe sur une entrée existante du tableau
					x = n. Sinon x = valeur de remplacement (sel) */
					$x = (isset(${$petiteTab}[$n])) ? $n: $sel;

					// Incrémentation de la fréquence de la valeur de la scission
					$pas += $scission;
					// Incrémentation de la valeur de remplacement de 1
					$n++;
				}
				// Si le pointeur est différent de la valeur de la fréquence
				else {
					// Initialisation du pointeur à la valeur de remplacement
					$x = $sel;
				}

				// Incrémentation du résultat est de
				$resultat .= ${$petiteTab}[$x];
				// (pour l'exemple) $Presultat .= ${$petiteTab}[$x];

			}

			// Initialisation du résultat final
			$this->_clef = $resultat;
		}

/* ################## */
/* # PUBLIC METHODS # */
/* ################## */

	/* ------ */
	/* GETTER */
	/* ------ */

		// sha1 de la clef
		public function getSha1() {
			return sha1($this->_clef);
		}

		// md5 de la clef
		public function getMd5() {
			return md5($this->_clef);
		}

		/* Cript de la clef
		résultat aléatoire si la propriété salt est null */
		public function getCrypt($salt = null) {
			return crypt($this->_clef, $salt);
		}
	}
?>