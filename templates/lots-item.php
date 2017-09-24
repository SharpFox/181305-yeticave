<?php foreach ($lots as $key => $lot): ?>
<li class="lots__item lot">
    <div class="lot__image">
        <img src=<?=$lot['url'];?> width="350" height="260" alt=<?=$lot['category'];?>>
    </div>
    <div class="lot__info">
        <span class="lot__category"><?=$lot['category'];?></span>
        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id'];?>"><?=$lot['name'];?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?=$lot['cost']; ?><b class="rub">р</b></span>
            </div>
            <div class="lot__timer timer">
                <?=getHumanTimeUntilRateEnd($lot['endTime']);?>
            </div>
        </div>
    </div>
</li>
<?php endforeach; ?>