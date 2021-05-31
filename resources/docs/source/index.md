---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#Authentication management


APIs for Authenticating users
<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Login user and create token

> Example request:

```bash
curl -X POST \
    "http://localhost/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"email":"deserunt","password":"maiores","remember_me":true}'

```

```javascript
const url = new URL(
    "http://localhost/api/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "deserunt",
    "password": "maiores",
    "remember_me": true
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/login`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | the email of the user
        `password` | string |  required  | the users prefered password
        `remember_me` | boolean |  optional  | 
    
<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_90f45d502fd52fdc0b289e55ba3c2ec6 -->
## Create user

the user signup routs

> Example request:

```bash
curl -X POST \
    "http://localhost/api/signup" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"quasi","email":"ea","phone":"enim","password":"optio","password_confirmation":"commodi"}'

```

```javascript
const url = new URL(
    "http://localhost/api/signup"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "quasi",
    "email": "ea",
    "phone": "enim",
    "password": "optio",
    "password_confirmation": "commodi"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/signup`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | the full name of the user
        `email` | string |  required  | the email of the user , this value is unige
        `phone` | string |  required  | the valide phone number of the user, this value is unige
        `password` | string |  required  | the users prefered password
        `password_confirmation` | string |  required  | the confirmation password. must be thesame as the password
    
<!-- END_90f45d502fd52fdc0b289e55ba3c2ec6 -->

<!-- START_5a7fab33595a7b2f9beeee8b8c494f92 -->
## User Activation

* @urlParam  token string required the token sent to the users email address

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/signup/activate/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/signup/activate/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/signup/activate/{token}`


<!-- END_5a7fab33595a7b2f9beeee8b8c494f92 -->

<!-- START_00e7e21641f05de650dbe13f242c6f2c -->
## Logout user (Revoke the token)

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/logout`


<!-- END_00e7e21641f05de650dbe13f242c6f2c -->

<!-- START_2b6e5a4b188cb183c7e59558cce36cb6 -->
## Get the authenticated User

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/user`


<!-- END_2b6e5a4b188cb183c7e59558cce36cb6 -->

<!-- START_86c0dd6f359bad9ecc9205dab5245583 -->
## Update user

> Example request:

```bash
curl -X POST \
    "http://localhost/api/user/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"ipsa","email":"suscipit","phone":"nobis"}'

```

```javascript
const url = new URL(
    "http://localhost/api/user/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "ipsa",
    "email": "suscipit",
    "phone": "nobis"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/user/update`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | the full name of the user
        `email` | string |  required  | the email of the user , this value is unige
        `phone` | string |  required  | the valide phone number of the user, this value is unige
    
<!-- END_86c0dd6f359bad9ecc9205dab5245583 -->

#Payment management


APIs for Payment
<!-- START_6cd4ec602ffcd199483fb2d8cf889109 -->
## Get all payments.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/payment`


<!-- END_6cd4ec602ffcd199483fb2d8cf889109 -->

<!-- START_deb129964c28500a2815c8b001f0bc2e -->
## Create Payment

the payment creation

> Example request:

```bash
curl -X POST \
    "http://localhost/api/payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"product_id":"non","quantity":9,"type":"qui","status":false,"expires":"est","description":"velit"}'

```

```javascript
const url = new URL(
    "http://localhost/api/payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "product_id": "non",
    "quantity": 9,
    "type": "qui",
    "status": false,
    "expires": "est",
    "description": "velit"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `product_id` | string |  required  | 
        `quantity` | integer |  optional  | string
        `type` | string |  required  | either make-payment/receive-payment
        `status` | boolean |  optional  | true for paid false un-paid,  false by default
        `expires` | string |  optional  | expires in a week by default
        `description` | string |  optional  | 
    
<!-- END_deb129964c28500a2815c8b001f0bc2e -->

<!-- START_aeb6c4fefc495ba68a89a66aba3e16d6 -->
## Get Single Payment

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/payment/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/payment/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/payment/{payment}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | string required

<!-- END_aeb6c4fefc495ba68a89a66aba3e16d6 -->

<!-- START_8fe8c1a6fa82ee2c6fb262561ef7f8df -->
## Update a Specified Payment

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/payment/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"quantity":2,"type":"eum","status":false,"expires":"quasi","description":"modi"}'

```

```javascript
const url = new URL(
    "http://localhost/api/payment/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "quantity": 2,
    "type": "eum",
    "status": false,
    "expires": "quasi",
    "description": "modi"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/payment/{payment}`

`PATCH api/payment/{payment}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | string required the id of the payment
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `quantity` | integer |  optional  | string
        `type` | string |  required  | either make-payment/receive-payment
        `status` | boolean |  optional  | true for paid false un-paid,  false by default
        `expires` | string |  optional  | expires in a week by default
        `description` | string |  optional  | 
    
<!-- END_8fe8c1a6fa82ee2c6fb262561ef7f8df -->

<!-- START_1f6934fb97f903c5b274700ae2174e3b -->
## Delete the specified product.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/payment/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/payment/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/payment/{payment}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | string required the id of the payment

<!-- END_1f6934fb97f903c5b274700ae2174e3b -->

#Product management


APIs for Product
<!-- START_dc538d69a8586a7a3c36d4393cee42e6 -->
## Get all Products.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/product" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/product"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/product`


<!-- END_dc538d69a8586a7a3c36d4393cee42e6 -->

<!-- START_dacecdb40d12cd7c191a4cb9036946f8 -->
## api/product/create
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/product/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/product/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/product/create`


<!-- END_dacecdb40d12cd7c191a4cb9036946f8 -->

<!-- START_2d62ba7cf16a7d6db447375e13e86c34 -->
## Create Product

The Product creation

> Example request:

```bash
curl -X POST \
    "http://localhost/api/product" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"delectus","slug":"laudantium","image":"voluptatibus","product_number":"placeat","price":"quis","quantity":1,"delivery_period":"molestiae","with_payment":true,"clients_email":"fuga","payment_details":{"transaction_id":"libero","tx_ref":"velit","status":"laudantium","description":"recusandae"},"description":"consequuntur"}'

