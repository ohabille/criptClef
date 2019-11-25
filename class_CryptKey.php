<?php

/**
 * CryptKey
 * @since 6 August 2018
 * @author Ohabille ohabille@gmail.com
 * @copyright GPL-3.0-or-later
 * @version 1
 */

class CryptKey {

	/**
	 * @var string
	 */
	private $_key = '';
	/**
	 * @var int
	 */
	private $_step;
	/**
	 * @var string
	 */
	private $_tabLogin;
	/**
	 * @var string
	 */
	private $_tabPassword;
	/**
	 * @var string
	 */
	private $_nbrLogin;
	/**
	 * @var string
	 */
	private $_nbrPassword;
	/**
	 * @var string
	 */
	private $_large;
	/**
	 * @var string
	 */
	private $_small;

	public function __construct(string $login, string $stepsword)
	{
		$this->setParams($login, $stepsword);

		// Initialisation d'un chiffre de remplacement
		// (Racine carré du nombre du petit arrondis à l'entier inferieur)
		$salt = (int) sqrt($this->{'_nbr'.$this->_small});

		$n = 0;

		// boucle de lecture des tableaux de charactères
		for($i = 0; $i < $this->{'_nbr'.$this->_large}; $i++) {
			// Initialisation du charactère du grand mot
			$this->_key .= $this->{'_tab'.$this->_large}[$i];

			// Si le pointeur de la boucle est égal à la valeur
			// de la fréquence
			if($i === $this->_step) {
				// Initialisation du pointeur pour le petit tableau
				// Si la clef par pré-défini (n) pointe sur une entrée
				// existante du tableau
				// x = n. Sinon x = valeur de remplacement
				$x = (isset($this->{'_tab'.$this->_small}[$n]) ? $n: $salt);

				// Incrémentation de la fréquence de la valeur
				// de la scission
				$this->_step += (int) $this->getNbrSplit();
				// Incrémentation de la valeur de remplacement de 1
				$n++;
			}
			// Si le pointeur est différent de la valeur de la fréquence
			else {
				// Initialisation du pointeur à la valeur de remplacement
				$x = $salt;
			}

			// Incrémentation du résultat est de
			$this->_key .= $this->{'_tab'.$this->_small}[$x];
		}
	}

	/**
	 * Initialisation des paramètres
	 * @param string $login
	 * @param string $password
	 */
	private function setParams(string $login, string $password)
	{
		foreach (self::getParamsKeys() as $val) {
			${$val} = urlencode(!empty(${$val}) ? ${$val}: $val);

			// Initialisation du nombre de charactères de la variable
			$this->{'_nbr'.ucfirst($val)} = strlen(${$val});
			// Initialisation du tableau des charactères de la variable
			$this->{'_tab'.ucfirst($val)} = str_split(${$val});
		}

		// Attribution de l'ordre de grandeur
		$tmp = ($this->getDiff() < 0) ? true: false;

		foreach (self::getSizeParams() as $k=>$val)
			$this->{'_'.$k} = ucfirst($tmp ? $val[0]: $val[1]);

		$this->setStep();
	}

	/**
	 * Initialise la frequence d'intercalement
	 */
	private function setStep()
	{
		// Si la différence est inférieur à 0
		if($this->getDiff() < 0) {
			// Initialisation du pas à 0
			$step = 0;
		}
		else {
			// Si la scission est supérieur à 1
			if ($this->getNbrSplit() > 1) {
				// Initialisation de la fréquence à l'entier inférieur
				// de la scission
				$step = floor($this->getNbrSplit());

				// Initialisation du quotient de la difference
				// par la frequence d'intercalage
				$tmp = $this->getDiff() / $step;

				// Si le quotient est inférieur ou égal au pas
				if($tmp <= $step)
					// Soustractionde la valeur du quotient à la valeur
					// de la fréquence
					$step -= $tmp;
			}
			// Si la scission est de 1 ou moins
			else {
				// Initialisation de la fréquence à la valeur de la scission
				$step = $this->getNbrSplit();
			}
		}

		$this->_step = (int) $step;
	}

	/**
	 * Retourne la différrenc de nombre de charactères
	 * entre le password et le login
	 * @return int
	 */
	private function getDiff()
	{
		return abs($this->_nbrPassword - $this->_nbrLogin);
	}

	/**
	 * Retourne la valeur de scission
	 * @return int
	 */
	private function getNbrSplit()
	{
		return $this->{'_nbr'.$this->_large} / $this->{'_nbr'.$this->_small};
	}

	/**
	 * Retourne le tableau des nom de taille
	 * @return array
	 */
	private static function getSizeParams()
	{
		return array(
			'large'=>self::getParamsKeys(),
			'small'=>array_reverse(self::getParamsKeys())
		);
	}

	/**
	 * Retourne le nom des paramètres
	 * @return [type] [description]
	 */
	public static function getParamsKeys()
	{
		return array('login', 'password');
	}

	/**
	 * Retourne le résultat du mélange du ogin et du password
	 * @return string
	 */
	public function getKey()
	{
		return $this->_key;
	}
}
