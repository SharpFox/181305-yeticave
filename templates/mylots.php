<?=$navigationMenu;?>

  <section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
    <?php foreach ($ratedContent as $key => $value): ?>
      <tr class="rates__item rates__item--win">
        <td class="rates__info">
          <div class="rates__img">
            <img src=<?=$value['url'];?> width="54" height="40" alt=<?=$value['category'];?>>
          </div>
          <div>
            <h3 class="rates__title"><a href="lot.php?id=<?=$value['goodsItem'];?>"><?=$value['name'];?></a></h3>
          </div>
        </td>
        <td class="rates__category">
            <?=$value['category'];?>
        </td>
        <td class="rates__timer">
          <div class="timer timer--win">Ставка выиграла</div>
        </td>
        <td class="rates__price">
            <?=$value['cost'];?>
        </td>
        <td class="rates__time">
          <?=gmdate(strtotime('now') - $value['lotTimeRemaining']);?>
        </td>
      </tr>  
      <?php endforeach; ?>    
    </table>
  </section>