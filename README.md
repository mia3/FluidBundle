# FluidBundle

## Installation

1. install package

```
composer require mia3/fluidbundle
```

2. activate bundle

Add ```new Mfc\Symfony\Bundle\FluidBundle\FluidBundle()``` to the Bundles in the ```app\AppKernel.php```

3. add fluid template engine

Add ```fluid``` to the ```framework.templating.engines``` array in ```app/config/config.yml```


## Rendering Fluid Templates

You can simply use the built-in render method in the controller to render a fluid template like this:

```
  return $this->render('SomeController/Index.html', [
    'foo' => 'bar'
  ]);
```

## Template Paths

Templates are loaded from ```app/Resources/Templates/```, ```app/Resources/Layouts/``` and ```app/Resources/Partials/```
