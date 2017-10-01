<main class="container">

    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= $category['class']; ?>">
                <a class="promo__link" href="all-lots.php?page=1&category-id=<?=$category['id']?>"><?= $category['name']; ?></a>
            </li>
            <?php endforeach; ?>           
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
            <select class="lots__select" id="select" onchange="selectCategory()">
            <option>Все категории</option>   
                <?php foreach($categories as $category): ?>
                    <option <?php if ($currentCategoryId === $category['id']) {
                        ?> selected <?php 
                        }; ?> value="<?= $category['id']; ?>">
                        <?= $category['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <ul class="lots__list">
            <?=$lotsItemContent;?>
        </ul>

        <?=$paginatorContent?>
        
    </section>
    
</main>