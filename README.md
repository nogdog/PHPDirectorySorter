# PHPDirectorySorter
Class for getting a file system directory list and providing ways to sort it

## Usage

```php
require_once 'path/to/Dir.php';
$dir = new Dir('/path/to/some/directory');
$dir->sortByTime();
print_r($dir->data());
```
