# Simple Apache-Based MVC Framework

Before we get started, head to App/Configuration/Main.php and configure your settings.

## Documentation
Alright so we have our project folder, we have 1 file, `.htaccess`, this one will route all requests to the `Public` folder, this means no file/directory traversal.
Inside the `Public` folder, we have another `.htaccess` file, this one will route all requests to index.php, index.php is what pulls our application together.

```php
require("../App/Bootstrap.php");

Session::init();
App::run();
```

We are initializing a new session every time we visit a different page, because again, all our requests are being routed to index.php. And that code is from index.php.
We run the App class, this is like our router, it will load the requested controller and it can handle any errors or edge-cases.

### Handling Requests
When we take a look at Request.php inside App/Core, there are 4 **static** methods we need to know:
- data() will return POST or $_FILES data.
- query() will return GET data.
- isPost() & isGet() checks if the request method is post. And same for isGet().

When we take a look at Response.php inside App/Core, there are 2 **static** methods we need to know:
- error() will throw an error to the user along with a view, see App/Views/Errors. error() requires 2 parameters, the view name (In Views/Errors) and an HTTP status code.
- redirect_to() self explanatory. Leave it empty with no arguments to redirect to the index/home page.

When we take a look at Session.php inside App/Core, there are 4 **static** methods we need to know:
- set()
- get()
- delete() Deletes specific session data.
- destroy() Deletes all session data.
The rest are self-explanatory.

### Making A Controller:
Firstly, go in App/Templates, and go into Controller.php, copy the code inside the comments, now go in App/Controllers, create a new file with the name of your controller. Keep in mind, the name of your controller can be visited by the router, for example, I named a controller "Products", when I go to website.com/products, it will work, anyway, paste what you copied into your new controller file. For this demonstration, we will make a products controller, name your file Products.php, and paste the code from the template:

```php
class CONTROLLER_NAME extends Controller {
    private static $middleware = [];
    private $primaryModel;

    public function __construct($request = null, $method = null) {
        parent::__construct($request, $method, self::$middleware);

        // $this -> primaryModel = $this -> model();
    }


    public function index() {
        
    }
}
```

Replace CONTROLLER_NAME with the name of the file, we named it "Products.php", so just type "Products" in there.
The index method:

```php
public function index() {

}
```

It means the root route, so when I go to website.com/products, the index method will be running, and you can do your controller code there, and it will list all the products in the view (if you'd like.).
Now if I want to make it dynamic, I want it to be wesite.com/products/5, here is how we can do it.

```php
public function index(?int $number = null) {
    echo $number;
}
```

`$number` by default will be null, which means it's optional, we're also type-casting it as an integer, when we visit website.com/products/hello-word, we would get a bad request error, but if we visit website.com/products/1, then the number will be echoed, let's see how we make this dynamic:

```php
public function index(?int $number = null) {
    if (!is_null($number)) {
        echo "Product {$number}";
    } else {
        echo "All Products";
    }
}
```

We can also make more methods for this controller, let's take a look, in the controller, I will add this method:

```php
public function view(int $number) {
    echo "Product {$number}";
}
```

Notice how the parameter is different this time, that means it's required, and `App.php` will handle it all for us.
So when I go to website.com/products/view/4, this is what we get.
To render a view, go inside a controller method, and use the view() method like so:
```php
$this -> view("Home");
```

You can additionally pass some data that a view can reach, for example:

```php
$data = ["firstName" => "John", "lastName" => "Doe"];
$this -> view("Home", $data);
```

*App/Views/*Home*.php*:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h2>Hello <?= $data["firstName"] ?></h2>
    <h2>Hello <?= $data["lastName"] ?></h1>
</body>
</html>
```

Alright, let's do a recap.

```php
class Products {
    private static $middleware = [];
    private $primaryModel;

    public function __construct($request = null, $method = null) {
        parent::__construct($request, $method, self::$middleware);

        // $this -> primaryModel = $this -> model();
    }

    public function index(?int $number = null) { // website.com/products OR website.com/products/5.
        if (!is_null($number)) {
            echo "Product {$number}";
        } else {
            echo "All Products";
        }
    }

    public function view(int $number) { // website.com/products/view/5.
        echo "Product {$number}";
    }
}
```

To link a model to a controller, you can look at the code above, you can notice at the __construct(), there is a commented line, this line would connect a model to the controller, simply uncomment it, and inside the model() method, there is 1 parameter, which is the name of the model to link, it will look through App/Models. To use the model, you can reference it by typing `$this -> primaryModel -> ...`.

The index method just means the main page for that controller.
You can set up middleware for each of those methods:

```php
private static $middleware = [
    "index" => ["Authenticated", "IsACoolGuy"],
    "view" => ["Authenticated"]
];
```

The framework will look through the App/Middleware folder and use the __construct, you can add your own middleware by going to App/Templates/Middleware.php and doing the same thing again as with the controller template.

### Making A Model
Inside App/Models, you can add a new class, you can name it anything, we'll name it Products.php.
Make sure the model class inherits from the database class.

```php
class Products extends Database {
    public function __construct() {
        parent::__construct();
    }
}
```

You can add your own methods that a controller can use:

```php
class Products extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function fetchOne($id) {
        return $this -> query("SELECT * FROM Products WHERE ID = ?", [$id]) -> fetch();
    }

    public function fetchAll() {
        return $this -> query("SELECT * FROM Products") -> fetchAll();
    }
}
```

These methods may be really short, but once you move beyond basic queries, you'll be writing more code for a method.

🔴 And yeah, that's prety much it, you can take a look at the code of what keeps this framework together if you'd like. You can run this application using an Apache web server like XAMPP, MAMP, or Laragon.