Silicone Skeleton
=================

Silicone Skeleton is Silex Framework Edition Skeleton.

Every part is configurable. You can choose anything you want.

This Silex modification contains the following:
* HttpCache
* Class Controllers
* Doctrine Common
* Doctrine ORM
* Monolog
* Session
* Twig
* Translation
* Validator
  * Unique validator for entities
* Forms
* Security
* User Registration and Authorization.
* Annotation Routes
* WebProfiler (with Doctrine queries logger)
* Console

Structure
---------
Structure of Silicone is very similar to Symfony's.
```
app/
    config/  -- Configuration
    lang/    -- Language Yml, Xliff files
    open/    -- Writable directory for caches, logs, ext.
    src/     -- Application sources
    vendor/  -- Vendors
    view/    -- Twig view files
    console  -- Console Tool
web/
    index.php
```

Controller
----------
You can use Silex controllers `$app->get(...)` with class controllers.
```php
class Blog extends Controller
{
    /**
     * @Route("/blog/{post}")
     */
    public function post($post)
    {
        return $this->render('post.twig');
    }
}
```

Doctrine ORM
------------
You can use all Doctrine ORM functionality, not just DBAL. Create file `app/src/Entity/Post.php`:
```php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column
     * @Assert\NotBlank()
     * @Assert\Length(min = "3", max = "1000")
     */
    protected $text;
}
```

After this just run:
```
app/console schema:update
```

Install
-------

Use Composer to create a new project:
```
composer create-project elfet/silicone-skeleton your/app/path
```

Open directory used for writing caches, logs, ext. So you must give write permissions for www-data user.
Example:
```
sudo chmod +a "www-data allow delete,write,append,file_inherit,directory_inherit" app/open/
sudo chmod +a "[your user name] allow delete,write,append,file_inherit,directory_inherit" app/open/
```

Add permissions to execute console command.
Example:
```
chmod +x app/console
```

Database
--------
After configuring console run the following commands to create sample database:
```
app/console database:create
app/console schema:create
```


TODO
----
* Documentation
* Tests
* SwiftMailer

