### RESTFul style JSON responder.

> Support laravel.

#### install
```shell
composer require xgbnl/response dev-main
```

#### GET
```json
{
  "message": "success",
  "code": 200,
  "data": null
}
```

#### POST、PATCH
```json
{
    "message": "created",
    "code": 201,
    "data": null
}
```

#### DELETE
```json
{
    "message": "noContent",
    "code": 204,
    "data": null
}
```