```

```javascript
const url = new URL(
    "http://localhost/api/product"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "delectus",
    "slug": "laudantium",
    "image": "voluptatibus",
    "product_number": "placeat",
    "price": "quis",
    "quantity": 1,
    "delivery_period": "molestiae",
    "with_payment": true,
    "clients_email": "fuga",
    "payment_details": {
        "transaction_id": "libero",
        "tx_ref": "velit",
        "status": "laudantium",
        "description": "recusandae"
    },
    "description": "consequuntur"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/product`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | Product Name
        `slug` | string |  optional  | Product     Slug Required if is_payment is true
        `image` | string |  optional  | Product     Image
        `product_number` | string |  optional  | Product     Number / Skew
        `price` | string |  required  | Unit Price of the product
        `quantity` | integer |  required  | Total Unit of product if empty it will default to one(1)
        `delivery_period` | string |  optional  | Possible Dilivery days (5)
        `with_payment` | boolean |  optional  | Indicate that it's both product creation and payment (true/false)
        `clients_email` | string |  optional  | Adds multiple emails to tonify/invite
        `payment_details` | array |  optional  | Required if user is creating and making payment at the same time
        `payment_details.transaction_id` | string |  optional  | Transaction ID (Sub-property of payment_details)
        `payment_details.tx_ref` | string |  optional  | Transaction refrence (Sub-property of payment_details)
        `payment_details.status` | string |  optional  | Transaction status (Sub-property of payment_details)
        `payment_details.description` | string |  optional  | Transaction Decsription (Sub-property of payment_details)
        `description` | string |  optional  | Product Description
    
<!-- END_2d62ba7cf16a7d6db447375e13e86c34 -->

<!-- START_1fcbf5d495e6ada99ea017e9ae32b380 -->
## Get Single Product

* @urlParam id string required

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/product/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/product/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/product/{product}`


<!-- END_1fcbf5d495e6ada99ea017e9ae32b380 -->

<!-- START_682327ab9f9deab00b7c603486ad935a -->
## Update a Specified Product

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/product/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"quis","product_number":"repudiandae","price":2390213.1465322347,"description":"quia","quantity":11}'

