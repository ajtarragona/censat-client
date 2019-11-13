# Censat Client for Laravel
Client d'accés a la API Rest de l'aplicació CENSAT de l'Ajuntament de Tarragona


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

### CENSUSES AND ENTITIES DEFINITION

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


  

### WORKING WITH INSTANCES (CREATE, UPDATE AND QUERY)


#### <a name="instances"></a>instances($census_name, $entity_name, $options=[])
Returns all instances in a given census and entity. Objects of class [Instance](#instance)

###### Options:
-  *fields* : comma separated field names that will be returned. Alias "basefields" will return id, version and dates.
-  *parsevalues* : true will return all object and array values as strings.
-  *exclude* : comma separated field names that will NOT be returned.
-  *sort* : name of the field to sort by.
-  *direction* : sort direction (asc or desc).
-  *paginate* : true to paginate (default false).
-  *page* : page number.
-  *pagesize* : page size (default 10).


#### <a name="search"></a>search($census_name, $entity_name, $filters, $options=[])
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

#### <a name="createInstance"></a>createInstance($census_name, $entity_name, $fields)
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


 

### GRIDS
 
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
 
  
### RELATED ENTITIES

#### addInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)
Add an item to an instance relation field, given its id. 
Returns the updated instance an Exception.

#### removeInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id)
Remove an item from an instance relation field, given its id. 
Returns the updated instance an Exception.



## PACKAGE CLASSES
### <a name="census"></a>CENSUS
#### entity($entity_name)
Returns an entity in the census given its name
```php
$entity=$census->entity('test')
```

#### entities()
Returns all the census entities


### <a name="census"></a>ENTITY
#### fields()
Returns all the entity fields

#### field($short_name)
Returns an entity field given its name

#### relatedEntity($short_name)
Returns the related entity given an entity-relation field name

#### forCensus($census_name)
Locates the entity in the given census. The following methods only work if this has been called previously

#### all($options=[])
Returns all the instances of the entity    
```php
$instances=$entity->forCensus("census_name")->all();
```
#### get($id, $options=[])
Returns an instance of the entity given its id. See [instances](#instances) method available options.

#### search($filters, $options=[])
Search instances in the entity. See [search](#search) method for filter options.

#### tree( $short_name, $options=[])
Returns the whole instances tree in the entity given the field name that establishes the instances parenthood hyerarchy.

#### create( $options=[])
Creates an instance in the entity. See [createInstance](#createInstance) method

### <a name="field"></a>FIELD
#### settings()
Returns the field settings

#### options()
For select field types, returns the select options.

#### gridFields()
For grid field types, returns the grid fields.

#### relatedEntity()
For entity-relation field types, returns the related entity
 

### <a name="instance"></a>INSTANCE

#### entity()
Return the instance entity

#### census()
Return the instance census

#### update($fields)
Updates the instance given an array of fields.  See [createInstance](#createInstance) method

#### get($field_name)
Gets the value of given field

#### set($field_name, $value)
Sets the value of given field

#### add($field_name, $value)
Adds a value to a given field. Useful for multiple fields and grids.

#### clear($field_name)
Clears (sets to null) the given field.

#### remove($field_name, $item_id)
Removes an item from a given field. Useful for multiple fields and grids.

#### delete()
Soft deletes the instance

#### destroy()
Destroys the instance
