<div id="news">
    <a href="" class="arrow left"></a>
    <a href="" class="arrow right"></a>
    <h3 class="title">Новости</h3>
    <div id="news_carousel">
        <div>
        <ul>
        <?
        if( !empty($top_news) ) { 
            foreach( $top_news as $n ){
                $preview = ( empty($n['preview']) ) ? '' : '<img src="/files/posts/'.$n['preview'].'" alt="" />';
            ?>
            <li><a href="<?= post_link( $n ) ?>"><?= $preview ?> <?= $n['title'] ?></a></li>
            <?            
            }        
        }
        ?>
        </ul>
        </div>
    </div>
    <!--
    <div class="block n1">
        <a href="">
            <img src="/imgs/!n1.jpg" alt=""/>
            Хочется надеяться, что все на уровне. По крайней мере песенка Nyx очень понравилась. Спасибо!
        </a>
    </div>
    <div class="block n2">
        <a href="">
            <img src="/imgs/!n2.jpg" alt=""/>
            Хочется надеяться, что все на уровне. По крайней мере песенка Nyx очень понравилась. Спасибо!
        </a>
    </div>
    <div class="block n3">
        <a href="">
            <img src="/imgs/!n3.jpg" alt=""/>
            Хочется надеяться, что все на уровне. По крайней мере песенка Nyx очень понравилась. Спасибо!
        </a>
    </div>
    -->
</div>