```

```javascript
const url = new URL(
    "http://localhost/api/product/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "quis",
    "product_number": "repudiandae",
    "price": 2390213.1465322347,
    "description": "quia",
    "quantity": 11
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/product/{product}`

`PATCH api/product/{product}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | string required the id of the product
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  optional  | 
        `product_number` | string |  optional  | 
        `price` | float |  optional  | 
        `description` | string |  optional  | 
        `quantity` | integer |  optional  | 
    
<!-- END_682327ab9f9deab00b7c603486ad935a -->

<!-- START_587b06cc0dc038b2e049f3a1baa2593b -->
## Delete the specified product.

* @urlParam  id string required the id of the product

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/product/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/product/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/product/{product}`


<!-- END_587b06cc0dc038b2e049f3a1baa2593b -->

<!-- START_1c24e49df01eef9b4d6eaedc77a35c51 -->
## api/product/accept/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/product/accept/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/product/accept/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/product/accept/{id}`


<!-- END_1c24e49df01eef9b4d6eaedc77a35c51 -->

<!-- START_0261d8aa51b6042fb3a6286cdd2a962a -->
## api/product/status/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/product/status/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/product/status/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/product/status/{id}`


<!-- END_0261d8aa51b6042fb3a6286cdd2a962a -->

#Transaction management


APIs for Transaction
<!-- START_4cd1705099409e5c331615adc934134b -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/transaction" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transaction"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/transaction`


<!-- END_4cd1705099409e5c331615adc934134b -->

<!-- START_47ac2ffa847b5d9b78133d3ff557474c -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/transaction/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transaction/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/transaction/create`


<!-- END_47ac2ffa847b5d9b78133d3ff557474c -->

<!-- START_63cf8353ae980733f5ed585df6b64d67 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/transaction" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transaction"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/transaction`


<!-- END_63cf8353ae980733f5ed585df6b64d67 -->

<!-- START_1145cd7510bf11264825abd3433cfbd5 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/transaction/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transaction/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/transaction/{transaction}`


<!-- END_1145cd7510bf11264825abd3433cfbd5 -->

<!-- START_5a52a1b95fb68217132d8935546d1da5 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/transaction/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transaction/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/transaction/{transaction}/edit`


<!-- END_5a52a1b95fb68217132d8935546d1da5 -->

<!-- START_5aca130abc67df3d596263727a4a1fc0 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/transaction/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transaction/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/transaction/{transaction}`

`PATCH api/transaction/{transaction}`


<!-- END_5aca130abc67df3d596263727a4a1fc0 -->

<!-- START_83b7136d851d7223a080c124169741f7 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/transaction/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transaction/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/transaction/{transaction}`


<!-- END_83b7136d851d7223a080c124169741f7 -->

#User Account Management


APIs for User Account Management
<!-- START_becd2474bdae2d3b678148120ff54292 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/account" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/account"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/account`


<!-- END_becd2474bdae2d3b678148120ff54292 -->

<!-- START_bd346fadf35959845ae3be58f4db14c9 -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/account/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/account/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/account/create`


<!-- END_bd346fadf35959845ae3be58f4db14c9 -->

<!-- START_8e41604b8817eca37cb034e144f5ff33 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/account" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/account"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/account`


<!-- END_8e41604b8817eca37cb034e144f5ff33 -->

<!-- START_1f6f7be70ceebafe69329fb7d344c91a -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/account/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/account/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/account/{account}`


<!-- END_1f6f7be70ceebafe69329fb7d344c91a -->

<!-- START_c58eca53b89feecd884d232994c0c33e -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/account/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/account/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/account/{account}/edit`


<!-- END_c58eca53b89feecd884d232994c0c33e -->

<!-- START_ebff0653e600c488fc331d410a101317 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/account/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/account/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/account/{account}`

`PATCH api/account/{account}`


<!-- END_ebff0653e600c488fc331d410a101317 -->

<!-- START_fff037991817542a80c7e20070ff00e0 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/account/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/account/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/account/{account}`


<!-- END_fff037991817542a80c7e20070ff00e0 -->

#Virtual card


<!-- START_91cd98a902bbb56e08d7d1125cc43964 -->
## APIs for Virtual card

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/card" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/card"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/card`


<!-- END_91cd98a902bbb56e08d7d1125cc43964 -->

#general


<!-- START_0c068b4037fb2e47e71bd44bd36e3e2a -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/authorize`


<!-- END_0c068b4037fb2e47e71bd44bd36e3e2a -->

<!-- START_e48cc6a0b45dd21b7076ab2c03908687 -->
## Approve the authorization request.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/authorize`


<!-- END_e48cc6a0b45dd21b7076ab2c03908687 -->

<!-- START_de5d7581ef1275fce2a229b6b6eaad9c -->
## Deny the authorization request.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/authorize`


<!-- END_de5d7581ef1275fce2a229b6b6eaad9c -->

<!-- START_a09d20357336aa979ecd8e3972ac9168 -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token`


<!-- END_a09d20357336aa979ecd8e3972ac9168 -->

<!-- START_d6a56149547e03307199e39e03e12d1c -->
## Get all of the authorized tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/tokens`


<!-- END_d6a56149547e03307199e39e03e12d1c -->

<!-- START_a9a802c25737cca5324125e5f60b72a5 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/tokens/{token_id}`


<!-- END_a9a802c25737cca5324125e5f60b72a5 -->

<!-- START_abe905e69f5d002aa7d26f433676d623 -->
## Get a fresh transient token cookie for the authenticated user.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/token/refresh" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/token/refresh"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token/refresh`


<!-- END_abe905e69f5d002aa7d26f433676d623 -->

<!-- START_babcfe12d87b8708f5985e9d39ba8f2c -->
## Get all of the clients for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/clients`


<!-- END_babcfe12d87b8708f5985e9d39ba8f2c -->

<!-- START_9eabf8d6e4ab449c24c503fcb42fba82 -->
## Store a new client.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/clients`


<!-- END_9eabf8d6e4ab449c24c503fcb42fba82 -->

<!-- START_784aec390a455073fc7464335c1defa1 -->
## Update the given client.

> Example request:

```bash
curl -X PUT \
    "http://localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT oauth/clients/{client_id}`


<!-- END_784aec390a455073fc7464335c1defa1 -->

<!-- START_1f65a511dd86ba0541d7ba13ca57e364 -->
## Delete the given client.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/clients/{client_id}`


<!-- END_1f65a511dd86ba0541d7ba13ca57e364 -->

<!-- START_9e281bd3a1eb1d9eb63190c8effb607c -->
## Get all of the available scopes for the application.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/scopes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/scopes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/scopes`


<!-- END_9e281bd3a1eb1d9eb63190c8effb607c -->

<!-- START_9b2a7699ce6214a79e0fd8107f8b1c9e -->
## Get all of the personal access tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/personal-access-tokens`


<!-- END_9b2a7699ce6214a79e0fd8107f8b1c9e -->

<!-- START_a8dd9c0a5583742e671711f9bb3ee406 -->
## Create a new personal access token for the user.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/personal-access-tokens`


<!-- END_a8dd9c0a5583742e671711f9bb3ee406 -->

<!-- START_bae65df80fd9d72a01439241a9ea20d0 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/personal-access-tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/personal-access-tokens/{token_id}`


<!-- END_bae65df80fd9d72a01439241a9ea20d0 -->

<!-- START_8e9e2f7b6568d14b197402543cdaa874 -->
## Create token password reset

> Example request:

```bash
curl -X POST \
    "http://localhost/api/password/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/password/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/password/create`


<!-- END_8e9e2f7b6568d14b197402543cdaa874 -->

<!-- START_28b16f279c1333c2efecf8a89e31b59d -->
## Find token password reset

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/password/find/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/password/find/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "status": "failed",
    "message": "This password reset token is invalid."
}
```

### HTTP Request
`GET api/password/find/{token}`


<!-- END_28b16f279c1333c2efecf8a89e31b59d -->

<!-- START_8ad860d24dc1cc6dac772d99135ad13e -->
## Reset password

> Example request:

```bash
curl -X POST \
    "http://localhost/api/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/password/reset`


