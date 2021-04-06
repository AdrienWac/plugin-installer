# plugin-installer
Installateur composer personnalisé pour installer des plugins sur des projets CakePHP2 via composer .

# Utilisation

Pour utiliser cet installateur composeur pour votre plugin vous pouvez éditer votre fichier de configuration composer.json
de la manière suivante : 

```javascript
"require": {
    "adrien-wac/plugin-installer": "*",
}
```

Ou lancer la commande suivante : 

```php
composer require adrien-wac/plugin-installer
```

# Configuration

La première étape de configuration est de définir le type de votre plugin dans le fichier composer.json : 

```javascript
"type": "cakephp-plugin"
```

Si vous souhaitez générer vous même le nom du plugin, vous pouvez renseigner le champ installer-name dans la rubrique extra : 

```javascript
"extra": {
    "installer-name": "MonBeauPlugin"
}
```

Si aucune valeur n'est renseignée dans le champ installer-name, le nom sera formatté à partir du nom du dépôt. Par exemple:

```javascript
"name": "mon-username-github/mon-plugin",
```

sera installé sous 

```
Plugin/Monplugin
```

