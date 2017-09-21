<?=$navigationMenu;?>

<section class="lot-item container">
    <h2><?=$goodsContent[$goodsItem]['name']?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src=<?=$goodsContent[$goodsItem]['url']?> width="730" height="548" alt=<?=$goodsContent[$goodsItem]['name']?>>
            </div>
            <p class="lot-item__category">Категория: <span><?=$goodsContent[$goodsItem]['category']?></span></p>
            <p class="lot-item__description"><?=$goodsContent[$goodsItem]['description']?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($isAuth): ?> 
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                    <?=$goodsContent[$goodsItem]['lotTimeRemaining']?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=$goodsContent[$goodsItem]['cost']?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=$goodsContent[$goodsItem]['cost']?></span>
                    </div>
                </div>
                <form class="lot-item__form" action="lot.php?id=<?=$goodsItem;?>" method="post">
                    <p class="lot-item__form-item">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="number" step="<?=$goodsContent[$goodsItem]['step']?>" value="<?=$goodsContent[$goodsItem]['cost']?>" name="cost" placeholder="<?=$goodsContent[$goodsItem]['cost']?>">
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span>4</span>)</h3>
                <table class="history__list">
                    <?php foreach($bets as $key => $value): ?>
                    <tr class="history__item">
                        <td class="history__name"><?=$value['name']; ?></td>
                        <td class="history__price"><?=$value['price']; ?> р</td>
                        <td class="history__time"><?=getHumanTimeOfLastRate($value['ts']); ?></td>                            
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>