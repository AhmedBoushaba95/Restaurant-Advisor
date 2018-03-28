# Laravel API FOR TRIP ADVISOR

**Make sure php => 7.1  is install in your machine !!**

## This Project use LARAVEL MIGRATION !!

## For Migrate all table :

###### php artisan migrate

## For create new Resource/Table :

###### php artisan make:resource (Resources Name) + php artisan make:model (Resources Name) -mc

## For Lunch the APi in the localhost:

###### php artisan serve

## For List Route :

###### php artisan route:list

## For check user is connected :

  http://127.0.0.1:8000/api/resto/1


## For register to the API (Method Post):

**"Body" => [
  Name = {Whatever}
  email = {Whatever}
  password = {Whatever}
  c_password = {Whatever}
]**

## For Login (Method Post):

**"Body" => [
  email = {Whatever},
  password = {Whatever}
];**

(It return Token !!!)

## Get resto :

**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**

## If Pb on you have this error :


  **Trying to get property of non-object in ClientRepository.php (line 80)**


  Please run :

###### php artisan passport:install --force

## For Stop the windows localhost :

###### net stop WAS

## For Update Table :

###### php artisan migrate:rollback && php artisan migrate
###### php artisan migrate:rollback && php artisan migrate && php artisan passport:install --force

**But you lost your data inside the table**

## For insert a new table in resto (idem for update):

**Format Post :
  { 'name' => {Whatever},
  'categorie' => {Whatever},
  'description' => {Whatever},
  'note' => {Whatever},
  'address' => {Whatever},
  'phone' => {Whatever},
  'website' => {Whatever},
  'open_week' => {Whatever},
  'close_week' => {Whatever},
  'open_weekend' => {Whatever},
  'close_weekend' => {Whatever} }
  In the body**

###### + dont forget the header !!
**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**

## For delete Resto // and for deleting a menu : use the url (**/resto/delete/{id}**) or use the url (**/menu/delete/{idMenu}**)


###### + dont forget the header !!
**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**

## For insert a Menu :

**Format Post :
  {
    'name' => {Whatever},
    'resto' => {resto_name or resto_id},
    'description' => {Whatever},
    'price' => {integer}
  }
  In the body**

###### + dont forget the header !!
**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**


## For Update a menus : use the url (**/resto/delete/{id_menu}**)

**Format Post :
  {
    'name' => {Whatever},
    'description' => {Whatever},
    'price' => {integer}
  }
  In the body**

###### + dont forget the header !!
**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**

## For getting Restaurant + Menus + avis:

**Method Get with Id of the restaurant / Menu WITHOUT the header**
**For specific restaurant / / menus / opinion you must include the id**

## For the opinion insert and update :

**Route for insert = "/avis/register/{idResto}"**
**Route for update = "/avis/update/{idAvis}"**
**Format Post :
  {
    'description' => {Whatever},
    'note' => {integer}
  }
  In the body**

###### + dont forget the header !!

**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**

## For the opinion deleting :

**Route for delete = "/avis/delete/{idAvis}"**
**Format GET**

###### + dont forget the header !!

**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**

## For the current user deleting :

**Route for delete = "/deleteUser"**
**Format GET**

###### + dont forget the header !!

**'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer ' . $accessTokenGetInTheLoginPost,
]**
