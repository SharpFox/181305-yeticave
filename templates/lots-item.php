<?php foreach ($goodsContent as $key => $value): ?>
<li class="lots__item lot">
    <div class="lot__image">
        <img src=<?=$value['url'];?> width="350" height="260" alt=<?=$value['category'];?>>
    </div>
    <div class="lot__info">
        <span class="lot__category"><?=$value['category'];?></span>
        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $key;?>"><?=$value['name'];?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?=$value['cost']; ?><b class="rub">р</b></span>
            </div>
            <div class="lot__timer timer">
                <?=$value['lotTimeRemaining'];?>
            </div>
        </div>
    </div>
</li>
<?php endforeach; ?>