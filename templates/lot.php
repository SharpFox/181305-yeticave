<main>
    
    <?=$navigationMenu;?>

    <section class="lot-item container">

        <h2><?=$lot['lotName']?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src=<?=$lot['url']?> width="730" height="548" alt=<?=$lot['lotName']?>>
                </div>
                <p class="lot-item__category">Категория: <span><?=$lot['category']?></span></p>
                <p class="lot-item__description"><?=$lot['description']?></p>
            </div>
            <div class="lot-item__right">
                <?php if ($isAuth): ?> 
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?=getHumanTimeUntilRateEnd($lot['endTime'])?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=$lot['lastCost']?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?=$lot['currentCost']?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="lot.php?id=<?=$lot['lotId'];?>" method="post">
                        <?php if (!$isBetMade):?>   
                        <p class="lot-item__form-item">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="number" step="<?=$lot['step']?>" value="<?=$lot['currentCost']?>" name="cost" placeholder="<?=$lot['currentCost']?>">
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                        <?php endif; ?>
                    </form>
                </div>
                <?php endif; ?>
                <div class="history">
                    <h3>История ставок (<span><?=$lot['quantityBets']?></span>)</h3>
                    <table class="history__list">
                        <?php foreach($bets as $key => $bet): ?>
                        <tr class="history__item">
                            <td class="history__name"><?=$bet['user']; ?></td>
                            <td class="history__price"><?=$bet['cost']; ?> р</td>
                            <td class="history__time"><?=getHumanTimeOfLastRate($bet['createdTime']); ?></td>                            
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>

</main>