Swiftlet
========

Swiftlet is quite possibly the smallest 
[http://en.wikipedia.org/wiki/Model-view-controller](MVC) framework you'll ever 
use. It's also swift.


Buzzword compliance
-------------------

✔ Micro-Framework  
✔ Pluggable  
✔ MVC  
✔ OOP  
✔ PHP5  

✘ ORM  


Installation in three easy steps
--------------------------------

* Step 1: Clone (or download and extract) Swiftlet into a directory on your PHP
  supported web server.
* Step 2: Congratulations, Swiftlet is now up and running.
* Step 3: There is no step 3.


Getting started: controllers and views
--------------------------------------

Let's create a page. Each page consists of a controller and at least one view.

Controllers house the 
[business logic](http://en.wikipedia.org/wiki/Business_logic) of the page while 
views should be limited to simple UI logic (loops and switches).

**Controller `controllers/FooController.php`**

```php
<?php

class FooController extends SwiftletController
{
	protected
		$_title = 'Foo'
		;

	public function indexAction()
	{
		$this->_app->getView()->set('hello world', 'Hello world!');
	}
}
```

**View `views/foo.html.php`**

```php
<h1><?php echo $this->getTitle() ?></h1>

<p>
	<?php echo $this->get('hello world') ?>
</p>
```

You can now view the page by navigating to `/foo` in your web browser!


Routing
-------

Notice how we can access the page at `/foo` by simply creating a controller 
named `FooController`. The application (Swiftlet) automatically maps URLs
to controllers, actions and arguments.

Consider the following URL: `/foo/bar/baz/qux`

In this case `foo` is the controller, `bar` is the action and `baz` and `qux`
are arguments. If the controller or action is missing they will default to 
`index`.


Actions
-------

Actions are methods of the controller. A common example might be `edit` or
`delete`:

`/blog/edit/1`

This will call the function `editAction()` on `BlogController` and pass the
argument `1` (i.e. the id of the blog post we're editing).

If the action doesn't exist `notImplementedAction()` will be called instead.
This will throw an exception by default.

The action name and arguments can be accessed by calling
`$this->_app->getAction()` and `$this->_app->getArgs()` respectively.


Models
------------

Let's throw a model into the mix and update the controller.

**Model `model/FooModel.php`**

<?php

class FooModel extends SwiftletModel
{
	public function getHelloWorld() {
		return 'Hello world!';
	}
}
```

**Controller `controllers/FooController.php`**

```php
<?php

class FooController extends SwiftletController
{
	protected
		$_title = 'Foo'
		;

	public function indexAction()
	{
		$exampleModel = $this->_app->getModel('example');

		$helloWorld = $exampleModel->getHelloWorld();

		$this->_app->getView()->set('hello world', $helloWorld);
	}
}
```

TODO

TODO: Plugins
-------------

TODO: More examples and documentation 
-------------------------------------

--------------------------------------------------------------------------------