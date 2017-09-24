<nav class="nav">
    <ul class="nav__list container">
        <?php foreach($categories as $key => $category)
                foreach($category as $value): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?=$value;?></a>
        </li>
        <?php endforeach; ?>            
    </ul>
</nav>