<?php

namespace Composer\Installer;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;
use React\Promise\PromiseInterface;

class Installer extends LibraryInstaller
{

    private string $finalName;

    private PackageInterface $package;

    public function __construct(
        IOInterface $io,
        Composer $composer,
        $type = 'library',
        Filesystem $filesystem = null,
        BinaryInstaller $binaryInstaller = null
    ) {
        parent::__construct(
            $io,
            $composer,
            $type,
            $filesystem,
            $binaryInstaller
        );
    }


    /**
     * Retourne le chemin d'installation du package
     *
     * @param PackageInterface $package
     * @return void
     */
    public function getInstallPath(PackageInterface $package)
    {
        $this->package = $package;
        
        if (!$this->isCakePhpPlugin()) {
            return parent::getInstallPath($package);
        }
       
        $this->getFinalName();
        
        return 'Plugin/' . $this->finalName;

    }

    /**
     * Récupère le nom final du package.
     * Le champ installer-name dans l'option extra du package est prioritaire. 
     * Puis le champ pretty name du package. Et enfin si aucun renseigné 
     * on utilise le champ name du package.
     *
     * @return void&
     */
    private function getFinalName()
    {

        if (array_key_exists('installer-name', $this->package->getExtra())) {
            $this->finalName = $this->package->getExtra()['installer-name'];
        } else {
            $this->finalName = !empty($this->package->getPrettyName()) ? $this->package->getPrettyName() :  $this->package->getName();
        }

        $this->finalName = $this->sanitizeString($this->finalName);

    }

    /**
     * Transforme une chaine de caractère en la faisant passé par différent process de transformation
     * -> Explode les / et ne récupère que le dernier élément
     * -> Supprimme tous les caractères non linéaires
     * -> Supprime tous les espaces
     * -> Met en majuscule la première lettre de tous les mots
     * 
     *
     * @param string $string Chaine d'entrée
     * @return string Chaine de caractère transformée
     */
    public function sanitizeString(string $string): string 
    {
        
        $explodeString = explode('/', $string);
        $string = end($explodeString);
        
        $string = $this->deleteAllNonLinearCharacters($string);

        $string = $this->removeSpace($string);

        return ucwords($string);

    }

    /**
     * Supprime les caractères non littéraux de la chaine de caractère
     *
     * @param string $string
     * @return string
     */
    private function deleteAllNonLinearCharacters(string $string): string
    {
        return preg_replace('/[^a-zA-Z]/m', '', $string);
    }

    /**
     * Supprime les espaces d'une chaine de caractère
     *
     * @param string $string
     * @return string
     */
    private function removeSpace(string $string): string
    {
        return str_replace(' ', '', $string);
    }

    /**
     * Test si le type du package est bien un plugin CakePhp
     *
     * @return boolean
     */    
    private function isCakePhpPlugin(): bool
    {   
        return $this->package->getType() == 'cakephp-plugin';
    }

}