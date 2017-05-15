<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates'
    ));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'microblog',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'charset'=> 'utf8'
    )
    ));

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['swiftmailer.options'] = array(
    'host' => 'gmail.com',
    'username' => 'email1@gmail.com',
    'password' => 'password'
);

$app->register(new Silex\Provider\SessionServiceProvider());


/*
 * Home Page
 */
$app->get('/{page}', function($page) use($app){
    
    $page = (int)$page;
    $per_page = 3;
    
    $posts_count = (int)$app['db']->fetchColumn('SELECT COUNT(*) FROM posts', array(), 0);
    
    $last_page = ceil($posts_count/$per_page);
        
    if($page<1){ $page = 1;}
    else if($page>$last_page){$page = $last_page;}
    
    $offset = ($page-1)*$per_page;
    
    $posts_list = $app['db']->fetchAll("SELECT * FROM posts ORDER BY date DESC LIMIT {$offset}, {$per_page}");
    
    return $app['twig']->render('index.twig', array(
        'posts_list' => $posts_list,
        'curr_page' => $page,
        'last_page' => $last_page
    ));
})
->bind('home')
->value('page', 1)
->assert('page', '\d+');

/*
 * Blog post
 */
$app->match('/post/{id}', function (Symfony\Component\HttpFoundation\Request $request, $id) use($app){
    
    $id = (int)$id;
    
    $post = $app['db']->fetchAssoc('SELECT * FROM posts WHERE id = ?', array($id));
    
    if(!$post){
        $app->abort(404, 'Nie znaleziono wpisu!');
    }
    
    $form = $app['form.factory']->createBuilder('form')
            ->add('author', 'text', array(
                'label' => 'Imię i nazwisko:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )
            ))
            ->add('comment', 'textarea', array(
                'label' => 'Komentarz:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )
            ))->getForm();
    
    if($request->isMethod('POST')){
        $form->bind($request);
        if($form->isValid()){
            $data = $form->getData();
            $app[db]->insert('comments', array(
                'post_id' => $id,
                'author' => $data['author'],
                'comment' => $data['comment'],
                'date' => date('Y-m-d H:i:s')
            ));
            
            $redirect_url = $app['url_generator']->generate('blog_post', array('id' => $id));
            
            return $app->redirect($redirect_url);
        }
    }
    
    $comments = $app['db']->fetchAll('SELECT * FROM comments WHERE post_id = ? ORDER BY date DESC', array($id));
    
    return $app['twig']->render('post.twig', array(
        'post' => $post,
        'comments_list' => $comments,
        'form' => $form->createView()
    ));
})
->method('GET|POST')
->bind('blog_post');
/*
 * Contact
 */
$app->match('/contact', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
    $form = $app['form.factory']->createBuilder('form')
            ->add('author', 'text', array(
                'label' => 'Imię i nazwisko:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )
            ))
            ->add('email', 'text', array(
                'label' => 'Email:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    )),
                    new Assert\Email(array(
                        'message' => 'Podałeś nie poprawny Email'
                    ))
                )
            ))
            ->add('message', 'textarea', array(
                'label' => 'Wiadomość:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )
            ))->getForm();
    
    if($request->isMethod('POST')){
        $form->bind($request);
        if($form->isValid()){
            
            $data = $form->getData();
            
            $msg_body = "Użytkownik: {$data['author']}\nE-mail: {$data['email']}\nWiadomość: {$data['message']}";
            $subject = "Wiadomość od {$data['author']}";
            $swift_message = Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom('email1@gmail.com', 'Microblog - Kontakt')
                    ->setTo(array('email2@gmail.com'))
                    ->setBody($msg_body);
            $app['mailer']->send($swift_message);
            $app['session']->set('msgConfirm', 'Dziękuje za wiadomość');
            $redirect_url = $app['url_generator']->generate('contact');
            
            return $app->redirect($redirect_url);
        }
    }
    
    return $app['twig']->render('contact.twig', array(
        'form' => $form->createView()
    ));
})
->method('GET|POST')
->bind('contact');
/*
 * About
 */
$app->get('/about', function () use($app){
    return $app['twig']->render('about.twig');
})
->bind('about');

/*
 * Rejestracja
 */
$app->match('/register', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
    $form = $app['form.factory']->createBuilder('form')
            ->add('login', 'text', array(
                'label' => 'Login:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    )),
                )
            ))
            ->add('password', 'password', array(
                'label' => 'Hasło:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    )),
                    new Assert\Length(array('min' => 5)),
                    new Assert\Length(array('max' => 20))
                )
            ))->getForm();
    
    if($request->isMethod('POST')){
        $form->bind($request);
        if($form->isValid()){
            $data = $form->getData();
            $app[db]->insert('users', array(
                'login' => $data['login'],
                'password' => $data['password']
            ));
            
            $redirect_url = $app['url_generator']->generate('home');
            
            return $app->redirect($redirect_url);
        }
    }
    
    
    return $app['twig']->render('register.twig', array(
        'form' => $form->createView()
    ));
})
->method('GET|POST')
->bind('register');

/*
 * Logowanie
 */
$app->match('/login', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
    $form = $app['form.factory']->createBuilder('form')
            ->add('login', 'text', array(
                'label' => 'Login:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )
            ))
            ->add('password', 'password', array(
                'label' => 'Hasło:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )
            ))->getForm();
    
    if($request->isMethod('POST')){
        $form->bind($request);
        if($form->isValid()){
            $data = $form->getData();
            $base = $app['db']->fetchAssoc('SELECT * FROM users WHERE login = ?', array($data['login']));
            if($data['login'] === $base['login']){
                if($data['password'] === $base['password']){
                    
                $redirect_url = $app['url_generator']->generate('insert');
            
                return $app->redirect($redirect_url);
             
        }}}
    }
  
    return $app['twig']->render('login.twig', array(
        'form' => $form->createView()
    ));
})
->method('GET|POST')
->bind('login');

/*
 * Insert
 */
$app->match('/insert', function (Symfony\Component\HttpFoundation\Request $request) use($app){

    $form = $app['form.factory']->createBuilder('form')
            ->add('author', 'text', array(
                'label' => 'Autor:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'                       
                    ))
                )
            ))
            ->add('title', 'text', array(
                'label' => 'Tytuł:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )))
            ->add('content', 'textarea', array(
                'label' => 'Treść:',
                'attr' => array(
                    'placeholder' => 'Pole wymagane'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'message' => 'To pole nie może być puste!'
                    ))
                )    
            ))->getForm();
    if($request->isMethod('POST')){
        $form->bind($request);
        if($form->isValid()){
            $data = $form->getData();
            $app[db]->insert('posts', array(
                'title' => $data['title'],
                'content' => $data['content'],
                'author' => $data['author'],
                'date' => date('Y-m-d H:i:s')
            ));
            
            $redirect_url = $app['url_generator']->generate('home');
            
             return $app->redirect($redirect_url);
    
        }
    }
    
    return $app['twig']->render('insert.twig', array(
        'form' => $form->createView()
    ));
})
->method('GET|POST')
->bind('insert');


//run application
$app->run();
