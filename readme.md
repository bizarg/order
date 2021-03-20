# Order

```PHP
<?php
$order = Order::fromRequest(
    Illuminate\Http\Request $request, //required
    ['createdAt' => 'created_at'], //required
    'createdAt', //optional
    'asc', //optional
    'sort' //optional
);

//---------------------------------------
$oreder = (new Order())->setField('created_at')->setDirection('desc');
```

