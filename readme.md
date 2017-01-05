# laravel-hookr

A laravel package for action and filter hook. Its helps to you fire any event with your desire action. Its a similar service as WP action and filter.
  
## Installation

Write these command from you terminal.

```shell
composer require nahid/hookr
```

## Configuration

After complete installation go to `config/app.php` and add this line in providers section

```php
Nahid\Hookr\HookrServiceProvider::class,
```

and add this line in aliases section

```php
'Hook'  =>  Nahid\Hookr\Facades\Hook::class,
```

Thats all

## Usages

 Its so easy to use. Just follow the instruction and apply with your laravel project.
 
### Action

You want to extra control with your application without touching your code you apply Action. Suppose you have a blog editor panel. Where you want add extra buttons from others developer without rewrite your code.
so lets see.


  ```html
  <!-- post.blade.php -->
  <form>
      <div class="form-group">
          <label for="title">Title</label>
          <input type="email" class="form-control" id="title" placeholder="Email">
      </div>

      <div class="form-group">
          <label for="blog">Blog</label>
          <textarea id="blog" cols="30" rows="10" class="form-control"></textarea>
      </div>

      <button type="submit" class="btn btn-default">Publish</button>
      {{hook_action('buttons')}}
  </form>
  ```
  
  
  ![Demo](http://i.imgur.com/xqN1brq.png "demo")
  
  See, here we use `hook_action()` helper function which is register as named `buttons`
  So if others developer is want to add more buttons with this form they will do this
  
  ```php
  use Nahid\Hookr\Facades\Hook;
  
  class BlogController extends Controller
  {
        public function getWritePost()
        {
            Hook::bindAction('buttons', function() {
                echo ' <button class="btn btn-info">Draft</button>';
            }, 2);
            
            return view('post');
       }
  }
  ```
  
  After run this code add new button will add with existing button. 
  
  

  ![Demo](http://i.imgur.com/Udy1TkG.png "demo")
