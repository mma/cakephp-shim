# Features

## Session component shim
The session should be used directly via `$this->request->session()` object.
But when upgrading you might want to keep the old way until you can refactor it fully:
```php
$components = array('Shim.Session');
```
and you don't have to change your code.


## Controller
When using the Shim plugin Controller class you can set your pagination defaults via
Configure
```
'Paginator' => [
    'limit' => ... // etc
]
```

You can also use `disableCache()` to auto-include `'Pragma' => 'no-cache'` which
shims it for older (IE) version to work there, as well.

## Component

Convenience class that automatically provides the component's methods with
the controller instance via `$this->Controller`. Extend is as follows:
```
namespace App\Controller\Component;

use Shim\Controller\Component\Component;

class MyComponent extends Component {
}
```

## Model
By using the Shim plugin Table class you can instantly re-use some 2.x behaviors.
This is super-useful when upgrading a very large codebase and you first need to get it to 
run again - and afterwards want to start refactoring it.

It will by default look if it can re-use the following (if not nothing bad happens^^):
- `$primaryKey`
- `$displayField`, 
- `$order` (also with correct auto-aliasing)
- `$validate` (needs minor adjustments)
- `$actsAs`
- all relations (`$belongsTo`, `$hasMany`, ...) as it would be very time-consuming to
manually adjust all those.

Also:
- Table::find('first') support.
- Table::find('count') support.
- Table::field() support and fieldByConditions() alias to migrate to.

It auto-adds Timestamp behavior if `created` or `modified` field exists in table.
To customize use `$this->createdField` or `$this->modifiedField` in your `initialize()` method
*before* calling `parent::initialize()`.
To deactivate simple set the two fields to `false`.