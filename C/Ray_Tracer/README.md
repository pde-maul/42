# 42  /  RT ou Raytracer
>>>> ##### Projet en C (fin de branche)

##### Grade ``(91/100)``
--------  -----------------------
```
Le but est de générer des images de synthèse selon la méthode du Ray-Tracing.
Ces images de synthèse représentent une scène, vue d’une position et d’un angle spécifiques,
définie par des objets géométriques simples, entiers ou partiels, 
et disposant d’un système d’éclairage.
```

Fonctionnalités:
- [X] Ray-Tracing: créaton d'images de synthèse
- [X] Presences d'objets géométriques simples et complexes
- [X] Gestion du réaffichage sans re-calcul
- [X] Position et direction quelconque du point de vision, et des objets simples
- [X] Gestion de la lumière : multi-spot, luminosité, brillance, ombres
- [X] Objets limités : parallélogrammes, disques, tuyaux...
- [X] Perturbation de la couleur, notemment par des textures
- [X] Fichier externe de description de la scène
- [X] Réflexion
- [X] Transparence
- [X] Modification de l’ombre selon la transparence des objets
- [X] Objets composés : cube
- [X] Textures
- [X] Objets négatifs
- [X] Mouvements de caméra
- [X] Anti aliasing
- [X] Sauvegarde de la scène (fichier JSON)
- [X] Sauvegarde d'une image de la scène
- [X] Interface évoluée
- [X] Multi-Threading
- [X] Perturbation de la limite / transparence / réflexion, selon une texture
- [X] ...


 -----------------------

##### Vue du programme:
```
Interface
```
![interface](https://github.com/pde-maul/42/blob/master/Wolf3D/wol3d.png)

```
Scène avec réflexion
```
![Scène avec réflexion](https://github.com/pde-maul/42/blob/master/Wolf3D/wol3d.png)
