cakephp-github-datasource
==========================

A simple datasource to read a users Github activity. No frills, just does exactly what it says on the tin. No more, no less. Hopefully it's of some use to someone.

##Usage

After you have the model and the datasource files in the correct place, you need to edit your database.php configuration file within your app, to include the following;

```php
public $github= array(
  'datasource' => 'GithubSource',
  'username' => 'synbyte',
  'count' => '5' 
);
```

You can then do a simple find within a controller, like so.

```php
$this->set('github', $this->Github->find('all'));
```

Then try ```debug($github);``` in a view to see the data.

#That's it. Enjoy!
