

# Censat Client for Laravel
Client d'accés a la API Rest de l'aplicació CENSAT de l'Ajuntament de Tarragona

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Instalació](#instalaci%C3%B3)
- [Configuració](#configuraci%C3%B3)
- [Ús](#%C3%BAs)
  - [A través d'una `Facade`:](#a-trav%C3%A9s-duna-facade)
  - [Vía Injecció de dependències:](#v%C3%ADa-injecci%C3%B3-de-depend%C3%A8ncies)
  - [Vía funció `helper`:](#v%C3%ADa-funci%C3%B3-helper)
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
  - [Classes](#classes)
    - [Census](#census)
    - [Entity](#entity)
    - [Field](#field)
    - [Instance](#instance)
- [Eloquent](#eloquent)
  - [Definir els models](#definir-els-models)
    - [Camps](#camps)
    - [Tipus de camp](#tipus-de-camp)
  - [Consultar](#consultar)
    - [all](#all)
    - [find](#find)
    - [QueryBuilder](#querybuilder)
    - [where](#where)
    - [Scopes](#scopes)
  - [Crear i Actualitzar](#crear-i-actualitzar)
  - [Esborrar](#esborrar)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Instalació
```bash
composer require ajtarragona/censat-client:"@dev"
``` 

## Configuració
Pots configurar el paquet a través de l'arxiu `.env` de l'aplicació. Aquests son els parámetres disponibles :

```bash
CENSAT_DEBUG
CENSAT_API_URL
CENSAT_API_USER
CENSAT_API_PASSWORD
```


Alternativament, pots publicar l'arxiu de configuració del paquet amb la comanda:

```bash
php artisan vendor:publish --tag=ajtarragona-censat-config
```

Això copiarà l'arxiu a `config/censatclient.php`.

 

## Ús
Un cop configurat, el paquet està a punt per fer-se servir.
Ho pots fer de les següents maneres:


### A través d'una `Facade`:
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

  

### Vía Injecció de dependències:
Als teus controlladors, helpers, model:


```php
use Ajtarragona\Censat\Models\CensatClient;
...

public  function  test(CensatClient  $censat){
    $censos=$censat->censuses();
    ...
}
```

### Vía funció `helper`:
```php
...
public  function  test(){
    $censos=censat()->censuses();
    ...
}
```

  
  
  

## Funcions

### Definició de censos i entitats 

#### censuses()
Returns all censuses. Objects of class [Census](#census)

#### census($short_name)
Returns a census given its name.

#### censusEntities($short_name)
Returns a census entities given the census name. Objects of class [Entity](#entity)

#### entities()
Returns all entities.

#### entity($short_name)
Returns an entity given its name.

#### entityFields($short_name)
Returns an entity fields given the entity name. Objects of class [Field](#field)

#### entityField($entity_name, $field_name)
Returns a single entity field given the entity name and the field name.

#### entityGridFields($short_name, $grid_name)
Returns the fields of an entity grid, given the entity name and the grid name.


  

### Accedint a les instàncies (Crear, modificar i consultar)


<a name="instances"></a>

#### instances($census_name, $entity_name, $options=[])
Returns all instances in a given census and entity. Objects of class [Instance](#instance)

###### Options:
-  *fields* : comma separated field names that will be returned. Alias "basefields" will return id, version and dates.
-  *parsevalues* : true will return all object and array values as strings.
-  *separator* : is parsevalues is set to true, multiple fields will be returned as a comma separated string. With this options you can set a diferent separator character/s.
-  *exclude* : comma separated field names that will NOT be returned.
-  *sort* : name of the field to sort by.
-  *direction* : sort direction (asc or desc).
-  *paginate* : true to paginate (default false).
-  *page* : page number.
-  *pagesize* : page size (default 10).


<a name="search"></a>

#### search($census_name, $entity_name, $filters, $options=[])
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


#### instancesTree($census_name, $entity_name, $field_name, $options=[])
Returns the whole instances tree given a census name, an entity name and the field name that establishes the instances parenthood hyerarchy.

###### Options:

-  *parent_id* : id of the root instance to start the tree with (defaults to null)
-  *sort* : name of the field to sort by.
-  *direction* : sort direction (asc or desc).
-  *filters* : see [search](#search) method


#### instance($census_name, $entity_name, $id, $options=[])
Returns a single instance given a census name, an entity name and the instance id.

###### Options:

-  *fields* : comma separated field names that will be returned. Alias "basefields" will return id, version and dates.
-  *parsevalues* : true will return all object and array values as strings.
-  *exclude* : comma separated field names that will NOT be returned.


#### getInstanceField($census_name, $entity_name, $id, $field_name)
Get an instance field given a census and entity, the instance id and the field name.

<a name="createInstance"></a>

#### createInstance($census_name, $entity_name, $fields)
Create an instance in a given census and entity. 
Fields must be a  key-value array with the field names and its values. 
For multiple values like selects, relations or grids, use arrays.

Returns the created instance or an exception.

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
            "tags" => [1,2,4]
        ]);
    }catch(Exception $e){
        ...
    }
```


#### deleteInstance($census_name, $entity_name, $id, $hard=false)
Delete and instance given a census and entity and the instance id.
By default it is a soft delete. Hard delete can be forced setting the parameter `hard`to true.
Returns true or an Exception

#### updateInstance($census_name, $entity_name, $id, $fields)
Update an instance given a census and entity and the instance id.
Fields must be a  key-value array with the field names and its values.
Returns the updated instance or an Exception.

#### updateInstanceField($census_name, $entity_name, $id, $field_name, $value)
Update an instance single field.
Returns the updated instance or an Exception.

#### clearInstanceField($census_name, $entity_name, $id, $field_name)
Clears (sets to null) an instance field.
Returns the updated instance or an Exception.
 
#### addInstanceFieldItem($census_name, $entity_name, $id, $field_name, $value)
Add an item to an instance field, given its value. 
It is useful for multiple fields (relations, selects, integrations or grids).
Returns the updated instance or an Exception.
   
#### removeInstanceFieldItem($census_name, $entity_name, $id, $field_name, $item_id)
Removes an instance field item, given its id
It is useful for multiple fields (relations, selects, integrations or grids).
Returns the updated instance or an Exception.


 

### Graelles (Grids)
 
#### getInstanceGridItems($census_name, $entity_name, $id, $grid_name)
Returns the items of an instance grid.
  
#### getInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id)
Returns an item of an instance grid, given its id.
  
#### addInstanceGridItem($census_name, $entity_name, $id, $grid_name, $values=[])
Add an item to an instance grid, given its value. 
Returns the updated instance an Exception.
 
#### updateInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id, $values=[])
Updates an instance grid item.
Returns the updated instance an Exception.
  
#### removeInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id)
Removes an instance grid item, given its id.
Returns the updated instance an Exception.
 
  
### Entitats relacionades

#### addInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)
Add an item to an instance relation field, given its id. 
Returns the updated instance an Exception.

#### removeInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)
Remove an item from an instance relation field, given its id. 
Returns the updated instance an Exception.



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



## Eloquent 
Alternativament podem fer servir Eloquent (o una reducció d'aquest) per accedir als models del censat.
Simplement cal definir els nostres models extenent el model base de Censat.

### Definir els models

Per crear un model d'una entitat hem de fer una classe que extengui la classe `Ajtarragona\Censat\Models\Eloquent\CensatEntity`

I definir el nom del cens i de la entitat a través de les propietats protegides `$census_name` i `$entity_name`

```php
<?php

namespace App\Models;

use Ajtarragona\Censat\Models\Eloquent\CensatEntity;

class Activitat extends CensatEntity{
    protected $census_name = "activitats";
    protected $entity_name = "activitat";
}

```

#### Camps

Podem indicar els camps que es recuperaran de la entitat a través de la propietat protegida `$attributes`. 
    
```php
class Activitat extends CensatEntity{
    protected $census_name = "activitats";
    protected $entity_name = "activitat";
    
    protected $attributes = ['id','created_at','nom'];

}
```

Si no ho especifiquem es retornaran tots. Alternativament, podem definir quins camps no volem que es tornin:
```php
class Activitat extends CensatEntity{
    protected $census_name = "activitats";
    protected $entity_name = "activitat";
    
    protected $attributes = [];
    protected $excluded = ['id','created_at','nom'];

}
```

#### Tipus de camp
Podem indicar els tipus de dades d'alguns camps per què al recuperar-los es parsegin automàticament.

```php
    protected $maps = ["adreca"];
    protected $images = ["imatge"];
    protected $selects = ["estat_de_publicacio","modalitat_venda"];
```

Pels camps de relació, hem d'indicar la classe del model relacionat, que també caldrà que estigui creat.
```php
    protected $relations = [
        "tipus_comerc" => "App\Models\TipusComerc"
    ];
```

### Consultar
#### all
El mètode `all` ens retorna una col·lecció amb totes les instàncies de la entitat. Cal utilitzar amb precaució amb entitats amb molts registres. En aquest cas tenim opcions de [paginació](#pagination).
```php
    $comercos=Comerc::all();
```
#### find
Retorna una instància de la entitat, passat el seu id (a censat sempre la clau primària serà el camp ID).
```php
    $comerc=Comerc::find(1);
```

#### QueryBuilder
#### Agregats
#### where
#### select

<a name="pagination"></a>

#### Paginació
Paginació

#### Odenació
#### Scopes
Podem crear scopes per modificar el QueryBuilder
```php
class Activitat extends CensatEntity{
    protected $census_name = "activitats";
    protected $entity_name = "activitat";
    
    public static function scopePublished($query){
        $query->where('estat_de_publicacio', 1);
    }
}
```

Aleshores utilitzar-lo dins del query builder:

```php
    $comercos=Comerc::published()->where('tipus',43)->get();
```

### Crear i Actualitzar
### Esborrar