<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TestController extends MY_Controller {

    public function __construct(){
        parent::__construct();
    }
    
	public function index(){
        // загрузили наши модели
        
        $this->load->model( array('post','tag','module') );
        
        // у авторизованного пользователя в контроллере должен быть $this->current_user массив
        // или же можно использовать $user = current_user(); хелпер
        
        $user = current_user();
        if( $user['id'] ){
            // 1. создаем пост ---------------------
            $post = array(
                'title'   => 'Наш замечательный заголовок топика',
                'tags'    => 'тэги, сиськи, эротика, киска, шалости',
                'user_id' => $user['id']
            );

            $post_id = $this->post->save( $post );
            // if( !post_$id ) значи бяда какая-то, надо писатьнуть в лог запись или отдельный файл неудач
            // чтобы потом заимпортировать
            $this->template->set( 'content', "Создали новый пост с post_id: {$post_id}<br/>" );
            
            // 2. добавляем модуль к посту ----------------
            // теперь с постом свяжем наш модуль TEXT
            $module = array(
                'post_id' => $post_id,
                'name'    => 'text'
            );
            $module_id = $this->module->add_new( $module );
            $this->template->append( 'content', "Добавили модуль TEXT к нашему посту с module_id: {$module_id}<br/>" );
            
            // 3. заполняем модуль контентом ----------------
            // теперь засунем наполнение этого модуля text
            $this->load->model('text/text'); // загрузим модель text модуля text (модуль/модель точнее)
            $text =  array(
                'module_id' => $module_id,
                'original'      => 'Тут будет наш большой текст из ЖЖ'
            );
            $this->text->save( $text ); // модель text не возвращает text_id так как у нее Primary Key это module_id в коде видно замену
            $this->template->append( 'content', "Заполнили модуль TEXT новым текстом<br/>" );
            
            // 4. сборка и публикация нашего поста -----------------
            // сбора мы используем HMVC чтобы из этого контроллера вызвать Post с публичным методом make( $post_id ) для сборки
            $post = Modules::run( 'post/make', $post_id );            
            $post['published'] = 1; // флаг опубликованности
            $this->post->save( $post ); // и сохранили
            $this->template->append( 'content', "Топик собран и опубликован <a href='/blog/show/{$post_id}' target='_blank'>здесь</a><br/>" );
            
            // ---- ну и разумеется это надо всё в цикле делать или даже вынести одним методом 
            // и вызывать его с данными в цикле
            
        }else{
            set_flash_error('Прежде чем импортировать вам надо войти на сайт');
            redirect( 'user/login' );
        }
        $this->draw();        
    }
}
