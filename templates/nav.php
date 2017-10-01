<nav class="nav">
    <ul class="nav__list container">
        <?php foreach($categories as $category):?>
        <li class="nav__item"<?=($category['id'] === $currentCategoryId) ? ' nav__item--current' : ''; ?>>
            <a href="all-lots.php?page=<?="1"?>&category-id=<?=$category['id']?>"><?=$category['name'];?></a>
        </li>
        <?php endforeach; ?>            
    </ul>
</nav>