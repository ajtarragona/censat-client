

# Censat Client for Laravel
Client d'accés a la API Rest de l'aplicació CENSAT de l'Ajuntament de Tarragona

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Instalació](#instalaci%C3%B3)
- [Configuració](#configuraci%C3%B3)
- [Via API](#via-api)
  - [Funcions](#funcions)
    - [Definició de censos i entitats](#definici%C3%B3-de-censos-i-entitats)
      - [censuses()](#censuses)
      - [census($short_name)](#censusshort_name)
      - [censusEntities($short_name)](#censusentitiesshort_name)
      - [entities()](#entities)
      - [entity($short_name)](#entityshort_name)
      - [entityFields($short_name)](#entityfieldsshort_name)
      - [entityField($entity_name, $field_name)](#entityfieldentity_name-field_name)
      - [entityGridFields($short_name, $grid_name)](#entitygridfieldsshort_name-grid_name)
    - [Accedint a les instàncies (Crear, modificar i consultar)](#accedint-a-les-inst%C3%A0ncies-crear-modificar-i-consultar)
      - [instances($census_name, $entity_name, $options=[])](#instancescensus_name-entity_name-options)
      - [search($census_name, $entity_name, $filters, $options=[])](#searchcensus_name-entity_name-filters-options)
      - [instancesTree($census_name, $entity_name, $field_name, $options=[])](#instancestreecensus_name-entity_name-field_name-options)
      - [instance($census_name, $entity_name, $id, $options=[])](#instancecensus_name-entity_name-id-options)
      - [getInstanceField($census_name, $entity_name, $id, $field_name)](#getinstancefieldcensus_name-entity_name-id-field_name)
      - [createInstance($census_name, $entity_name, $fields)](#createinstancecensus_name-entity_name-fields)
      - [deleteInstance($census_name, $entity_name, $id, $hard=false)](#deleteinstancecensus_name-entity_name-id-hardfalse)
      - [updateInstance($census_name, $entity_name, $id, $fields)](#updateinstancecensus_name-entity_name-id-fields)
      - [updateInstanceField($census_name, $entity_name, $id, $field_name, $value)](#updateinstancefieldcensus_name-entity_name-id-field_name-value)
      - [clearInstanceField($census_name, $entity_name, $id, $field_name)](#clearinstancefieldcensus_name-entity_name-id-field_name)
      - [addInstanceFieldItem($census_name, $entity_name, $id, $field_name, $value)](#addinstancefielditemcensus_name-entity_name-id-field_name-value)
      - [removeInstanceFieldItem($census_name, $entity_name, $id, $field_name, $item_id)](#removeinstancefielditemcensus_name-entity_name-id-field_name-item_id)
    - [Graelles (Grids)](#graelles-grids)
      - [getInstanceGridItems($census_name, $entity_name, $id, $grid_name)](#getinstancegriditemscensus_name-entity_name-id-grid_name)
      - [getInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id)](#getinstancegriditemcensus_name-entity_name-id-grid_name-grid_item_id)
      - [addInstanceGridItem($census_name, $entity_name, $id, $grid_name, $values=[])](#addinstancegriditemcensus_name-entity_name-id-grid_name-values)
      - [updateInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id, $values=[])](#updateinstancegriditemcensus_name-entity_name-id-grid_name-grid_item_id-values)
      - [removeInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id)](#removeinstancegriditemcensus_name-entity_name-id-grid_name-grid_item_id)
    - [Entitats relacionades](#entitats-relacionades)
      - [addInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)](#addinstancerelateditemcensus_name-entity_name-id-field_name-item_id)
      - [removeInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)](#removeinstancerelateditemcensus_name-entity_name-id-field_name-item_id)
    - [Attachments (documents)](#attachments-documents)
      - [getAttachment($attachment_id)](#getattachmentattachment_id)
      - [getAttachmentContent($attachment_id)](#getattachmentcontentattachment_id)
      - [downloadAttachment($attachment_id)](#downloadattachmentattachment_id)
    - [Trees Feature](#trees-feature)
      - [getTrees($options=[])](#gettreesoptions)
      - [getTree($tree_id, $options=[])](#gettreetree_id-options)
      - [getTreeNodes($tree_id, $options=[])](#gettreenodestree_id-options)
      - [getNode($tree_id, $node_id, $options=[])](#getnodetree_id-node_id-options)
      - [getNodeChildren($tree_id, $node_id, $options=[])](#getnodechildrentree_id-node_id-options)
      - [getNodeParent($tree_id, $node_id, $options=[])](#getnodeparenttree_id-node_id-options)
      - [getNodeAncestors($tree_id, $node_id, $options=[])](#getnodeancestorstree_id-node_id-options)
      - [getNodeDescendants($tree_id, $node_id, $options=[])](#getnodedescendantstree_id-node_id-options)
      - [getNodeSiblings($tree_id, $node_id, $direction=null, $options=[])](#getnodesiblingstree_id-node_id-directionnull-options)
  - [Classes](#classes)
    - [Census](#census)
      - [entity($entity_name)](#entityentity_name)
      - [entities()](#entities-1)
    - [Entity](#entity)
      - [fields()](#fields)
      - [field($short_name)](#fieldshort_name)
      - [relatedEntity($short_name)](#relatedentityshort_name)
      - [forCensus($census_name)](#forcensuscensus_name)
      - [all($options=[])](#alloptions)
      - [get($id, $options=[])](#getid-options)
      - [search($filters, $options=[])](#searchfilters-options)
      - [tree( $short_name, $options=[])](#tree-short_name-options)
      - [create( $options=[])](#create-options)
    - [Field](#field)
      - [settings()](#settings)
      - [options()](#options)
      - [gridFields()](#gridfields)
      - [relatedEntity()](#relatedentity)
    - [Instance](#instance)
      - [entity()](#entity)
      - [census()](#census)
      - [update($fields)](#updatefields)
      - [get($field_name)](#getfield_name)
      - [set($field_name, $value)](#setfield_name-value)
      - [add($field_name, $value)](#addfield_name-value)
      - [clear($field_name)](#clearfield_name)
      - [remove($field_name, $item_id)](#removefield_name-item_id)
      - [delete()](#delete)
      - [destroy()](#destroy)
    - [TreeNode](#treenode)
      - [parent($options=[])](#parentoptions)
      - [siblings($direction=null, $options=[])](#siblingsdirectionnull-options)
      - [descendants($options=[])](#descendantsoptions)
      - [ancestors($options=[])](#ancestorsoptions)
      - [children($options=[])](#childrenoptions)
- [Vía Base de Dades (Eloquent )](#v%C3%ADa-base-de-dades-eloquent-)
  - [Camps data](#camps-data)
  - [Integracions i Mapes](#integracions-i-mapes)
  - [Relacions](#relacions)
  - [Selects](#selects)
  - [Grids](#grids)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Instalació
```bash
composer require ajtarragona/censat-client:"@dev"
``` 

## Configuració
Pots configurar el paquet a través de l'arxiu `.env` de l'aplicació. Aquests son els parámetres disponibles :

Per a l'accés via API
```bash
CENSAT_DEBUG
CENSAT_API_URL // no incloure la versio a la URL
CENSAT_API_VERSION
CENSAT_API_USER
CENSAT_API_PASSWORD
CENSAT_API_TOKEN
```

*Si definim un token, es farà servir aquest per totes les peticions. Si no, se'n crearà un de nou per cada petició a partir de l'usuari i password.
És recomanable doncs definir un token ja que es faran la meitat de crides. Censat genera tokens amb una expiració d'un any a través del mètode Login de la seva Api.*


Per a l'accés via Base de Dades
```bash
CENSAT_DB_HOST
CENSAT_DB_PORT
CENSAT_DB_DATABASE
CENSAT_DB_USERNAME
CENSAT_DB_PASSWORD
```

Alternativament, pots publicar l'arxiu de configuració del paquet amb la comanda:

```bash
php artisan vendor:publish --tag=ajtarragona-censat-config
```

Això copiarà esl arxiu a `censat-api.php` i `censat-database.php` a la carpeta `config`.

 

## Via API
Un cop configurat, el paquet està a punt per fer-se servir.
Ho pots fer de les següents maneres:


**A través d'una `Facade`:**
```php
use Censat;
...
public  function  test(){
    $censos=Censat::censuses();
    ...
}
```

Per Laravel < 5.6, cal registrar l'alias de la Facade a l'arxiu `config/app.php` :
 
```php
'aliases'  =>  [
    ...
    'Censat'  =>  Ajtarragona\Censat\Facades\Censat::class
]
```

  

**Vía Injecció de dependències:**
Als teus controlladors, helpers, model:


```php
use Ajtarragona\Censat\Models\CensatClient;
...

public  function  test(CensatClient  $censat){
    $censos=$censat->censuses();
    ...
}
```

**Vía funció `helper`:**
```php
...
public  function  test(){
    $censos=censat()->censuses();
    ...
}
```

  
  
  

### Funcions

#### Definició de censos i entitats 

##### censuses()
Returns all censuses. Objects of class [Census](#census)

##### census($short_name)
Returns a census given its name.

##### censusEntities($short_name)
Returns a census entities given the census name. Objects of class [Entity](#entity)

##### entities()
Returns all entities.

##### entity($short_name)
Returns an entity given its name.

##### entityFields($short_name)
Returns an entity fields given the entity name. Objects of class [Field](#field)

##### entityField($entity_name, $field_name)
Returns a single entity field given the entity name and the field name.

##### entityGridFields($short_name, $grid_name)
Returns the fields of an entity grid, given the entity name and the grid name.


  

#### Accedint a les instàncies (Crear, modificar i consultar)


<a name="instances"></a>

##### instances($census_name, $entity_name, $options=[])
Returns all instances in a given census and entity. Objects of class [Instance](#instance)

###### Options:
-  *fields* : comma separated field names that will be returned. See [fields](#fields) section for more info.
-  *parsevalues* : true will return all object and array values as strings.
-  *separator* : is parsevalues is set to true, multiple fields will be returned as a comma separated string. With this options you can set a diferent separator character/s.
-  *exclude* : comma separated field names that will NOT be returned.
-  *sort* : name of the field to sort by.
-  *direction* : sort direction (asc or desc).
-  *paginate* : true to paginate (default false).
-  *page* : page number.
-  *pagesize* : page size (default 10).

<a name="fields"></a> 

###### Fields:
Podem especificar quins camps volem que se'ns retornis amb una llista dels noms, separats per coma.

Tenim l'alias *basefields* que ens retornarà els camps base (id, dates, versio...)

Però a més, podem navegar pel model de dades mitjançant notació punt. Per exemple si volem que ens retorni l'id d'un camp de relació, podem fer:

```
nom_camp_relacio.id
```

En el cas de grids o camps múltiples (relacions, integracions, selects) tenim els accessors especials:
```
nom_grid.0 (o l'índex que vulguem)
nom_grid.first 
nob_grid.last
```

I la gràcia és que tot plegat es pot concatenar:
```
nom_camp.0.nom_grid.1.camp_usuari.username
```


**Formatadors**
Sobre cada camp, podem aplicar formatadors separant-los amb el caràcter `|`. Aquests s'aplicaran al valor retornat. 
Per exemple podem fer que ens retorni un valor de text en majúscules:
```
nom_camp|upper
```

Es poden concaternar varios formatadors que s'aplicaran en ordre.
```
nom_camp|upper|lower
```

Aquests son els disponibles:

- **upper**: passa a majúscules
- **lower**: passa a minúscules
- **serialize**: serialitza
- **csv(delimiter?;enclosure?)**: passa a csv, en cas que ens tornin arrays (opcionalment podem definir el caracter delimitador i les cometes per enbolcallar els texts)
- **json**: passa a json
- **array**: passa a rray si era un objecte
- **object**: passa a objecte si era un array

- **matricula/format/string(format?)**: els 3 fan el mateix. Mostra una versió textual del camp retornat.
- **pad(num;char?;position?)**: afegeix zeros per l'equerra (podem passar el numero de zeros, i opcionalment el caracter a afegir, si no volem q sigui zero i la  posicio: (0: esquerra, 1: dreta, 2:els dos costats) )
- **count**: retorna el numero d'elements si es retorna un array


<a name="search"></a>

##### search($census_name, $entity_name, $filters, $options=[])
Search instances in a given census and entity.

###### Filters:
Must be an array of filters or filtergroups:
A filter must be a key-value array with following attributes:
-  *id* : name of the field to filter
-  *value* : value of the field
-  *operation* : Logic Operation (=, !=, <, > , <=, >=, contains, starts_with, ends_with, in, not in, isnull, isnotnull) (default =)


A filtergroup must be a key-value array with following attributes:
-  *concat* : and / or
-  *filters* : array of filters or filtergroups

  

```php
[
    ["id"=>  "name",  "value"=>"john",  "operation"=>"contains"],
    ["id"=>  "gender",  "value"=>"male"],  //default operation =
    ["id"=>  "age",  "value"=>18,  "operation"=>">"],
    ["id"=>  "active",  "operation"=>"isnotnull"],  //value not needed
    ["id"=>  "tags",  "operation"=>"in",  "value"=>[1,2,3]],
    ["concat"=>  "or",  "filters"=>[
        ["id"=>  "gender",  "value"=>"female"]
    ]]
]
```

###### Options:

- See [instances](#instances) method


##### instancesTree($census_name, $entity_name, $field_name, $options=[])
Returns the whole instances tree given a census name, an entity name and the field name that establishes the instances parenthood hyerarchy.

###### Options:

-  *parent_id* : id of the root instance to start the tree with (defaults to null)
-  *sort* : name of the field to sort by.
-  *direction* : sort direction (asc or desc).
-  *filters* : see [search](#search) method


##### instance($census_name, $entity_name, $id, $options=[])
Returns a single instance given a census name, an entity name and the instance id.

###### Options:

-  *fields* : comma separated field names that will be returned. Alias "basefields" will return id, version and dates.
-  *parsevalues* : true will return all object and array values as strings.
-  *exclude* : comma separated field names that will NOT be returned.


##### getInstanceField($census_name, $entity_name, $id, $field_name)
Get an instance field given a census and entity, the instance id and the field name.

<a name="createInstance"></a>

##### createInstance($census_name, $entity_name, $fields)
Create an instance in a given census and entity. 
Returns the created instance or an exception.

- Fields must be a key-value array with the field names and its values. 
- For multiple values like selects, relations, documents or grids, use arrays.
- Select and relation fields expect the ID of the related values.
- Document-type fields expect and array with 'file-name' and 'file-content' (binary content)
- Integration fields (like LDAP users and UOs) expect the PK of the integration (username and code in the examples).


```php
    try{
        $instance= Censat::createInstance("census_name","entity_name",[
            "name" => "John",
            "surname" => "Smith",
            "age" => 25,
            "addresses_grid"=> [
                [
                    "street"=>"Fake street",
                    "number"=> 1
                ],
                [
                    "street"=>"Dumb street",
                    "number"=> 33
                    "floor" => 1
                ]
            ],
            "tags" => [1,2,4], //relation field
            'document_simple' => [
                "file-name"=>"doc_name.pdf",
                "file-content"=>$binary_content
            ],
            'document_multiple' => [
                [
                    "file-name"=>"doc_name1.pdf",
                    "file-content"=>$binary_content
                ],
                [
                    "file-name"=>"doc_name2.pdf",
                    "file-content"=>$binary_content
                ]
            ]
        ]);
    }catch(Exception $e){
        ...
    }
```


##### deleteInstance($census_name, $entity_name, $id, $hard=false)
Delete and instance given a census and entity and the instance id.
By default it is a soft delete. Hard delete can be forced setting the parameter `hard`to true.
Returns true or an Exception

##### updateInstance($census_name, $entity_name, $id, $fields)
Update an instance given a census and entity and the instance id.
Fields must be a  key-value array with the field names and its values.
Returns the updated instance or an Exception.

##### updateInstanceField($census_name, $entity_name, $id, $field_name, $value)
Update an instance single field.
Returns the updated instance or an Exception.

##### clearInstanceField($census_name, $entity_name, $id, $field_name)
Clears (sets to null) an instance field.
Returns the updated instance or an Exception.
 
##### addInstanceFieldItem($census_name, $entity_name, $id, $field_name, $value)
Add an item to an instance field, given its value. 
It is useful for multiple fields (relations, selects, integrations or grids).
Returns the updated instance or an Exception.
   
##### removeInstanceFieldItem($census_name, $entity_name, $id, $field_name, $item_id)
Removes an instance field item, given its id
It is useful for multiple fields (relations, selects, integrations or grids).
Returns the updated instance or an Exception.


 

#### Graelles (Grids)
 
##### getInstanceGridItems($census_name, $entity_name, $id, $grid_name)
Returns the items of an instance grid.
  
##### getInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id)
Returns an item of an instance grid, given its id.
  
##### addInstanceGridItem($census_name, $entity_name, $id, $grid_name, $values=[])
Add an item to an instance grid, given its value. 
Returns the updated instance an Exception.
 
##### updateInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id, $values=[])
Updates an instance grid item.
Returns the updated instance an Exception.
  
##### removeInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id)
Removes an instance grid item, given its id.
Returns the updated instance an Exception.
 
  
#### Entitats relacionades

##### addInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)
Add an item to an instance relation field, given its id. 
Returns the updated instance an Exception.

##### removeInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)
Remove an item from an instance relation field, given its id. 
Returns the updated instance an Exception.


  
#### Attachments (documents)

##### getAttachment($attachment_id)
Returns an attachment info given its Censat ID


##### getAttachmentContent($attachment_id)
Returns an attachment content (encoded in base64) given its Censat ID

##### downloadAttachment($attachment_id)
Downloads (streams the file through the response) an attachment given its Censat ID 


#### Trees Feature
##### getTrees($options=[])
Retorna tots els arbres

##### getTree($tree_id, $options=[])
Retorna un arbre passant el seu ID o short_name

##### getTreeNodes($tree_id, $options=[])
Retorna els nodes d'un arbre

###### Options:
-  *parent_id* : Si s'especifica, retorna nodes a partir d'aquest node arrel.
-  *with_instance* : Retorna més dades de la instància d'aquest node.
-  *hyerarchical* : Retorna els nodes de forma jeràrquica (per defecte true).
-  *depth* : Nivell de profunditat (Si no s'especifica es retornen tots els nivells).
-  *term* : Paraula de cerca per filtrar els nodes (si s'especifica, es retornaran els nodes sense jerarquia).
-  *instance_id* : Retorna els nodes en que l'id d'instància sigui el passat.
-  *entity_id* : Retorna els nodes en que l'id de la entitat sigui el passat.
-  *census_id* : Retorna els nodes en que l'id del cens sigui el passat.

##### getNode($tree_id, $node_id, $options=[])
Retorna un node d'un arbre passant els seus IDs.

##### getNodeChildren($tree_id, $node_id, $options=[])
Retorna els fills d'un node

##### getNodeParent($tree_id, $node_id, $options=[])
Retorna el pare d'un node

##### getNodeAncestors($tree_id, $node_id, $options=[])
Retorna els ancestres d'un node

##### getNodeDescendants($tree_id, $node_id, $options=[])
Retorna els descendents d'un node

##### getNodeSiblings($tree_id, $node_id, $direction=null, $options=[])
Retorna els germans d'un node. Es pot especificar si volem només els següents (next) o anteriors (prev)



### Classes
Els diferents mètodes de consulta retornen objectes de diferents classes. A través d'aquestes classes també podem fer crides a diferents mètodes.

<a name="census"></a>

#### Census

##### entity($entity_name)

Returns an entity in the census given its name
```php
$entity=$census->entity('test')
```

##### entities()
Returns all the census entities

<a name="entity"></a>

#### Entity

##### fields()
Returns all the entity fields

##### field($short_name)
Returns an entity field given its name

##### relatedEntity($short_name)
Returns the related entity given an entity-relation field name

##### forCensus($census_name)
Locates the entity in the given census. The following methods only work if this has been called previously

##### all($options=[])
Returns all the instances of the entity    
```php
$instances=$entity->forCensus("census_name")->all();
```
##### get($id, $options=[])
Returns an instance of the entity given its id. See [instances](#instances) method available options.

##### search($filters, $options=[])
Search instances in the entity. See [search](#search) method for filter options.

##### tree( $short_name, $options=[])
Returns the whole instances tree in the entity given the field name that establishes the instances parenthood hyerarchy.

##### create( $options=[])
Creates an instance in the entity. See [createInstance](#createInstance) method


<a name="field"></a>

#### Field

##### settings()
Returns the field settings

##### options()
For select field types, returns the select options.

##### gridFields()
For grid field types, returns the grid fields.

##### relatedEntity()
For entity-relation field types, returns the related entity
 

<a name="instance"></a>

#### Instance

##### entity()
Return the instance entity

##### census()
Return the instance census

##### update($fields)
Updates the instance given an array of fields.  See [createInstance](#createInstance) method

##### get($field_name)
Gets the value of given field

##### set($field_name, $value)
Sets the value of given field

##### add($field_name, $value)
Adds a value to a given field. Useful for multiple fields and grids.

##### clear($field_name)
Clears (sets to null) the given field.

##### remove($field_name, $item_id)
Removes an item from a given field. Useful for multiple fields and grids.

##### delete()
Soft deletes the instance

##### destroy()
Destroys the instance

<a name="treenode"></a>

#### TreeNode

##### parent($options=[])
Retorna el no de pare
##### siblings($direction=null, $options=[])
Retorna els germams
##### descendants($options=[])
Retorna els descendents
##### ancestors($options=[])
Retorna els ancestres
##### children($options=[])
Retorna els fills directes
     

## Vía Base de Dades (Eloquent )
Alternativament podem fer servir Eloquent per accedir als models del censat, sempre i quant tinguem accés a la base de dades.

Simplement cal definir els nostres models extenent el model base `CensatEntityModel`.

```php
namespace App\Models;

use Ajtarragona\Censat\Models\Eloquent\CensatEntityModel;

class NomEntitat extends CensatEntityModel
{
    public $entity_name = 'nom_entitat';  // nom de la entitat
    public $census_id = 28; //id del cens (només necessari si la entitat està a més d'un cens)
    
}

```

Estem treballant amb models Eloquent, per la qual cosa tenim disponible tota la seva funcionalitat: QueryBuilder, Relacions,  Scopes, Mutators, etc.

[Documentació d'Eloquent](https://laravel.com/docs/5.8/eloquent)


> <span style="color:#ce3b3b">**IMPORTANT!** Per ara només és aconsellable fer servir aquest mètode per consulta. Realitzar modificacions (create, update, delete) directament podria donar generar inconsistència a les dades: no s'auditen els canvis, no s'actualitza caché...</span>

### Camps data
Si la nostra entitat té algun camp de tipus **Data**, podem indicar-ho aprofitant el mutator de Laravel `$dates`.

```php
class Tramit extends CensatEntityModel
{
    ...
    protected $dates = [
        'data_inici',
        'data_final'
    ];
    ...
    
```
### Integracions i Mapes
Els camps de tipus **Integració** o de tipus **Mapa** son objectes json internament, per la qual cosa ho podem indicar aprofitant el casting d'atributs de Laravel:

```php
class Tramit extends CensatEntityModel
{
    ...
    protected $casts = [
        'unitat_organica' => 'object'
    ];
    ...
    
```

### Relacions
Si una entitat té algun camp de **Relació**, ho podem indicar a partir dels atributs `$simple_relations` i `$multiple_relations`, indicant el nom del camp i el nom de la classe que modela la entitat relacionada.

```php

class Tramit extends CensatEntityModel
{
 
    public $entity_name = 'tramit';
    
    
    protected $simple_relations = [
        'estruc_org' => '\App\Models\Tramits\UnitatOrganica'
    ];

    protected $multiple_relations = [
        'classificacio_tematica' => '\App\Models\Tramits\TematicaTramit',
        'classificacio_perfil' => '\App\Models\Tramits\Perfil'
    ];

    ...

```

Definint els camps de relació d'aquesta manera aquests automàticament esdevenen relacions Eloquent al model. Podem fer coses com aquestes, per exemple:
```php
$tramit=Tramit::find(1);
$tramit->estruc_org; //aixo retorna una instància de \App\Models\Tramits\UnitatOrganica o null
$tramit->classificacio_tematica; //aixo retorna una col·lecció
$tramit->classificacio_tematica()->where('id','>',10)->orderBy('id') //aqui tenim el QueryBuilder

$tramits=Tramit::has('classificacio_tematica')->get() //retorna tramits amb alguna classsificació temàtica

```
Aquests exemples d'Eloquent son extensibles als camps Select i Grids que veurem tot seguit.


### Selects
Si una entitat té algun camp de tipus **Select**, ho podem indicar a partir dels atributs `$simple_selects` i `$multiple_selects`, indicant el nom del camp i el nom de la classe que modela el select.

```php

class Tramit extends CensatEntityModel
{
 
    public $entity_name = 'tramit';
    
    
    protected $simple_selects = [
        'tipus_instancia' => '\App\Models\Tramits\TipusSolicitud',
        'destinatari' => '\App\Models\Tramits\Destinatari'
    ];

          
    protected $multiple_selects = [
        'formes_tramitacio' => '\App\Models\Tramits\FormaTramitacio'
    ];

    ...

```

Aquesta classe del Select haurà d'extendre la classe `CensatSelectModel`, indicant el nom de la entitat i el nom del camp.

```php
namespace App\Models\Tramits;
use Ajtarragona\Censat\Models\Eloquent\CensatSelectModel;

class TipusSolicitud extends CensatSelectModel
{
    public $entity_name="tramit";
    public $field_name="tipus_instancia";

}  
```


### Grids
Si una entitat té alguna graella, ho podem indicar a partir de l'atribut `$grids`, indicant el nom de la graella i el nom de la classe que la modela.
```php

class Tramit extends CensatEntityModel
{
 
    public $entity_name = 'tramit';
    
    
    protected $grids = [
        'autors' => '\App\Models\Tramits\Autor'
    ];   
    
    ...

```

Aquesta classe del Grid haurà d'extendre la classe `CensatGridModel`, indicant el nom de la entitat i el nom de la graella.

```php
namespace App\Models\Tramits;
use Ajtarragona\Censat\Models\Eloquent\CensatGridModel;

class Autor extends CensatGridModel
{
    public $entity_name ="tramit";
    public $grid_name ="autors";

   ...
}    
```