<!-- END_8ad860d24dc1cc6dac772d99135ad13e -->

<!-- START_dc2449bd2273cfb20c94da6050883300 -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/card/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/card/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/card/create`


<!-- END_dc2449bd2273cfb20c94da6050883300 -->

<!-- START_37016b0e284c9a6bd51c7e77810a3876 -->
## Create Product

The Product creation

> Example request:

```bash
curl -X POST \
    "http://localhost/api/card" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"ea","slug":"maxime","image":"in","product_number":"ut","price":"facilis","quantity":20,"delivery_period":"eum","with_payment":false,"clients_email":"et","payment_details":{"transaction_id":"in","tx_ref":"quia","status":"vitae","description":"tenetur"},"description":"numquam"}'

```

```javascript
const url = new URL(
    "http://localhost/api/card"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "ea",
    "slug": "maxime",
    "image": "in",
    "product_number": "ut",
    "price": "facilis",
    "quantity": 20,
    "delivery_period": "eum",
    "with_payment": false,
    "clients_email": "et",
    "payment_details": {
        "transaction_id": "in",
        "tx_ref": "quia",
        "status": "vitae",
        "description": "tenetur"
    },
    "description": "numquam"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/card`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | Product Name
        `slug` | string |  optional  | Product     Slug Required if is_payment is true
        `image` | string |  optional  | Product     Image
        `product_number` | string |  optional  | Product     Number / Skew
        `price` | string |  required  | Unit Price of the product
        `quantity` | integer |  required  | Total Unit of product if empty it will default to one(1)
        `delivery_period` | string |  optional  | Possible Dilivery days (5)
        `with_payment` | boolean |  optional  | Indicate that it's both product creation and payment (true/false)
        `clients_email` | string |  optional  | Adds multiple emails to tonify/invite
        `payment_details` | array |  optional  | Required if user is creating and making payment at the same time
        `payment_details.transaction_id` | string |  optional  | Transaction ID (Sub-property of payment_details)
        `payment_details.tx_ref` | string |  optional  | Transaction refrence (Sub-property of payment_details)
        `payment_details.status` | string |  optional  | Transaction status (Sub-property of payment_details)
        `payment_details.description` | string |  optional  | Transaction Decsription (Sub-property of payment_details)
        `description` | string |  optional  | Product Description
    
<!-- END_37016b0e284c9a6bd51c7e77810a3876 -->

<!-- START_5b6e7e0a32e4457748fd54b2207e8d8f -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/card/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/card/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/card/{card}`


<!-- END_5b6e7e0a32e4457748fd54b2207e8d8f -->

<!-- START_06f51b110cf5876f46ce08493a6b9b92 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/card/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/card/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/card/{card}/edit`


<!-- END_06f51b110cf5876f46ce08493a6b9b92 -->

<!-- START_40ce207dd8dce7ceee4025566597e2ff -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/card/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/card/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/card/{card}`

`PATCH api/card/{card}`


<!-- END_40ce207dd8dce7ceee4025566597e2ff -->

<!-- START_84a6c0fce8a861651eb03d7b61ec190c -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/card/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/card/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/card/{card}`


<!-- END_84a6c0fce8a861651eb03d7b61ec190c -->

<!-- START_e2e0a6d8936f87ea1d1df54528fdb726 -->
## fund account.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/fund" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/fund"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/fund`


<!-- END_e2e0a6d8936f87ea1d1df54528fdb726 -->

<!-- START_e40bc60a458a9740730202aaec04f818 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/admin" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/admin"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET admin`


<!-- END_e40bc60a458a9740730202aaec04f818 -->

<!-- START_322587c4d64eda1bed9566722565f385 -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/admin/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/admin/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET admin/create`


<!-- END_322587c4d64eda1bed9566722565f385 -->

<!-- START_dce9e13bc7a2be0f182ec384476840f5 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/admin" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/admin"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST admin`


<!-- END_dce9e13bc7a2be0f182ec384476840f5 -->

<!-- START_fd598c4c741a0ac21b46ff5eef85509c -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/admin/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/admin/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET admin/{admin}`


<!-- END_fd598c4c741a0ac21b46ff5eef85509c -->

<!-- START_23980be196a62049756862ebdd13a836 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/admin/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/admin/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET admin/{admin}/edit`


<!-- END_23980be196a62049756862ebdd13a836 -->

<!-- START_3a16ec77cff895853eb95e9abc97aa94 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/admin/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/admin/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT admin/{admin}`

`PATCH admin/{admin}`


<!-- END_3a16ec77cff895853eb95e9abc97aa94 -->

<!-- START_881308974fd5fb1881b8ba78e3efa2f1 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/admin/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/admin/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE admin/{admin}`


<!-- END_881308974fd5fb1881b8ba78e3efa2f1 -->


