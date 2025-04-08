### RESTFul style JSON responder.

> Support laravel.

#### install

```shell
composer require xgbnl/response dev-main
```

#### GET Method.

```json
{
  "msg": "success",
  "code": 200,
  "data": null
}
```

#### POST、PATCH Methods.

```json
{
  "msg": "created",
  "code": 201,
  "data": null
}
```

#### DELETE Method.

```json
{
  "msg": "deleted",
  "code": 204,
  "data": null
}
```