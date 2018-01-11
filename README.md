# Nextras/Orm -Console command
Using [Kdyby\Console](https://github.com/Kdyby/Console), don´t forget to config file

`create:orm article Articles\Article articles`

will genereate 3 files in folder Article (into specific directory):

**Article.php**
```php
namespace App\Orm\Articles\Article;

use Nextras\Orm\Entity\Entity;

/**
 * Article Entity class
 * @property int $id {primary}
 */
class Article extends Entity
{
}
```

**ArticleRepository.php**
```php
namespace App\Orm\Articles\Article;

use Nextras\Orm\Repository\Repository;

class ArticleRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Article::class];
	}
}
```

**ArticleMapper.php**
```php
namespace App\Orm\Articles\Article;

use Nextras\Orm\Mapper\Mapper;

class ArticleMapper extends Mapper
{
	protected $tableName = 'articles';
}
```

## Command syntax
[0] command name 

[1] entity name

[2] namespace (optional)

[3] table name (optional) 

## Config constans
You can setup your custom extends Parents. For Example custom entity:

```php
const NS_ENTITY = 'App\Core\Entity';
```

